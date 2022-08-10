<?php
/*
 * This file is part of Aplus Framework Dev Commands Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
use Framework\MVC\App;
use Framework\Routing\RouteCollection;

App::router()->serve('https://domain.tld', static function (RouteCollection $routes) : void {
    $routes->namespace('App\Controllers', [
        $routes->get('/', 'Home::index', 'home'),
        $routes->get('/contact', 'Contact::index', 'contact'),
        $routes->post('/contact', 'Contact::create', 'contact.create'),
    ]);
    $routes->notFound('App\Controllers\Errors::error404');
})->serve('https://api.domain.tld', static function (RouteCollection $routes) : void {
    $routes->resource('/users', 'Api\Controllers\Users', 'users');
    $routes->notFound(static fn () => 'Route Not Found');
}, 'api');
