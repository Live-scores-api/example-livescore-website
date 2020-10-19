<?php

ini_set('display_errors', 'Off');
error_reporting(E_ALL);

require_once 'config.php';
require_once 'classes/LiveScoreApi.class.php';

$LiveScoreApi = new LiveScoreApi(KEY, SECRET, DB_HOST, DB_USER, DB_PASS, DB_NAME);
echo '<pre>';
var_dump($LiveScoreApi->getLivescores());
