<?php
if (defined('ROOT') && $_SESSION['id'] == 1) {
    $files = array(
        'usr/images', 
        'usr/images/pfp', 
        'usr/images/previews', 
        'usr/images/thumbnails', 
        'usr/conf/conf.json', 
        'usr/conf/msg.json', 
        'usr/conf.default.json'
    );
    
    foreach ($files as $file) {
        if (!is_writable(__DIR__."/../../../$file")) {
            $results[] = array(
                'type'=>'critical', 
                'message'=>"$file is not writable", 
                'fix'=>'manual'
            );
        }
    }
    
    foreach ($files as $file) {
        if (!fileperms(__DIR__."/../../../$file")) {
            $results[] = array(
                'type'=>'critical', 
                'message'=>"PHP does not have permitions for $file", 
                'fix'=>'manual'
            );
        }
    }
}