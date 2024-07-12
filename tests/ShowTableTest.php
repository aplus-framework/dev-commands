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

use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

#[RunTestsInSeparateProcesses]
final class ShowTableTest extends DatabaseTestCase
{
    public function testRun() : void
    {
        $table = \getenv('DB_SCHEMA') . '.Users';
        $this->createTables();
        $this->app->runCli('showtable ' . $table);
        self::assertStdoutContains('Column');
        self::assertStdoutContains('Type');
        self::assertStdoutContains('Indexes');
    }

    public function testKeys() : void
    {
        $this->createTables();
        $this->app->runCli('showtable Posts');
        self::assertStdoutContains('PRIMARY');
        self::assertStdoutContains('FULLTEXT');
        self::assertStdoutContains('INDEX');
    }
}
