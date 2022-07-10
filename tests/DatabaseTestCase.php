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
use Framework\Database\Database;
use Framework\Database\Definition\Table\TableDefinition;
use Framework\Testing\TestCase;

abstract class DatabaseTestCase extends TestCase
{
    protected static Database $database;

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

    protected function getDatabase() : Database
    {
        return static::$database ??= new Database([
            'host' => \getenv('DB_HOST'),
            'port' => \getenv('DB_PORT'),
            'username' => \getenv('DB_USERNAME'),
            'password' => \getenv('DB_PASSWORD'),
            'schema' => \getenv('DB_SCHEMA'),
        ]);
    }

    protected function createTables() : void
    {
        $this->dropTables();
        $database = $this->getDatabase();
        $database->createTable('Users')->definition(static function (TableDefinition $def) : void {
            $def->column('id')->int()->autoIncrement()->primaryKey();
            $def->column('email')->varchar(255)->uniqueKey();
            $def->column('name')->varchar(64);
            $def->index('name')->key('name');
        })->run();
        $database->createTable('Posts')->definition(static function (TableDefinition $def) : void {
            $def->column('id')->int()->autoIncrement()->primaryKey();
            $def->column('userId')->int()->null();
            $def->column('title')->varchar(255)->uniqueKey();
            $def->column('contents')->text();
            $def->index()->foreignKey('userId')
                ->references('Users', 'id')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $def->index()->fulltextKey('title', 'contents');
        })->run();
    }

    protected function dropTables() : void
    {
        $database = $this->getDatabase();
        $database->dropTable('Posts')->ifExists()->run();
        $database->dropTable('Users')->ifExists()->run();
    }
}
