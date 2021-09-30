<?php
/*
 * This file is part of Aplus Framework CLI Commands Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
use Framework\CodingStandard\Config;
use Framework\CodingStandard\Finder;

return (new Config())->setDefaultHeaderComment(
    'Aplus Framework CLI Commands Library',
    'Natan Felles <natanfelles@gmail.com>'
)->setFinder(
    Finder::create()->in(__DIR__)
);
