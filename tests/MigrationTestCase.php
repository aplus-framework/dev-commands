<?php declare(strict_types=1);
/*
 * This file is part of Aplus Framework Dev Commands Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Tests\CLI\Commands;

use Framework\Database\Extra\Migrator;

abstract class MigrationTestCase extends DatabaseTestCase
{
    protected function migrateTo(string $name) : void
    {
        $this->dropTables();
        $migrator = new Migrator($this->getDatabase(), [
            __DIR__ . '/Migrations',
        ]);
        foreach ($migrator->migrateTo($name) as $migration) {
        }
    }
}
