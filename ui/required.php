<?php
require_once dirname(__DIR__)."/app/server/conn.php";
require_once dirname(__DIR__)."/app/app.php";
require_once dirname(__DIR__)."/app/settings/settings.php";

ini_set('post_max_size', $user_settings['upload_max']."M");
ini_set('upload_max_filesize', ($user_settings['upload_max'] + 1)."M");

if ($user_settings['is_testing'] == "true") {
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ERROR | E_PARSE | E_NOTICE);
}