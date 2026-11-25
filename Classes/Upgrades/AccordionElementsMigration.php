<?php

declare(strict_types=1);

namespace Ps14\Migration\Upgrades;

use TYPO3\CMS\Core\Attribute\UpgradeWizard;

/**
 * Akkordeon (ps14_accordion): Elements -> tx_modules_accordion_item (Content Block ps14/accordion aus ps14_modules)
 */
#[UpgradeWizard('ps14Migration_accordionElementsMigration')]
final class AccordionElementsMigration extends AbstractElementsMigration
{
    #[\Override]
    public function getTitle(): string
    {
        return 'ps14: Migrate accordion elements to "tx_modules_accordion_item"';
    }

    #[\Override]
    protected function getContentType(): string
    {
        return 'ps14_accordion';
    }

    #[\Override]
    protected function getTargetTable(): string
    {
        return 'tx_modules_accordion_item';
    }

    #[\Override]
    protected function getCollectionField(): string
    {
        return 'ps14_accordion_items';
    }

    #[\Override]
    protected function getRecordTypes(): array
    {
        return [
            'ps14_accordion_default' => 'text',
            'ps14_accordion_records' => 'content',
        ];
    }

    #[\Override]
    protected function getFieldMap(string $recordType): array
    {
        $fields = [
            'title' => 'title',
            'link' => 'link',
            'active' => 'tx_accordion_active',
        ];

        if ($recordType === 'text') {
            $fields['description'] = 'description';
        }

        return $fields;
    }

    #[\Override]
    protected function getNestedContentRecordTypes(): array
    {
        return ['content'];
    }
}
