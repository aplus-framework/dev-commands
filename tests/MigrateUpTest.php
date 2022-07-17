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
final class MigrateUpTest extends MigrationTestCase
{
    public function testRunUp() : void
    {
        $this->migrateTo('0');
        $this->app->runCli('migrateup');
        self::assertStdoutContains('100_create_table_users');
        self::assertStdoutContains('200_create_table_posts');
        self::assertStdoutContains('300_create_table_comments');
    }

    public function testRunQuantity() : void
    {
        $this->migrateTo('200');
        $this->app->runCli('migrateup 2');
        self::assertStdoutNotContains('100_create_table_users');
        self::assertStdoutContains('200_create_table_posts');
        self::assertStdoutContains('300_create_table_comments');
    }

    public function testRunNone() : void
    {
        $this->migrateTo('400');
        $this->app->runCli('migrateup 1');
        self::assertStdoutContains('Did not run any migration.');
    }
}
