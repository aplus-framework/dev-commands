<?php
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
final class SeedTest extends DatabaseTestCase
{
    public function testRun() : void
    {
        $this->app->runCli('seed Tests\CLI\Commands\Seeds\AppSeeder');
        self::assertStdoutContains('Database Instance:');
        self::assertStdoutContains('- Seeding');
        self::assertStdoutContains('Total time of');
    }
}
