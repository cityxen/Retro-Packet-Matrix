<?php

define('APP_NAME',    'RPM Hotspot Manager');
define('APP_VERSION', '0.1.0');
define('APP_ENV',     'development');
define('APP_URL',     'http://localhost');

define('VIEWS',   APP . '/views');
define('SCRIPTS', ROOT . '/scripts');

error_reporting(E_ALL);
ini_set('display_errors', APP_ENV === 'development' ? '1' : '0');

session_start();
