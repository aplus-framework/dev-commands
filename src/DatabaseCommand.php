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

use Framework\CLI\Command;
use Framework\Database\Database;

/**
 * Class DatabaseCommand.
 *
 * @package database
 */
abstract class DatabaseCommand extends Command
{
    protected Database $database;

    public function setDatabase(Database $database) : static
    {
        $this->database = $database;
        return $this;
    }

    public function getDatabase() : Database
    {
        return $this->database;
    }
}
