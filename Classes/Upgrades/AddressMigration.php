<?php

declare(strict_types=1);

namespace Ps14\Migration\Upgrades;

use TYPO3\CMS\Core\Attribute\UpgradeWizard;
use TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Expression\CompositeExpression;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Adressen (ps14_foundation 2.x / tt_address 9) -> ps14_address v14
 *
 * - tt_address: Felder "tx_foundation_*" -> "tx_address_*", YouTube -> "youtubechannel" (tt_address 10)
 * - Oeffnungszeiten: "tx_foundation_domain_model_openinghours" -> "tx_address_openinghours" (gleiche UIDs)
 * - Plugin "list"/"ps14foundation_addressrecord" -> CType "ps14_address", FlexForm-Schluessel umbenannt
 *
 * Felder werden nur uebernommen, wenn das Ziel leer ist; ein zweiter Lauf aendert nichts.
 */
#[UpgradeWizard('ps14Migration_addressMigration')]
final class AddressMigration extends AbstractMigration
{
    private const ADDRESS_FIELDS = [
        'tx_foundation_directors' => 'tx_address_directors',
        'tx_foundation_commercial_register' => 'tx_address_commercial_register',
        'tx_foundation_registered_office' => 'tx_address_registered_office',
        'tx_foundation_vat_number' => 'tx_address_vat_number',
        'tx_foundation_maps_link' => 'tx_address_maps_link',
        'tx_foundation_additional_description' => 'tx_address_additional_description',
        'tx_foundation_opening_hours_description' => 'tx_address_opening_hours_description',
        'tx_foundation_opening_hours' => 'tx_address_opening_hours',
        'tx_foundation_youtube' => 'youtubechannel',
    ];

    private const NUMERIC_FIELDS = ['tx_address_opening_hours'];

    private const OPENING_HOURS_SOURCE = 'tx_foundation_domain_model_openinghours';

    private const OPENING_HOURS_TARGET = 'tx_address_openinghours';

    /**
     * Spalten, die in beiden Tabellen vorkommen (Quelle zusaetzlich: cruser_id, t3ver_id, t3ver_label, ...)
     */
    private const OPENING_HOURS_COLUMNS = [
        'uid', 'pid', 'tstamp', 'crdate', 'deleted', 'hidden', 'starttime', 'endtime', 'sorting',
        'sys_language_uid', 'l10n_parent', 'l10n_diffsource', 'l10n_state', 't3ver_oid', 't3ver_wsid', 't3ver_state', 't3ver_stage',
        'address', 'days', 'days_title', 'open_at', 'close_at', 'category',
    ];

    private const PLUGIN = 'ps14foundation_addressrecord';

    private const CTYPE = 'ps14_address';

    /**
     * FlexForm v12 (Sheet, Feld) -> v14 (Sheet, Feld)
     */
    private const FLEXFORM_FIELDS = [
        ['main', 'settings.records', 'main', 'address'],
        ['display', 'settings.display.name', 'display', 'showName'],
        ['display', 'settings.display.description', 'display', 'showDescription'],
        ['display', 'settings.display.location', 'display', 'showLocation'],
        ['display', 'settings.display.contact', 'display', 'showContact'],
        ['display', 'settings.display.url', 'display', 'showUrl'],
        ['display', 'settings.display.openingHours', 'display', 'showOpeningHours'],
        ['seo', 'settings.structuredDataType', 'seo', 'structuredData'],
        ['seo', 'settings.structuredDataOpeningHoursCategory', 'seo', 'openingHoursCategory'],
    ];

    private const VARIANTS = ['default', 'imprint'];

    public function __construct(
        ConnectionPool $connectionPool,
        private readonly FlexFormTools $flexFormTools,
    ) {
        parent::__construct($connectionPool);
    }

    #[\Override]
    public function getTitle(): string
    {
        return 'ps14: Migrate ps14_foundation addresses to ps14_address';
    }

    #[\Override]
    public function getDescription(): string
    {
        return 'Copies the tt_address fields and opening hours of ps14_foundation to ps14_address and changes the address plugin to the CType "ps14_address".';
    }

    #[\Override]
    public function updateNecessary(): bool
    {
        return $this->getAddressFieldMapping() !== []
            || $this->getOpeningHoursToCopy() !== []
            || $this->getPlugins() !== [];
    }

    #[\Override]
    public function executeUpdate(): bool
    {
        $this->migrateAddressFields();
        $this->migrateOpeningHours();
        $this->migratePlugins();

        return true;
    }

    private function migrateAddressFields(): void
    {
        $connection = $this->connectionPool->getConnectionForTable('tt_address');
        $updated = 0;

        foreach ($this->getAddressFieldMapping() as $source => $target) {
            $queryBuilder = $connection->createQueryBuilder();
            $queryBuilder->getRestrictions()->removeAll();

            $updated += $queryBuilder
                ->update('tt_address')
                ->set($target, $queryBuilder->quoteIdentifier($source), false)
                ->where(...$this->getPendingConditions($queryBuilder, $source, $target))
                ->executeStatement();
        }

        $this->output?->writeln(sprintf('tt_address: %d field values copied.', $updated));
    }

    private function migrateOpeningHours(): void
    {
        $rows = $this->getOpeningHoursToCopy();

        if ($rows === []) {
            return;
        }

        $connection = $this->connectionPool->getConnectionForTable(self::OPENING_HOURS_TARGET);
        foreach ($rows as $row) {
            $connection->insert(self::OPENING_HOURS_TARGET, array_intersect_key($row, array_flip(self::OPENING_HOURS_COLUMNS)));
        }

        $this->output?->writeln(sprintf('%s: %d records copied.', self::OPENING_HOURS_TARGET, count($rows)));
    }

