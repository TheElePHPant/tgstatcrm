<?php

use Encore\Admin\Controllers\AuthController;
use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix' => config('admin.route.prefix'),
    'namespace' => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
    'as' => config('admin.route.prefix') . '.',
], function (Router $router) {


    $router->group([
        'prefix' => '2fa',
        'as' => '2fa.',
    ], function ($router) {
        $router->get('enable', 'AuthController@enable2fa')->name('enable')
            ->withoutMiddleware(['verify2fa']);
        $router->get('verify', 'AuthController@form2fa')->name('form')
            ->withoutMiddleware(['verify2fa']);
        $router->post('verify', 'AuthController@verify2fa')->name('verify')
            ->withoutMiddleware(['verify2fa']);
        $router->get('disable', 'AuthController@disable2fa')->name('disable');
        $router->post('disable', 'AuthController@disable2faConfirm');
    });

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('topics', TopicsController::class);
    $router->resource('channels', ChannelsController::class);
    $router->get('update-channel/{id}', [\App\Admin\Controllers\ChannelsController::class, 'updateInfo'])->name('channels.update-info');
    $router->resource('clients', ClientsController::class);
    $router->resource('transactions', TransactionsController::class);
    $router->group(['prefix' => 'transactions', 'as' => 'transactions.'], function ($router) {
        $router->get('create-consumption/{channel}', [\App\Admin\Controllers\TransactionsController::class, 'createConsumption'])->name('create-consumption');
        $router->post('create-consumption/{channel}', [\App\Admin\Controllers\TransactionsController::class, 'storeConsumption'])->name('store-consumption');
    });
    $router->group(['prefix' => 'clients', 'as' => 'clients.'], function ($router) {
        $router->get('search', [\App\Admin\Controllers\ClientsController::class, 'search'])->name('search');
        $router->post('quick-create', [\App\Admin\Controllers\ClientsController::class, 'quickCreate'])->name('quick-create');
    });
    $router->resource('auth/users', UserController::class)->names('admin.auth.users');
});
