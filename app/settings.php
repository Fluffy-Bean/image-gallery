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
$user_settings  = json_decode(file_get_contents(__DIR__."/../usr/conf/conf.json"), true);

if (is_file(__DIR__."/../usr/conf/conf.json")) {
    $settings = json_decode(file_get_contents(__DIR__."/../usr/conf/conf.json"), true);
} else {
    $settings = json_decode(file_get_contents(__DIR__."/../usr/conf.default.json"), true);
}

if (is_file(__DIR__."/../usr/conf/msg.json")) {
    $user_welcome   = json_decode(file_get_contents(__DIR__."/../usr/conf/msg.json"), true);
    $user_welcome   = $user_welcome['welcome'];
} else {
    $user_welcome   = array('Welcome to your new Only Legs installation! You can change this message in the settings page.');
}


$web_info       = json_decode(file_get_contents(__DIR__."/app.json"), true);

$upload_conf    = $user_settings["upload"];