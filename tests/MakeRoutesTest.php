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

use Framework\Config\Config;
use Framework\Testing\TestCase;

/**
 * @runTestsInSeparateProcesses
 */
final class MakeRoutesTest extends TestCase
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
        ]);
        parent::prepareDefaults();
    }

    protected function getEmptyRoutingContents() : string
    {
        return <<<'EOL'
            <?php

            use Framework\MVC\App;
            use Framework\Routing\RouteCollection;

            App::router();
            EOL;
    }

    public function testRelativeFilepath() : void
    {
        $this->config->set('autoloader', [
            'namespaces' => [],
        ]);
        $filepath = 'routes.php';
        $absoluteFilepath = \getcwd() . '/routes.php';
        if (\is_file($absoluteFilepath)) {
            \unlink($absoluteFilepath);
        }
        $this->app->runCli('makeroutes ' . $filepath);
        self::assertFileExists($absoluteFilepath);
        self::assertStringContainsString(
            $this->getEmptyRoutingContents(),
            (string) \file_get_contents($absoluteFilepath)
        );
        \unlink($absoluteFilepath);
    }

    public function testAbsoluteFilepath() : void
    {
        $this->config->set('autoloader', [
            'namespaces' => [],
        ]);
        $filepath = \sys_get_temp_dir() . '/routes.php';
        $filepath = \strtr($filepath, [' ' => '\ ']);
        if (\is_file($filepath)) {
            \unlink($filepath);
        }
        $this->app->runCli('makeroutes ' . $filepath);
        self::assertFileExists($filepath);
        self::assertStringContainsString(
            $this->getEmptyRoutingContents(),
            (string) \file_get_contents($filepath)
        );
        \unlink($filepath);
    }

    public function testNoRoutes() : void
    {
        $this->config->set('autoloader', [
            'namespaces' => [],
        ]);
        $this->app->runCli('makeroutes');
        self::assertStdoutContains($this->getEmptyRoutingContents());
    }

    public function testNotFoundInAnyOrigin() : void
    {
        $this->config->set('autoloader', [
            'namespaces' => [
            ],
            'classes' => [
                'Tests\CLI\Commands\app\Other\Bar' => __DIR__ . '/app/Other/Bar.php',
            ],
        ]);
        $this->app->runCli('makeroutes');
        self::assertStdoutContains(
            <<<'EOL'
                <?php

                use Framework\MVC\App;
                use Framework\Routing\RouteCollection;

                App::router()->serve(null, static function (RouteCollection $routes) : void {
                    $routes->notFound('Tests\CLI\Commands\app\Other\Bar::notFound');
                });
                EOL
        );
    }

    public function testOneOriginWithManyRoutes() : void
    {
        $this->config->set('autoloader', [
            'namespaces' => [
            ],
            'classes' => [
                'Tests\CLI\Commands\app\Other\Foo' => __DIR__ . '/app/Other/Foo.php',
            ],
        ]);
        $this->app->runCli('makeroutes');
        self::assertStdoutContains(
            <<<'EOL'
                <?php

                use Framework\MVC\App;
                use Framework\Routing\RouteCollection;

                App::router()->serve(null, static function (RouteCollection $routes) : void {
                    $routes->get('/foo', 'Tests\CLI\Commands\app\Other\Foo::index/*');
                    $routes->post('/foo', 'Tests\CLI\Commands\app\Other\Foo::create/*', 'foo.create');
                    $routes->notFound('Tests\CLI\Commands\app\Other\Foo::notFound');
                });
                EOL
        );
    }

    public function testManyOriginsWithManyRoutes() : void
    {
        $this->config->set('autoloader', [
            'namespaces' => [
                'Tests\CLI\Commands\app\Controllers' => __DIR__ . '/app/Controllers',
            ],
            'classes' => [
                'Tests\CLI\Commands\app\Other\Foo' => __DIR__ . '/app/Other/Foo.php',
            ],
        ]);
        $this->app->runCli('makeroutes');
        self::assertStdoutContains(
            <<<'EOL'
                <?php

                use Framework\MVC\App;
                use Framework\Routing\RouteCollection;

                App::router()->serve('https://blog.domain.tld', static function (RouteCollection $routes) : void {
                    $routes->notFound('Tests\CLI\Commands\app\Controllers\Errors::notFound');
                })->serve('https://domain.tld', static function (RouteCollection $routes) : void {
                    $routes->get('/', 'Tests\CLI\Commands\app\Controllers\Home::index/*', 'home');
                    $routes->get('/contact', 'Tests\CLI\Commands\app\Controllers\Home::contact/*');
                    $routes->post('/contact', 'Tests\CLI\Commands\app\Controllers\Home::contact/*');
                    $routes->notFound('Tests\CLI\Commands\app\Controllers\Errors::notFound');
                })->serve(null, static function (RouteCollection $routes) : void {
                    $routes->get('/foo', 'Tests\CLI\Commands\app\Other\Foo::index/*');
                    $routes->post('/foo', 'Tests\CLI\Commands\app\Other\Foo::create/*', 'foo.create');
                    $routes->notFound('Tests\CLI\Commands\app\Other\Foo::notFound');
                });
                EOL
        );
    }
}
