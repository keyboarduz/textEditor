<?php
declare(strict_types=1);

use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\Application\Actions\Post\IndexAction;
use App\Application\Actions\Post\UploadImageAction;
use App\Application\Actions\Post\PostCreateAction;
use App\Application\Actions\Post\PostUpdateAction;
use App\Application\Actions\Post\PostViewAction;

return function (App $app) {
    $app->get('/', IndexAction::class);

    $app->group('/image', function (Group $group) {
        $group->post('/upload', UploadImageAction::class);
    });

    $app->any('/post/create', PostCreateAction::class);
    $app->any('/post/update/{id}', PostUpdateAction::class);
    $app->get('/post/view/{id}', PostViewAction::class);
};
