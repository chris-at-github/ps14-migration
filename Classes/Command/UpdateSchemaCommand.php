<?php

declare(strict_types=1);

namespace Ps14\Migration\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Database\Schema\SchemaMigrator;
use TYPO3\CMS\Core\Database\Schema\SqlReader;

/**
 * Updates the database schema like "extension:setup" (add, change, create_table, change_table), but without
 * logging in the backend user "_cli_" first. On an old database (e.g. TYPO3 v12) the login fails because it
 * writes to columns that do not exist yet (be_users.user_settings, TYPO3 14.2). Nothing is removed.
 *
 * Columns whose change would destroy data still needed later (e.g. a VARCHAR that would become INT) can be
 * excluded with --skip=<table>.<column> (repeatable).
 */
#[AsCommand(
    name: 'ps14:migration:update-schema',
    description: 'Adds and changes tables and columns of all active packages without backend login (no removal).',
)]
final class UpdateSchemaCommand extends Command
{
    private const ACTIONS = ['create_table', 'add', 'change', 'change_table'];

    public function __construct(
        private readonly SqlReader $sqlReader,
        private readonly SchemaMigrator $schemaMigrator,
    ) {
        parent::__construct();
    }

    #[\Override]
    protected function configure(): void
    {
        $this->addOption('dry-run', null, InputOption::VALUE_NONE, 'Only list the statements, do not execute them');
        $this->addOption('skip', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Do not change this column (<table>.<column>), repeatable', []);
    }

    #[\Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $statements = $this->sqlReader->getCreateTableStatementArray($this->sqlReader->getTablesDefinitionString());
        $suggestions = array_merge_recursive(...array_values($this->schemaMigrator->getUpdateSuggestions($statements)));
        $selected = [];
        $skip = array_map('strtolower', (array)$input->getOption('skip'));

        foreach (self::ACTIONS as $action) {
            $actionStatements = $suggestions[$action] ?? [];
            $output->writeln(sprintf('%s: %d', $action, count($actionStatements)));

            foreach ($actionStatements as $hash => $statement) {
                $column = $this->getChangedColumn((string)$statement);

                if ($action === 'change' && $column !== null && in_array($column, $skip, true)) {
                    $output->writeln('  <comment>skipped</comment> ' . $statement);
                    continue;
                }

                $selected[$hash] = true;

                if ($output->isVerbose() || $input->getOption('dry-run')) {
                    $output->writeln('  ' . $statement);
                }
            }
        }

        if ($input->getOption('dry-run') || $selected === []) {
            return Command::SUCCESS;
        }

        $errors = $this->schemaMigrator->migrate($statements, $selected);

        foreach ($errors as $hash => $error) {
            $output->writeln(sprintf('<error>%s</error>', $error));
        }

        $output->writeln(sprintf('Executed %d statements, %d errors.', count($selected), count($errors)));

        return $errors === [] ? Command::SUCCESS : Command::FAILURE;
    }

    /**
     * "ALTER TABLE `tt_content` CHANGE `header_layout` `header_layout` INT ..." -> "tt_content.header_layout"
     */
    private function getChangedColumn(string $statement): ?string
    {
        if (preg_match('/^ALTER TABLE\s+`?([a-z0-9_]+)`?\s+CHANGE\s+`?([a-z0-9_]+)`?/i', $statement, $matches) !== 1) {
            return null;
        }

        return strtolower($matches[1] . '.' . $matches[2]);
    }
}
