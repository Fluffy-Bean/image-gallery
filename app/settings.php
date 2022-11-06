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
if (is_file(__DIR__."/../usr/conf/conf.json")) {
    $user_settings = json_decode(file_get_contents(__DIR__."/../usr/conf/conf.json"), true);

    if (is_file(__DIR__."/../usr/conf/msg.json")) {
        $user_welcome   = json_decode(file_get_contents(__DIR__."/../usr/conf/msg.json"), true)['welcome'];
    }
} elseif (is_file(__DIR__."/../usr/conf/manifest.json")) {
    $user_settings  = json_decode(file_get_contents(__DIR__."/../usr/conf/manifest.json"), true);
    $user_welcome   = $user_settings['welcome_msg'];
} elseif (is_file(__DIR__."/manifest.json")) {
    $user_settings  = json_decode(file_get_contents(__DIR__."/manifest.json"), true);
    $user_welcome   = $user_settings['welcome_msg'];
} else {
    $user_settings = json_decode(file_get_contents(__DIR__."/../usr/conf.default.json"), true);
}

$web_info       = json_decode(file_get_contents(__DIR__."/gallery.json"), true);
$upload_conf    = $user_settings['upload'];