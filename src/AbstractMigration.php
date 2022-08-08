<?php declare(strict_types=1);
/*
 * This file is part of Aplus Framework Dev Commands Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Framework\CLI\Commands;

use Framework\CLI\CLI;
use Framework\CLI\Command;
use Framework\MVC\App;

/**
 * Class AbstractMigration.
 *
 * @package dev-commands
 */
abstract class AbstractMigration extends Command
{
    protected string $migratorInstance = 'default';

    protected function runMigration(string $direction, int | string $arg = null) : void
    {
        // @phpstan-ignore-next-line
        $this->migratorInstance = $this->getConsole()->getOption('instance') ?? 'default';
        $direction = \ucfirst(\strtolower($direction));
        $arg ??= $this->getConsole()->getArgument(0);
        if ($direction !== 'To') {
            $arg = (int) $arg;
        }
        $method = 'migrate' . $direction;
        CLI::write(
            CLI::style('Migrator Instance:', CLI::FG_YELLOW, formats: [CLI::FM_BOLD])
            . ' ' . $this->migratorInstance
        );
        CLI::newLine();
        $count = 0;
        $time = $start = \microtime(true);
        // @phpstan-ignore-next-line
        foreach (App::migrator($this->migratorInstance)->{$method}($arg) as $item) {
            CLI::write('- Migrated to ' . CLI::style($item, CLI::FG_GREEN)
                . ' in ' . CLI::style((string) \round(\microtime(true) - $time, 6), CLI::FG_YELLOW) . ' seconds.');
            $time = \microtime(true);
            $count++;
        }
        if ($count) {
            CLI::newLine();
            CLI::write('Ran ' . $count . ' migration' . ($count !== 1 ? 's' : '')
                . ' in ' . \round(\microtime(true) - $start, 6) . ' seconds.');
            return;
        }
        CLI::write('Did not run any migration.');
    }
}
