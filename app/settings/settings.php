<?php
/*
 |-------------------------------------------------------------
 | Settings (decode)
 |-------------------------------------------------------------
 | This is for decoding the settings Json, used throughout
 | most of the website. Used for settings things such as
 | the default background and accent colour
 |-------------------------------------------------------------
*/
$user_import    = file_get_contents(__DIR__."/manifest.json");
$user_settings  = json_decode($user_import, true);
$upload_conf    = $user_settings["upload"];