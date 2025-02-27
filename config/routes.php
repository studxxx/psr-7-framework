<?php

/** @var \Framework\Http\Application $app */

use App\Http\Action as Action;

$app->get('home', '/', Action\HelloAction::class);
$app->get('about', '/about', Action\AboutAction::class);
$app->get('blog', '/blog', Action\Blog\IndexAction::class);
$app->get('blog_page', '/blog/page/{page}', Action\Blog\IndexAction::class, ['tokens' => ['page' => '\d+']]);
$app->get('blog_show', '/blog/{id}', Action\Blog\ShowAction::class, ['tokens' => ['id' => '\d+']]);
$app->get('cabinet', '/cabinet', Action\CabinetAction::class);
