<?php
use Slim\App;
use App\Middlewares\AuthMiddleware;


return function (App $app) {
    $app->get('/', [\App\Controllers\HomeController::class, 'index'])->setName('home.index');
    $app->get('/users/', [\App\Controllers\UserController::class, 'index'])->setName('users.index');

    $app->get('/users/create/', \App\Controllers\UserController::class . ':create');
    $app->post('/users', \App\Controllers\UserController::class . ':store');
    $app->get('/users/{id}/',\App\Controllers\UserController::class . ':show');
    $app->get('/users/{id}/edit/', \App\Controllers\UserController::class . ':edit');
    $app->put('/users/{id}', \App\Controllers\UserController::class . ':update');
    $app->delete('/users/{id}', \App\Controllers\UserController::class . ':delete');

    //$app->get('/admin', \App\Controllers\AdminController::class . ':index')->setName('admin.index');
    // $app->get('/admin/login', \App\Controllers\AdminController::class . ':login')->setName('admin.login');
    // $app->get('/admin/forms', \App\Controllers\AdminController::class . ':forms')->setName('admin.forms');
    // $app->get('/admin/tables', \App\Controllers\AdminController::class . ':tables')->setName('admin.tables');
    // $app->get('/admin/modals', \App\Controllers\AdminController::class . ':modals')->setName('admin.modals');
    // $app->get('/admin/buttons', \App\Controllers\AdminController::class . ':buttons')->setName('admin.buttons');
    // $app->get('/admin/ui', \App\Controllers\AdminController::class . ':ui')->setName('admin.ui');

    $app->get('/admin/login', [\App\Controllers\AuthController::class, 'gologin']);
    $app->post('/admin/login', [\App\Controllers\AuthController::class, 'login']);
    $app->get('/admin/logout', [\App\Controllers\AuthController::class, 'logout']);


    $app->group('/admin', function($group) {
        $group->get('', \App\Controllers\AdminController::class . ':index')->setName('admin.index');
        $group->get('/forms', \App\Controllers\AdminController::class . ':forms')->setName('admin.forms');
        $group->get('/tables', \App\Controllers\AdminController::class . ':tables')->setName('admin.tables');
        $group->get('/modals', \App\Controllers\AdminController::class . ':modals')->setName('admin.modals');
        $group->get('/buttons', \App\Controllers\AdminController::class . ':buttons')->setName('admin.buttons');
        $group->get('/ui', \App\Controllers\AdminController::class . ':ui')->setName('admin.ui');
        $group->group('/post', function($group) {
            $group->get('', \App\Controllers\Admin\PostController::class. ':index')->setName('admin.posts.index');
            $group->get('/create', \App\Controllers\Admin\PostController::class. ':create')->setName('admin.posts.create');
            $group->post('', \App\Controllers\Admin\PostController::class. ':store')->setName('admin.posts.store');
            $group->get('/{id}/edit', \App\Controllers\Admin\PostController::class. ':edit')->setName('admin.posts.edit');
            $group->post('/{id}/edit', \App\Controllers\Admin\PostController::class. ':update')->setName('admin.posts.update');
            $group->delete('/{id}', \App\Controllers\Admin\PostController::class. ':delete')->setName('admin.posts.delete');
        });
    })->add(AuthMiddleware::class);
};