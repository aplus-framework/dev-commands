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
 * Class MigrationVersion.
 *
 * @package dev-commands
 */
class MigrationVersion extends Command
{
    protected string $description = 'Shows last migration version name.';
    protected string $migratorInstance = 'default';

    public function run() : void
    {
        // @phpstan-ignore-next-line
        $this->migratorInstance = $this->getConsole()->getOption('instance') ?? 'default';
        // @phpstan-ignore-next-line
        $name = App::migrator($this->migratorInstance)->getLastMigrationName();
        if ($name !== null) {
            CLI::write($name);
            return;
        }
        CLI::write('No migration.');
    }
}
