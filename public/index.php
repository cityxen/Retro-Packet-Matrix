<?php

define('ROOT', dirname(__DIR__));
define('APP', ROOT . '/app');

require APP . '/config/app.php';
require APP . '/config/database.php';
require APP . '/core/Database.php';
require APP . '/core/View.php';
require APP . '/core/Controller.php';
require APP . '/core/Router.php';

require APP . '/models/HotspotConfig.php';
require APP . '/models/Device.php';
require APP . '/models/DhcpLease.php';

require APP . '/controllers/DashboardController.php';
require APP . '/controllers/HotspotController.php';
require APP . '/controllers/DevicesController.php';
require APP . '/controllers/DhcpController.php';
require APP . '/controllers/DiagnosticsController.php';

$router = new Router();

$router->get('/',                [DashboardController::class, 'index']);
$router->get('/hotspot',         [HotspotController::class,  'index']);
$router->post('/hotspot/toggle', [HotspotController::class,  'toggle']);
$router->post('/hotspot/save',   [HotspotController::class,  'save']);
$router->get('/devices',         [DevicesController::class,  'index']);
$router->post('/devices/block',  [DevicesController::class,  'block']);
$router->post('/devices/unblock',[DevicesController::class,  'unblock']);
$router->get('/dhcp',            [DhcpController::class,     'index']);
$router->post('/dhcp/dns/add',   [DhcpController::class,     'addDns']);
$router->post('/dhcp/dns/delete',[DhcpController::class,     'deleteDns']);
$router->get('/diagnostics',     [DiagnosticsController::class, 'index']);
$router->post('/diagnostics/ping',  [DiagnosticsController::class, 'ping']);
$router->post('/diagnostics/trace', [DiagnosticsController::class, 'trace']);

$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$router->dispatch($uri, $method);
