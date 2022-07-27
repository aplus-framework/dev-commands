<?php declare(strict_types=1);
/*
 * This file is part of Aplus Framework Dev Commands Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Framework\CLI\Commands;

use Framework\CLI\CLI;
use Framework\CLI\Command;
use Framework\MVC\App;
use Framework\Routing\Reflector;
use Framework\Routing\RouteActions;

/**
 * Class MakeRoutes.
 *
 * @package dev-commands
 */
class MakeRoutes extends Command
{
    protected string $name = 'makeroutes';
    protected string $description = 'Make routes file.';
    protected string $usage = 'makeroutes [options]';

    public function run() : void
    {
        $contents = "<?php\n";
        $contents .= "\n";
        $contents .= "use Framework\\MVC\\App;\n";
        $contents .= "use Framework\\Routing\\RouteCollection;\n";
        $contents .= "\n";
        $collections = $this->makeCollections();
        $contents .= "App::router(){$collections}";
        //file_put_contents(__DIR__ . '/routes.php', $contents);
        CLI::write($contents);
    }

    protected function makeCollections() : string
    {
        $contents = '';
        foreach ($this->getOrigins() as $origin => $routes) {
            if ($origin !== 'null') {
                $origin = "'{$origin}'";
            }
            $contents .= "->serve({$origin}, static function (RouteCollection \$routes) : void {\n";
            foreach ($routes as $route) {
                foreach ($route['methods'] as $method) {
                    $method = \strtolower($method);
                    $arguments = '';
                    if ($route['arguments'] !== '') {
                        $arguments = "/{$route['arguments']}";
                    }
                    $name = '';
                    if ($route['name'] !== null) {
                        $name = ", '{$route['name']}'";
                    }
                    $contents .= "    \$routes->{$method}('{$route['path']}', '{$route['action']}{$arguments}'{$name});\n";
                }
            }
            $contents .= '})';
        }
        $contents .= ";\n";
        return $contents;
    }

    /**
     * @return array<int,string>
     */
    protected function getClasses() : array
    {
        //App::autoloader()->setNamespace('App', __DIR__ . '/../raw-tests/app');
        $autoloader = App::autoloader();
        $locator = App::locator();
        $files = [];
        foreach ($autoloader->getNamespaces() as $namespaces) {
            foreach ($namespaces as $directory) {
                $files = [...$files, ...$locator->listFiles($directory)];
            }
        }
        foreach ($autoloader->getClasses() as $file) {
            $files[] = $file;
        }
        $files = \array_unique($files);
        \sort($files);
        $actions = [];
        foreach ($files as $file) {
            $className = $locator->getClassName($file);
            if ($className === null) {
                continue;
            }
            if (\is_subclass_of($className, RouteActions::class)) {
                $actions[] = $className;
            }
        }
        return $actions;
    }

    /**
     * @return array<int,array<mixed>>
     */
    protected function getRoutes() : array
    {
        $classes = $this->getClasses();
        $routes = [];
        foreach ($classes as $class) {
            $reflector = new Reflector($class); // @phpstan-ignore-line
            $routes = [...$routes, ...$reflector->getRoutes()];
        }
        return $routes;
    }

    /**
     * @return array<array<mixed>>
     */
    protected function getOrigins() : array
    {
        $origins = [];
        foreach ($this->getRoutes() as $route) {
            if (empty($route['origins'])) {
                $origins['null'][] = $route;
                continue;
            }
            foreach ($route['origins'] as $origin) {
                $origins[$origin][] = $route;
            }
        }
        return $origins;
    }
}