    private function migratePlugins(): void
    {
        $connection = $this->connectionPool->getConnectionForTable('tt_content');
        $plugins = $this->getPlugins();

        foreach ($plugins as $plugin) {
            $variant = (string)$plugin['tx_foundation_variant'];

            if (in_array($variant, self::VARIANTS, true) === false) {
                $this->output?->writeln(sprintf('tt_content %d: variant "%s" does not exist in ps14_address, set to "default".', $plugin['uid'], $variant));
                $variant = 'default';
            }

            $connection->update('tt_content', [
                'CType' => self::CTYPE,
                'list_type' => '',
                'tx_foundation_variant' => $variant,
                'pi_flexform' => $this->convertFlexForm((string)$plugin['pi_flexform']),
            ], ['uid' => (int)$plugin['uid']]);
        }

        $this->output?->writeln(sprintf('tt_content: %d address plugins migrated to "%s".', count($plugins), self::CTYPE));
    }

    private function convertFlexForm(string $xml): string
    {
        $source = $xml !== '' ? GeneralUtility::xml2array($xml) : [];
        $source = is_array($source) ? $source : [];
        $target = ['data' => []];

        foreach (self::FLEXFORM_FIELDS as [$sourceSheet, $sourceField, $targetSheet, $targetField]) {
            $value = $source['data'][$sourceSheet]['lDEF'][$sourceField]['vDEF'] ?? null;

            // Nicht gespeicherte Optionen waren in v12 ausgeschaltet
            if ($value === null && $targetSheet === 'display') {
                $value = '0';
            }

            if ($value !== null) {
                $target['data'][$targetSheet]['lDEF'][$targetField]['vDEF'] = (string)$value;
            }
        }

        return $this->flexFormTools->flexArray2Xml($target);
    }

    /**
     * @return array<string, string> Quellspalte => Zielspalte, nur wenn beide existieren
     */
    private function getAddressFieldMapping(): array
    {
        $mapping = [];

        foreach (self::ADDRESS_FIELDS as $source => $target) {
            if ($this->columnExists('tt_address', $source) && $this->columnExists('tt_address', $target) && $this->hasPendingValues($source, $target)) {
                $mapping[$source] = $target;
            }
        }

        return $mapping;
    }

    private function hasPendingValues(string $source, string $target): bool
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable('tt_address');
        $queryBuilder->getRestrictions()->removeAll();

        return (int)$queryBuilder
            ->count('uid')
            ->from('tt_address')
            ->where(...$this->getPendingConditions($queryBuilder, $source, $target))
            ->executeQuery()
            ->fetchOne() > 0;
    }

    /**
     * Ziel leer, Quelle gefuellt; Zaehlerfelder (Inline) werden numerisch verglichen (Strict Mode)
     *
     * @return list<string|CompositeExpression>
     */
    private function getPendingConditions(QueryBuilder $queryBuilder, string $source, string $target): array
    {
        $expr = $queryBuilder->expr();

        if (in_array($target, self::NUMERIC_FIELDS, true)) {
            return [
                $expr->eq($target, $queryBuilder->createNamedParameter(0, Connection::PARAM_INT)),
                $expr->gt($source, $queryBuilder->createNamedParameter(0, Connection::PARAM_INT)),
            ];
        }

        return [
            $expr->or($expr->isNull($target), $expr->eq($target, $queryBuilder->createNamedParameter(''))),
            $expr->isNotNull($source),
            $expr->neq($source, $queryBuilder->createNamedParameter('')),
        ];
    }

    /**
     * @return list<array<string, mixed>> Nicht geloeschte Oeffnungszeiten, deren UID im Ziel noch fehlt
     */
    private function getOpeningHoursToCopy(): array
    {
        if (!$this->tableExists(self::OPENING_HOURS_SOURCE) || !$this->tableExists(self::OPENING_HOURS_TARGET)) {
            return [];
        }

        $targetQueryBuilder = $this->connectionPool->getQueryBuilderForTable(self::OPENING_HOURS_TARGET);
        $targetQueryBuilder->getRestrictions()->removeAll();
        $existing = array_map('intval', $targetQueryBuilder->select('uid')->from(self::OPENING_HOURS_TARGET)->executeQuery()->fetchFirstColumn());

        $queryBuilder = $this->connectionPool->getQueryBuilderForTable(self::OPENING_HOURS_SOURCE);
        $queryBuilder->getRestrictions()->removeAll();
        $rows = $queryBuilder
            ->select('*')
            ->from(self::OPENING_HOURS_SOURCE)
            ->where($queryBuilder->expr()->eq('deleted', 0))
            ->orderBy('uid')
            ->executeQuery()
            ->fetchAllAssociative();

        return array_values(array_filter(
            $rows,
            static fn(array $row): bool => in_array((int)$row['uid'], $existing, true) === false
        ));
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function getPlugins(): array
    {
        // Ohne ps14_address (erkennbar an dessen Tabelle) gaebe es fuer den neuen CType keine Ausgabe
        if (!$this->columnExists('tt_content', 'list_type') || !$this->tableExists(self::OPENING_HOURS_TARGET)) {
            return [];
        }

        $queryBuilder = $this->connectionPool->getQueryBuilderForTable('tt_content');
        $queryBuilder->getRestrictions()->removeAll();

        return $queryBuilder
            ->select('uid', 'tx_foundation_variant', 'pi_flexform')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq('CType', $queryBuilder->createNamedParameter('list')),
                $queryBuilder->expr()->eq('list_type', $queryBuilder->createNamedParameter(self::PLUGIN)),
            )
            ->executeQuery()
            ->fetchAllAssociative();
    }
}
