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
} else {
    $user_settings = json_decode(file_get_contents(__DIR__."/../usr/conf.default.json"), true);
}

if (is_file(__DIR__."/../usr/conf/msg.json")) {
    $user_welcome   = json_decode(file_get_contents(__DIR__."/../usr/conf/msg.json"), true);
    $user_welcome   = $user_welcome['welcome'];
}

$web_info = json_decode(file_get_contents(__DIR__."/app.json"), true);