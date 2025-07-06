<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->group('api', ['namespace' => 'App\Controllers\Api'], function ($routes) {
  // Public endpoints
  $routes->post('login', 'AuthController::login');
  $routes->post('register', 'AuthController::register');

  // Protected endpoints (require JWT token)
  $routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('siswa', 'SiswaController::index');
    $routes->post('siswa', 'SiswaController::create');
  });
});
