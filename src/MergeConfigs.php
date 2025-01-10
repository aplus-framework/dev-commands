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
use Framework\CLI\Command;
use Framework\Helpers\Isolation;
use Framework\MVC\App;
use RuntimeException;

/**
 * Class MergeConfigs.
 *
 * @package dev-commands
 */
class MergeConfigs extends Command
{
    protected string $description = 'Merge configuration files.';
    protected string $group = 'Configs';
    protected array $options = [
        '--extension' => 'Set a custom file extension. Default is ".php"',
        '-i' => 'Ignore files that do not return an array in the correct format.',
    ];
    protected string $usage = 'mergeconfigs [options] -- [directory]';

    protected function getDirectory() : string
    {
        $dir = $this->console->getArgument(0);
        if ($dir) {
            return $this->validateDir($dir);
        }
        $options = [];
        foreach (['config', 'configs'] as $option) {
            $dir = \getcwd() . '/' . $option;
            if (\is_dir($dir)) {
                $options[] = $option;
            }
        }
        $dir = \defined('IS_IN_PHPUNIT') && IS_IN_PHPUNIT
            ? __DIR__ . '/../tests/configs/ok'
            : CLI::prompt('Config directory', $options);
        return $this->validateDir($dir);
    }

    protected function validateDir(string $dir) : string
    {
        if (!\is_dir($dir)) {
            throw new RuntimeException('Config directory "' . $dir . '" does not exist');
        }
        return $dir;
    }

    protected function getExtension() : string
    {
        $extension = $this->console->getOption('extension');
        if ($extension === true || $extension === null) {
            return '.php';
        }
        return $extension; // @phpstan-ignore-line;
    }

    public function run() : void
    {
        $dir = $this->getDirectory();
        $extension = $this->getExtension();
        $config = [];
        $files = App::locator()->listFiles($dir);
        foreach ($files as $file) {
            if (!\str_ends_with($file, $extension)) {
                continue;
            }
            \ob_start();
            $contents = Isolation::require($file);
            \ob_end_clean();
            if (!\is_array($contents)) {
                if ($this->allowIgnore()) {
                    continue;
                }
                throw new RuntimeException(
                    'Config file "' . $file . '" does not return an array'
                );
            }
            if (empty($contents)) {
                if ($this->allowIgnore()) {
                    continue;
                }
                throw new RuntimeException(
                    'Config file "' . $file . '" return an empty array'
                );
            }
            foreach ($contents as $key => $values) {
                if (!\is_string($key)) {
                    if ($this->allowIgnore()) {
                        continue 2;
                    }
                    throw new RuntimeException(
                        'Config file "' . $file . '" return invalid keys (must be strings)'
                    );
                }
                if (!\is_array($values)) {
                    if ($this->allowIgnore()) {
                        continue 2;
                    }
                    throw new RuntimeException(
                        'Config file "' . $file . '" return invalid values (must be arrays)'
                    );
                }
            }
            $service = \substr($file, \strrpos($file, \DIRECTORY_SEPARATOR) + 1);
            $service = \substr($service, 0, -\strlen($extension));
            $config[$service] = $contents;
        }
        $config = \var_export($config, true);
        CLI::write('<?php');
        CLI::write('// Do not edit this file. It is created automatically.');
        CLI::write('// Created at: ' . \gmdate('Y-m-d H:i:s') . ' UTC');
        CLI::write('return ' . $config . ';');
    }

    public function allowIgnore() : bool
    {
        return (bool) $this->console->getOption('i');
    }
}