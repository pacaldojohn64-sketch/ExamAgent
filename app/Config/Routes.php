<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Authentication Routes
$routes->get('ExamAgent', 'ExamAgent::index');
$routes->get('ExamAgent/register', 'ExamAgent::register');
$routes->post('ExamAgent/storeUser', 'ExamAgent::storeUser');
$routes->post('ExamAgent/login', 'ExamAgent::login');
$routes->get('ExamAgent/logout', 'ExamAgent::logout');

// Core App Routes
$routes->get('ExamAgent/predictor', 'ExamAgent::predictor');
$routes->post('ExamAgent/processForm', 'ExamAgent::processForm');
$routes->post('ExamAgent/uploadCsv', 'ExamAgent::uploadCsv'); // NEW BATCH UPLOAD ROUTE
$routes->get('ExamAgent/dashboard', 'ExamAgent::dashboard');

// CRUD Routes (Update & Delete)
$routes->get('ExamAgent/edit/(:num)', 'ExamAgent::edit/$1');
$routes->post('ExamAgent/update/(:num)', 'ExamAgent::update/$1');
$routes->get('ExamAgent/delete/(:num)', 'ExamAgent::delete/$1');

// Export Route
$routes->get('ExamAgent/export', 'ExamAgent::exportCsv');
