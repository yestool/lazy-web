<?php
use Slim\App;
use App\Controllers\HomeController;
use App\Controllers\UserController;

return function (App $app) {
    $app->get('/', [HomeController::class, 'index'])->setName('home.index');
    $app->get('/users', [UserController::class, 'index'])->setName('users.index');

    $app->get('/users/create', UserController::class . ':create');
    $app->post('/users', UserController::class . ':store');
    $app->get('/users/{id}',UserController::class . ':show');
    $app->get('/users/{id}/edit', UserController::class . ':edit');
    $app->put('/users/{id}', UserController::class . ':update');
    $app->delete('/users/{id}', UserController::class . ':delete');
};