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
final class ListSchemasTest extends DatabaseTestCase
{
    public function testRun() : void
    {
        $this->app->runCli('listschemas');
        self::assertStdoutContains('Schema');
        self::assertStdoutContains('Collation');
        self::assertStdoutContains('Tables');
        self::assertStdoutContains('Total');
    }
}
