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
 * Class MigrateTo.
 *
 * @package dev-commands
 */
class MigrateTo extends AbstractMigration
{
    protected string $description = 'Runs migrations to a version.';

    public function run() : void
    {
        $version = $this->getConsole()->getArgument(0);
        if ($version === null) {
            $version = CLI::prompt('Version');
        }
        $this->runMigration('to', $version);
    }
}
