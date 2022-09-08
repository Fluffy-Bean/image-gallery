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
$user_import = file_get_contents("app/settings/user_settings.json");
$user_settings = json_decode($user_import, true);

foreach ($user_settings->data as $settings_list) {
    foreach ($settings_list->website as $website) {
        foreach ($website->database as $database) {

        }
        foreach ($website->debug as $debug) {

        }
        foreach ($website->plugins as $plugins) {

        }
    }
}
$database = $user_settings["website"]["database"];
$debug = $user_settings["website"]["debug"];
$plugins = $user_settings["website"]["plugins"];
