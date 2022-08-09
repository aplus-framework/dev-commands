<?php declare(strict_types=1);
/*
 * This file is part of Aplus Framework Dev Commands Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Tests\CLI\Commands\app\Controllers;

use Framework\MVC\Controller;
use Framework\Routing\Attributes\Origin;

#[Origin('https://no-routes.com')]
class NoRoutes extends Controller
{
}
