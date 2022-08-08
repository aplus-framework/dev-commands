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

use Framework\CLI\Command;
use Framework\Database\Database;
use Framework\MVC\App;

/**
 * Class DatabaseCommand.
 *
 * @package dev-commands
 */
abstract class DatabaseCommand extends Command
{
    protected Database $database;
    protected string $databaseInstance = 'default';
    protected array $options = [
        '--instance' => 'Database instance name.',
    ];

    public function setDatabase() : static
    {
        // @phpstan-ignore-next-line
        $this->databaseInstance = $this->getConsole()->getOption('instance') ?? 'default';
        $this->database = App::database($this->databaseInstance); // @phpstan-ignore-line
        return $this;
    }

    public function getDatabase() : Database
    {
        return $this->database;
    }
}
