<?php

declare(strict_types=1);

namespace Ps14\Migration\Upgrades;

use TYPO3\CMS\Core\Attribute\UpgradeWizard;

/**
 * Tabs (ps14_tabs): Elements -> tx_modules_tabs_item (Content Block ps14/tabs aus ps14_modules)
 */
#[UpgradeWizard('ps14Migration_tabsElementsMigration')]
final class TabsElementsMigration extends AbstractElementsMigration
{
    #[\Override]
    public function getTitle(): string
    {
        return 'ps14: Migrate tabs elements to "tx_modules_tabs_item"';
    }

    #[\Override]
    protected function getContentType(): string
    {
        return 'ps14_tabs';
    }

    #[\Override]
    protected function getTargetTable(): string
    {
        return 'tx_modules_tabs_item';
    }

    #[\Override]
    protected function getCollectionField(): string
    {
        return 'ps14_tabs_items';
    }

    #[\Override]
    protected function getRecordTypes(): array
    {
        return [
            'ps14_tabs_default' => 'text',
            'ps14_tabs_records' => 'content',
        ];
    }

    #[\Override]
    protected function getFieldMap(string $recordType): array
    {
        $fields = [
            'title' => 'title',
            'active' => 'tx_tabs_active',
            'thumbnail' => 'thumbnail',
        ];

        if ($recordType === 'text') {
            $fields['description'] = 'description';
        }

        return $fields;
    }

    #[\Override]
    protected function getFileFields(): array
    {
        return ['thumbnail' => 'thumbnail'];
    }

    #[\Override]
    protected function getNestedContentRecordTypes(): array
    {
        return ['content'];
    }
}
