<?php declare(strict_types=1);
/*
 * This file is part of Aplus Framework Dev Commands Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Tests\CLI\Commands;

use Framework\Config\Config;
use Framework\Testing\TestCase;

abstract class DatabaseTestCase extends TestCase
{
    protected function prepareDefaults() : void
    {
        $this->config = new Config([
            'console' => [
                'default' => [
                    'directories' => [
                        __DIR__ . '/../src',
                    ],
                ],
            ],
            'database' => [
                'default' => [
                    'config' => [
                        'host' => \getenv('DB_HOST'),
                        'port' => \getenv('DB_PORT'),
                        'username' => \getenv('DB_USERNAME'),
                        'password' => \getenv('DB_PASSWORD'),
                        'schema' => \getenv('DB_SCHEMA'),
                    ],
                ],
            ],
        ]);
        parent::prepareDefaults();
    }
}
