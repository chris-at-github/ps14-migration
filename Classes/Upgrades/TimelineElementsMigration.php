<?php

declare(strict_types=1);

namespace Ps14\Migration\Upgrades;

use TYPO3\CMS\Core\Attribute\UpgradeWizard;

/**
 * Timeline (ps14_timeline): Elements -> tx_modules_timeline_item (Content Block ps14/timeline aus ps14_modules)
 */
#[UpgradeWizard('ps14Migration_timelineElementsMigration')]
final class TimelineElementsMigration extends AbstractElementsMigration
{
    #[\Override]
    public function getTitle(): string
    {
        return 'ps14: Migrate timeline elements to "tx_modules_timeline_item"';
    }

    #[\Override]
    protected function getContentType(): string
    {
        return 'ps14_timeline';
    }

    #[\Override]
    protected function getTargetTable(): string
    {
        return 'tx_modules_timeline_item';
    }

    #[\Override]
    protected function getCollectionField(): string
    {
        return 'ps14_timeline_items';
    }

    #[\Override]
    protected function getFieldMap(string $recordType): array
    {
        return [
            'date' => 'tx_timeline_date',
            'title' => 'title',
            'description' => 'description',
            'media' => 'media',
        ];
    }

    #[\Override]
    protected function getFileFields(): array
    {
        return ['media' => 'media'];
    }
}
