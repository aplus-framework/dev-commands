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

/**
 * Class Query.
 *
 * @package dev-commands
 */
class Query extends DatabaseCommand
{
    protected string $description = 'Runs an SQL query.';

    public function run() : void
    {
        $this->setDatabase();
        $query = $this->console->getArgument(0);
        if (empty($query)) {
            $query = CLI::prompt('Query');
        }
        CLI::write(
            CLI::style('Query: ', 'white') . CLI::style($query, 'yellow')
        );
        try {
            $result = $this->getDatabase()->query($query);
        } catch (\Exception $exception) {
            CLI::beep();
            CLI::error($exception->getMessage());
            return;
        }
        $result = $result->fetchArrayAll();
        if (empty($result)) {
            CLI::write('No results.');
            return;
        }
        CLI::table($result, \array_keys($result[0]));
        CLI::write('Total: ' . \count($result));
    }
}
