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
final class MigrationVersionTest extends MigrationTestCase
{
    public function testNoVersion() : void
    {
        $this->dropTables();
        $this->app->runCli('migrationversion');
        self::assertStdoutContains('No migration');
    }

    public function testWithVersion() : void
    {
        $this->dropTables();
        $this->migrateTo('201');
        $this->app->runCli('migrationversion');
        self::assertStdoutContains('200_create_table_posts');
    }
}
