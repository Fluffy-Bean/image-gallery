<?php
if (defined('ROOT') && $_SESSION['id'] == 1) {
    $files = array(
        'usr/images', 
        'usr/images/pfp', 
        'usr/images/previews', 
        'usr/images/thumbnails'
    );
    
    foreach ($files as $file) {
        if (!is_dir(__DIR__."/../../../$file")) {
            $results[] = array(
                'type'=>'critical', 
                'message'=>"$file is missing", 
                'fix'=>'auto'
            );
        }
    }
}