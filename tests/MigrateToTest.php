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
final class MigrateToTest extends MigrationTestCase
{
    public function testRunUp() : void
    {
        $this->migrateTo('0');
        $this->app->runCli('migrateto 200');
        self::assertStdoutContains('100_create_table_users');
        self::assertStdoutNotContains('200_create_table_posts');
    }

    public function testRunDown() : void
    {
        $this->migrateTo('400');
        $this->app->runCli('migrateto 200');
        self::assertStdoutContains('300_create_table_comments');
        self::assertStdoutContains('200_create_table_posts');
        self::assertStdoutNotContains('100_create_table_users');
    }

    public function testRunNone() : void
    {
        $this->migrateTo('200');
        $this->app->runCli('migrateto 200');
        self::assertStdoutContains('Did not run any migration.');
    }
}
