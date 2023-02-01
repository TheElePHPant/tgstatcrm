<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('channels', ChannelsController::class);
    $router->get('update-channel/{id}', [\App\Admin\Controllers\ChannelsController::class, 'updateInfo'])->name('channels.update-info');
    $router->resource('clients', ClientsController::class);
    $router->resource('transactions', TransactionsController::class);
    $router->group(['prefix' => 'clients', 'as'=>'clients.'], function($router){
        $router->get('search', [\App\Admin\Controllers\ClientsController::class, 'search'])->name('search');
    });
});
