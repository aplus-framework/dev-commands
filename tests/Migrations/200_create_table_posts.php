<?php
/*
 * This file is part of Aplus Framework Dev Commands Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
use Framework\Database\Definition\Table\TableDefinition;
use Framework\Database\Extra\Migration;

return new class() extends Migration {
    protected string $table = 'Posts';

    public function up() : void
    {
        $this->getDatabase()->createTable($this->table)
            ->definition(static function (TableDefinition $def) : void {
                $def->column('id')->int(11)->primaryKey()->autoIncrement();
                $def->column('userId')->int(11);
                $def->column('title')->varchar(255);
                $def->index()
                    ->foreignKey('userId')
                    ->references('Users', 'id')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            })
            ->run();
    }

    public function down() : void
    {
        $this->getDatabase()->dropTable($this->table)->ifExists()->run();
    }
};
