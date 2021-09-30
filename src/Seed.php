<?php
/*
 * This file is part of Aplus Framework CLI Commands Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Framework\CLI\Commands;

use App\Seeds\Seeder;
use Framework\CLI\Command;

class Seed extends Command
{
    protected string $name = 'seed';
    protected string $description = 'Seed database.';
    protected string $usage = 'seed';

    public function run() : void
    {
        (new Seeder(\App::database()))->run();
    }
}
