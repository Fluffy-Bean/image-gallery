<?php
$exec_start = microtime(true); // Track how long it took to generate the page

require_once dirname(__DIR__)."/app/conn.php";
require_once dirname(__DIR__)."/app/app.php";
require_once dirname(__DIR__)."/app/settings.php";

ini_set('post_max_size', $upload_conf['max_filesize']);
ini_set('upload_max_filesize', $upload_conf['max_filesize']);

if ($user_settings['is_testing']) {
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ERROR | E_PARSE | E_NOTICE);
}