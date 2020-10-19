<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL);

require_once 'config.php';
require_once('classes/LiveScoreAPI.class.php');

$LiveScoreApi = new LiveScoreApi(KEY, SECRET, DB_HOST, DB_USER, DB_PASS, DB_NAME);
$LiveScoreApi->getLivescores();
