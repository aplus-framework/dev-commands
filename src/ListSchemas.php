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
use Framework\Debug\Debugger;

/**
 * Class ListSchemas.
 *
 * @package dev-commands
 */
class ListSchemas extends DatabaseCommand
{
    protected string $description = 'Lists database schemas.';

    public function run() : void
    {
        $this->setDatabase();
        $sql = 'SELECT `SCHEMA_NAME` AS `schema`,
`DEFAULT_COLLATION_NAME` AS `collation`
FROM `information_schema`.`SCHEMATA`
ORDER BY `SCHEMA_NAME`';
        $schemas = $this->getDatabase()->query($sql)->fetchArrayAll();
        $sql = 'SELECT `TABLE_SCHEMA` AS `schema`,
SUM(`DATA_LENGTH` + `INDEX_LENGTH`) AS `size`,
COUNT(DISTINCT CONCAT(`TABLE_SCHEMA`, ".", `TABLE_NAME`)) AS `tables`
FROM `information_schema`.`TABLES`
GROUP BY `TABLE_SCHEMA`';
        $infos = $this->getDatabase()->query($sql)->fetchArrayAll();
        foreach ($schemas as &$schema) {
            $schema['size'] = $schema['tables'] = 0;
            foreach ($infos as $info) {
                if ($info['schema'] === $schema['schema']) {
                    $schema['tables'] = $info['tables'];
                    $schema['size'] = Debugger::convertSize((int) $info['size']);
                    break;
                }
            }
        }
        unset($schema);
        CLI::table($schemas, [
            'Schema',
            'Collation',
            'Tables',
            'Size',
        ]);
        CLI::write('Total: ' . \count($schemas));
    }
}
