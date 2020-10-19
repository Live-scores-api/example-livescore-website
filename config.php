<?php

$ini = parse_ini_file('config.ini');
define('KEY', $ini['KEY']);
define('SECRET', $ini['SECRET']);
define('DB_HOST', $ini['DB_HOST']);
define('DB_PASS', $ini['DB_PASS']);
define('DB_USER', $ini['DB_USER']);
define('DB_NAME', $ini['DB_NAME']);

