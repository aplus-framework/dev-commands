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

use Framework\Config\Config;
use Framework\Testing\TestCase;

/**
 * @runTestsInSeparateProcesses
 */
final class RoutesTest extends TestCase
{
    protected function prepareDefaults() : void
    {
        $this->config = new Config([
            'console' => [
                'default' => [
                    'directories' => [
                        __DIR__ . '/../src',
                    ],
                ],
            ],
            'router' => [
                'default' => [
                    'files' => [
                        __DIR__ . '/routes.php',
                    ],
                ],
            ],
        ]);
        parent::prepareDefaults();
    }

    public function testRun() : void
    {
        $this->app->runCli('routes');
        self::assertStdoutContains('Method');
        self::assertStdoutContains('Origin');
    }

    public function testRunWithInstance() : void
    {
        $this->app->runCli('routes default');
        self::assertStdoutContains('Method');
        self::assertStdoutContains('Origin');
    }

    public function testRunWithNoRouteCollection() : void
    {
        $this->config->set('router', []);
        $this->app->runCli('routes');
        self::assertStdoutContains('No Route Collection has been set.');
    }
}
