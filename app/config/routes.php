<?php

use app\controllers\PersonController;
use app\controllers\FrontOfficeController;

use app\controllers\ApiExampleController;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;

/** 
 * @var Router $router 
 * @var Engine $app
 */

// This wraps all routes in the group with the SecurityHeadersMiddleware
$router->group('', function(Router $router) use ($app) {

	$router->get('/', function() use ($app) {
		$app->render('welcome', [ 'message' => 'You are gonna do great things!' ]);
	});

	$router->get('/hello-world/@name', function($name) {
		echo '<h1>Hello world! Oh hey '.$name.'!</h1>';
	});

	$router->group('/api', function() use ($router) {
		$router->get('/users', [ ApiExampleController::class, 'getUsers' ]);
		$router->get('/users/@id:[0-9]', [ ApiExampleController::class, 'getUser' ]);
		$router->post('/users/@id:[0-9]', [ ApiExampleController::class, 'updateUser' ]);
		$router->get('/persons', [ PersonController::class, 'index' ]);
	});

	// Back Office
	$router->group('/backoffice', function() use ($router) {
		$router->get('/article?id=[0-9]+', [ BackOfficeController::class, 'article_detail' ]);
	});
	
	// Front Office
	$router->group('/frontoffice', function() use ($router)
	{
		$router->get('/', [ FrontOfficeController::class, 'home' ]);
	});

}, [ SecurityHeadersMiddleware::class ]);