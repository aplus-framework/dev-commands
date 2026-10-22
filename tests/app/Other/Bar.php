<?php declare(strict_types=1);
/*
 * This file is part of Aplus Framework Dev Commands Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Tests\Cli\Commands\app\Other;

use Aplus\Mvc\Controller;
use Aplus\Routing\Attributes\RouteNotFound;

class Bar extends Controller
{
    #[RouteNotFound]
    public function notFound() : void
    {
    }
}
