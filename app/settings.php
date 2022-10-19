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
$user_settings  = json_decode(file_get_contents(__DIR__."/../usr/conf/manifest.json"), true);
$web_info       = json_decode(file_get_contents(__DIR__."/app.json"), true);

$upload_conf    = $user_settings["upload"];