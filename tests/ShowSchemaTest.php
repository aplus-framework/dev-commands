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
final class ShowSchemaTest extends DatabaseTestCase
{
    public function testRun() : void
    {
        $this->dropTables();
        $this->app->runCli('showschema framework-tests');
        self::assertStdoutContains('No tables');
    }

    public function testRunWithTables() : void
    {
        $this->createTables();
        $this->app->runCli('showschema framework-tests');
        self::assertStdoutContains('Schema:');
        self::assertStdoutContains('Users');
        self::assertStdoutContains('Table');
        self::assertStdoutContains('Engine');
        self::assertStdoutContains('Total');
    }
}
