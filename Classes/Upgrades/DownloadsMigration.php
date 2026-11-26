<?php

declare(strict_types=1);

namespace Ps14\Migration\Upgrades;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\ParameterType;
use TYPO3\CMS\Core\Attribute\UpgradeWizard;

/**
 * Downloads (ps14_downloads 12.x): CType "ps14_downloads" -> Core "uploads" (ps14_downloads v14)
 *
 * Die Dateien wandern von "tx_foundation_file" nach "media". Beschreibung und Dateigroesse werden eingeschaltet, weil
 * das alte Element beides immer ausgegeben hat.
 */
#[UpgradeWizard('ps14Migration_downloadsMigration')]
final class DownloadsMigration extends AbstractMigration
{
    private const TABLE = 'tt_content';

    private const SOURCE_TYPE = 'ps14_downloads';

    #[\Override]
    public function getTitle(): string
    {
        return 'ps14: Migrate "ps14_downloads" to the core element "uploads"';
    }

    #[\Override]
    public function getDescription(): string
    {
        return 'Changes the CType "ps14_downloads" to "uploads" and moves the files from "tx_foundation_file" to "media".';
    }

    #[\Override]
    public function updateNecessary(): bool
    {
        return $this->getRecordUids() !== [];
    }

    #[\Override]
    public function executeUpdate(): bool
    {
        $uids = $this->getRecordUids();

        $referenceQueryBuilder = $this->connectionPool->getQueryBuilderForTable('sys_file_reference');
        $referenceQueryBuilder->getRestrictions()->removeAll();
        $referenceQueryBuilder
            ->update('sys_file_reference')
            ->set('fieldname', 'media')
            ->where(
                $referenceQueryBuilder->expr()->eq('tablenames', $referenceQueryBuilder->createNamedParameter(self::TABLE)),
                $referenceQueryBuilder->expr()->eq('fieldname', $referenceQueryBuilder->createNamedParameter('tx_foundation_file')),
                $referenceQueryBuilder->expr()->in('uid_foreign', $referenceQueryBuilder->createNamedParameter($uids, ArrayParameterType::INTEGER)),
            )
            ->executeStatement();

        $connection = $this->connectionPool->getConnectionForTable(self::TABLE);

        foreach ($uids as $uid) {
            $connection->update(self::TABLE, [
                'CType' => 'uploads',
                'media' => $this->countReferences($uid),
                'tx_foundation_file' => 0,
                'uploads_description' => 1,
                'filelink_size' => 1,
            ], ['uid' => $uid]);
        }

        $this->output?->writeln(sprintf('%d elements migrated to "uploads".', count($uids)));

        return true;
    }

    /**
     * @return list<int>
     */
    private function getRecordUids(): array
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable(self::TABLE);
        $queryBuilder->getRestrictions()->removeAll();

        return array_map('intval', $queryBuilder
            ->select('uid')
            ->from(self::TABLE)
            ->where(
                $queryBuilder->expr()->eq('CType', $queryBuilder->createNamedParameter(self::SOURCE_TYPE)),
            )
            ->executeQuery()
            ->fetchFirstColumn());
    }

    private function countReferences(int $uid): int
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable('sys_file_reference');
        $queryBuilder->getRestrictions()->removeAll();

        return (int)$queryBuilder
            ->count('uid')
            ->from('sys_file_reference')
            ->where(
                $queryBuilder->expr()->eq('tablenames', $queryBuilder->createNamedParameter(self::TABLE)),
                $queryBuilder->expr()->eq('fieldname', $queryBuilder->createNamedParameter('media')),
                $queryBuilder->expr()->eq('uid_foreign', $queryBuilder->createNamedParameter($uid, ParameterType::INTEGER)),
                $queryBuilder->expr()->eq('deleted', 0),
            )
            ->executeQuery()
            ->fetchOne();
    }
}
