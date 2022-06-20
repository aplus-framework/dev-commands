<?php declare(strict_types=1);
/*
 * This file is part of Aplus Framework CLI Commands Library.
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
use ReflectionMethod;

class Seed extends Command
{
    protected string $name = 'seed';
    protected string $description = 'Seeds database.';
    protected string $usage = 'seed [classname]';
    protected string $databaseInstance = 'default';

    public function run() : void
    {
        CLI::write(
            CLI::style('Database Instance:', CLI::FG_YELLOW, formats: [CLI::FM_BOLD])
            . ' ' . $this->databaseInstance
        );
        CLI::newLine();
        $start = \microtime(true);
        $this->runSeeder();
        $end = \microtime(true);
        CLI::newLine();
        CLI::write('Total time of ' . \round($end - $start, 6) . ' seconds.');
    }

    protected function runSeeder() : void
    {
        $class = $this->getConsole()->getArgument(0);
        if (empty($class)) {
            CLI::error('First argument must be a class name.');
        }
        $class = new $class(App::database($this->databaseInstance));
        $method = new ReflectionMethod($class, 'runSeed');
        $method->setAccessible(true);
        $method->invoke($class, $class);
    }
}
