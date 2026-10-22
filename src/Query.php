<?php declare(strict_types=1);
/*
 * This file is part of Aplus Framework Dev Commands Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Aplus\Cli\Commands;

use Aplus\Cli\Cli;

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
            $query = Cli::prompt('Query');
        }
        Cli::write(
            Cli::style('Query: ', 'white') . Cli::style($query, 'yellow')
        );
        try {
            $result = $this->getDatabase()->query($query);
        } catch (\Exception $exception) {
            Cli::beep();
            Cli::error($exception->getMessage());
            return;
        }
        $result = $result->fetchArrayAll();
        if (empty($result)) {
            Cli::write('No results.');
            return;
        }
        Cli::table($result, \array_keys($result[0]));
        Cli::write('Total: ' . \count($result));
    }
}
