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
 * @package cli-commands
 */
class MigrationVersion extends Command
{
    protected string $description = 'Shows last migration version name.';

    public function run() : void
    {
        $name = App::migrator()->getLastMigrationName();
        if ($name) {
            CLI::write($name);
            return;
        }
        CLI::write('No migration.');
    }
}
