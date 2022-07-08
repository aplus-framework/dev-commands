<?php declare(strict_types=1);
/*
 * This file is part of Aplus Framework Dev Commands Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Tests\CLI\Commands\Seeds;

use Framework\Database\Extra\Seeder;

class UsersSeeder extends Seeder
{
    public function run() : void
    {
        \usleep(500);
    }
}
