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

/**
 * @runTestsInSeparateProcesses
 */
final class QueryTest extends DatabaseTestCase
{
    public function testRun() : void
    {
        $this->app->runCli('query "SELECT 1"');
        self::assertStdoutContains('Query');
        self::assertStdoutContains('SELECT 1');
        self::assertStdoutContains('Total');
    }

    public function testNoResults() : void
    {
        $this->createTables();
        $this->app->runCli('query "SELECT * FROM Users"');
        self::assertStdoutContains('Query');
        self::assertStdoutContains('SELECT * FROM Users');
        self::assertStdoutContains('No results');
    }
}
