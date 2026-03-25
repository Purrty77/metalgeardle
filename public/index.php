<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/src/bootstrap.php';

use App\Controllers\GameController;
use App\Core\Config;
use App\Core\Router;

$basePath = (string) (Config::get('app', 'base_url') ?? '');
$router = new Router($basePath);
$gameController = new GameController();

$router->get('/', [$gameController, 'index']);
$router->get('/privacy-policy', [$gameController, 'privacy']);
$router->post('/guess', [$gameController, 'guess']);

$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');
