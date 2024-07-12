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
final class MigrateDownTest extends MigrationTestCase
{
    public function testRunDown() : void
    {
        $this->migrateTo('400');
        $this->app->runCli('migratedown');
        self::assertStdoutContains('300_create_table_comments');
        self::assertStdoutContains('200_create_table_posts');
        self::assertStdoutContains('100_create_table_users');
    }

    public function testRunQuantity() : void
    {
        $this->migrateTo('400');
        $this->app->runCli('migratedown 2');
        self::assertStdoutContains('300_create_table_comments');
        self::assertStdoutContains('200_create_table_posts');
        self::assertStdoutNotContains('100_create_table_users');
    }

    public function testRunNone() : void
    {
        $this->migrateTo('100');
        $this->app->runCli('migratedown 1');
        self::assertStdoutContains('Did not run any migration.');
    }
}
