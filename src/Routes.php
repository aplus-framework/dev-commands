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

/**
 * Class Routes.
 *
 * @package dev-commands
 */
class Routes extends Command
{
    protected string $name = 'routes';
    protected string $description = 'Shows routes list.';
    protected string $usage = 'routes [options]';
    protected string $routerInstance = 'default';

    public function run() : void
    {
        $instance = $this->console->getArgument(0);
        if ($instance !== null) {
            $this->routerInstance = $instance;
        }
        CLI::write(
            CLI::style('Router Instance:', CLI::FG_YELLOW, formats: [CLI::FM_BOLD])
            . ' ' . $this->routerInstance
        );
        CLI::newLine();
        $data = $this->collectData();
        $count = \count($data);
        $this->showCollectionsSet($count);
        foreach ($data as $index => $collection) {
            CLI::write(CLI::style('Route Collection ' . ($index + 1), CLI::FG_YELLOW, formats: [CLI::FM_BOLD]));
            $this->writeHeader('Origin', $collection['origin']);
            if (isset($collection['name'])) {
                $this->writeHeader('Name', $collection['name']);
            }
            $this->writeHeader('Routes Count', (string) $collection['count']);
            if (isset($collection['notFound'])) {
                $this->writeHeader('Route Not Found', $collection['notFound']);
            }
            if ($collection['routes']) {
                CLI::table(
                    $collection['routes'],
                    ['#', 'Method', 'Path', 'Action', 'Name', 'Has Options']
                );
            }
            if ($index + 1 < $count) {
                CLI::newLine();
            }
        }
    }

    protected function showCollectionsSet(int $count) : void
    {
        if ($count === 0) {
            CLI::write('No Route Collection has been set.', CLI::FG_RED);
            return;
        }
        $plural = $count > 1;
        CLI::write(
            'There ' . ($plural ? 'are' : 'is') . ' ' . $count
            . ' Route Collection' . ($plural ? 's' : '') . ' set:',
            CLI::FG_GREEN
        );
        CLI::newLine();
    }

    protected function writeHeader(string $field, string $value) : void
    {
        CLI::write(CLI::style($field . ':', formats: [CLI::FM_BOLD]) . ' ' . $value);
    }

    /**
     * @return array<int,mixed>
     */
    protected function collectData() : array
    {
        $data = [];
        foreach (App::router($this->routerInstance)->getCollections() as $index => $collection) {
            $data[$index]['origin'] = $collection->origin;
            $data[$index]['name'] = $collection->name;
            $data[$index]['count'] = $collection->count();
            $notFound = null;
            if (isset($collection->notFoundAction)) {
                $notFound = \is_string($collection->notFoundAction) // @phpstan-ignore-line
                    ? $collection->notFoundAction // @phpstan-ignore-line
                    : 'Closure';
            }
            $data[$index]['notFound'] = $notFound;
            $data[$index]['routes'] = [];
            foreach ($collection->routes as $method => $routes) {
                foreach ($routes as $route) {
                    $action = $route->getAction();
                    $action = \is_string($action) ? $action : 'Closure';
                    $data[$index]['routes'][] = [
                        'method' => $method,
                        'path' => $route->getPath(),
                        'action' => $action,
                        'name' => $route->getName(),
                        'hasOptions' => $route->getOptions() ? 'Yes' : 'No',
                    ];
                }
                \usort($data[$index]['routes'], static function ($route1, $route2) {
                    return \strcmp($route1['method'], $route2['method']);
                });
            }
            $count = 0;
            foreach ($data[$index]['routes'] as &$route) {
                $route = \array_reverse($route);
                $route['#'] = ++$count;
                $route = \array_reverse($route);
            }
            unset($route);
        }
        return $data;
    }
}
