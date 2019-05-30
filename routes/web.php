<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

//$router->get('/master/lapangan', 'MasterController@getLapangan');

//$router->get('/master/lapangan/', 'MasterController@getLapangan');

//$router->post('/transaksi/', 'TransactionController@index');
$router->post('/transaksi/', 'TransactionController@t_header');
$router->post('/transaksi/update', 'TransactionController@pre_update');
$router->get('/transaksi/', 'TransactionController@get');
$router->get('/transaksi/hash', 'TransactionController@getHasOrder');
$router->get('/transaksi/orderid', 'TransactionController@getOrderId');
$router->get('/transaksi/check', 'TransactionController@checkBooking');

$router->get('/member/{hari}', 'TransactionController@getMember');

//$router->get('/master/{id}', 'MasterController@show');
