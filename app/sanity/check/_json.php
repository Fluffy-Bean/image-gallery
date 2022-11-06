<?php
if (defined('ROOT') && $_SESSION['id'] == 1) {
    if (!is_file(__DIR__."/../../../usr/conf/msg.json")) {
        $results[] = array(
            'type'=>'warning', 
            'message'=>'msg.json is missing', 
            'fix'=>'auto'
        );
    }
    
    if (!is_file(__DIR__."/../../../usr/conf/conf.json")) {
        if (is_file(__DIR__."/../../../usr/conf/manifest.json")) {
            $results[] = array(
                'type'=>'warning', 
                'message'=>'manifest.json is deprecated, the file should be renamed to conf.json if you dont want errors in the future', 
                'fix'=>'manual'
            );
        } elseif (is_file(__DIR__."/../../../app/settings/manifest.json")) {
            $results[] = array(
                'type'=>'warning', 
                'message'=>'manifest.json is deprecated, the file should be renamed to conf.json and move it to usr/conf if you dont want errors in the future', 
                'fix'=>'manual'
            );
        } else {
            $results[] = array(
                'type'=>'critical', 
                'message'=>'conf.json is missing, using conf.default.json instead', 
                'fix'=>'auto'
            );
        }
    } else {
        $manifest = json_decode(file_get_contents(__DIR__."/../../../usr/conf/conf.json"), true);
    
        if (empty($manifest['user_name']) || $manifest['user_name'] == "[your name]") {
            $results[] = array(
                'type'=>'warning', 
                'message'=>'conf.json is missing your name', 
                'fix'=>'manual'
            );
        }
        if ($manifest['upload']['rename_on_upload']) {
            if (empty($manifest['upload']['rename_to'])) {
                $results[] = array(
                    'type'=>'critical', 
                    'message'=>'conf.json doesnt know what to rename your files to', 
                    'fix'=>'manual'
                );
            } else {
                $rename_to      = $manifest['upload']['rename_to'];
                $rename_rate    = 0;
    
                if (str_contains($rename_to, '{{autoinc}}')) $rename_rate = 5;
                if (str_contains($rename_to, '{{time}}')) $rename_rate = 5;
    
                if (str_contains($rename_to, '{{date}}')) $rename_rate += 2;
                if (str_contains($rename_to, '{{filename}}')) $rename_rate += 2;
    
                if (str_contains($rename_to, '{{username}}') || str_contains($rename_to, '{{userid}}')) $rename_rate += 1;
    
                if ($rename_rate < 2) {
                    $results[] = array(
                        'type'=>'critical', 
                        'message'=>'You will encounter errors when uploading images due to filenames, update your conf.json', 
                        'fix'=>'manual'
                    );
                } elseif ($rename_rate < 5 && $rename_rate > 2) {
                    $results[] = array(
                        'type'=>'warning', 
                        'message'=>'You may encounter errors when uploading images due to filenames, concider modifying your conf.json', 
                        'fix'=>'manual'
                    );
                }
            }
        }
    
        if ($manifest['is_testing']) {
            $results[] = array(
                'type'=>'warning', 
                'message'=>'You are currently in testing mode, errors will be displayed to the user. This is not recommended for production use.'
            );
        }
    }
}