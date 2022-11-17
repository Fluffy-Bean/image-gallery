<?php
if (defined('ROOT') && $_SESSION['id'] == 1) {
    if (is_dir(__DIR__."/../../../usr")) {
        $results[] = array(
            'type'=>'success', 
            'message'=> 'Found usr/ folder!',
        );
    } else {
        try {
            if (mkdir(__DIR__."/../../../usr")) {
                $results[] = array(
                    'type'=>'success', 
                    'message'=> 'Created usr/ folder!',
                );
            } else {
                $results[] = array(
                    'type'=>'critical', 
                    'message'=> 'Error creating usr/ folder',
                    'fix'=>'manual',
                );
            }
        } catch (Exception $e) {
            $results[] = array(
                'type'=>'critical', 
                'message'=> 'Error creating usr/ folder: '.$e,
                'fix'=>'manual',
            );
        }
    }

    if (is_dir(__DIR__."/../../../usr/images")) {
        $results[] = array(
            'type'=>'success', 
            'message'=> 'Found usr/images/ folder!',
        );
    } else {
        try {
            if (mkdir(__DIR__."/../../../usr/images")) {
                $results[] = array(
                    'type'=>'success', 
                    'message'=> 'Created usr/images/ folder!',
                );
            } else {
                $results[] = array(
                    'type'=>'critical', 
                    'message'=> 'Error creating usr/images/ folder',
                    'fix'=>'manual',
                );
            }
        } catch (Exception $e) {
            $results[] = array(
                'type'=>'critical', 
                'message'=> 'Error creating usr/images/ folder: '.$e,
                'fix'=>'manual',
            );
        }
    }

    if (is_dir(__DIR__."/../../../usr/images/thumbnails")) {
        $results[] = array(
            'type'=>'success', 
            'message'=> 'Found usr/images/thumbnails/ folder!',
        );
    } else {
        try {
            if (mkdir(__DIR__."/../../../usr/images/thumbnails")) {
                $results[] = array(
                    'type'=>'success', 
                    'message'=> 'Created usr/images/thumbnails/ folder!',
                );
            } else {
                $results[] = array(
                    'type'=>'critical', 
                    'message'=> 'Error creating usr/images/thumbnails/ folder',
                    'fix'=>'manual',
                );
            }
        } catch (Exception $e) {
            $results[] = array(
                'type'=>'critical', 
                'message'=> 'Error creating usr/images/thumbnails/ folder: '.$e,
                'fix'=>'manual',
            );
        }
    }
    if (is_dir(__DIR__."/../../../usr/images/previews")) {
        $results[] = array(
            'type'=>'success', 
            'message'=> 'Found usr/images/previews/ folder!',
        );
    } else {
        try {
            if (mkdir(__DIR__."/../../../usr/images/previews")) {
                $results[] = array(
                    'type'=>'success', 
                    'message'=> 'Created usr/images/previews/ folder!',
                );
            } else {
                $results[] = array(
                    'type'=>'critical', 
                    'message'=> 'Error creating usr/images/previews/ folder',
                    'fix'=>'manual',
                );
            }
        } catch (Exception $e) {
            $results[] = array(
                'type'=>'critical', 
                'message'=> 'Error creating usr/images/previews/ folder: '.$e,
                'fix'=>'manual',
            );
        }
    }

    if (is_dir(__DIR__."/../../../usr/conf")) {
        $results[] = array(
            'type'=>'success', 
            'message'=> 'Found usr/conf/ folder!',
        );
    } else {
        try {
            if (mkdir(__DIR__."/../../../usr/conf")) {
                $results[] = array(
                    'type'=>'success', 
                    'message'=> 'Created usr/conf/ folder!',
                );
            } else {
                $results[] = array(
                    'type'=>'critical', 
                    'message'=> 'Error creating usr/conf/ folder',
                    'fix'=>'manual',
                );
            }
        } catch (Exception $e) {
            $results[] = array(
                'type'=>'critical', 
                'message'=> 'Error creating usr/conf/ folder: '.$e,
                'fix'=>'manual',
            );
        }
    }

    if (is_file(__DIR__."/../../../usr/conf/conf.json")) {
        $results[] = array(
            'type'=>'success', 
            'message'=> 'Found usr/conf/conf.json folder!',
        );
    } else {
        try {
            $conf = file_get_contents(__DIR__."/../../../usr/conf.default.json");
            $conf_new = fopen(__DIR__."/../../../usr/conf/conf.json", "w");

            if ($conf_new) {
                fwrite($conf_new, $conf);
                fclose($conf_new);
                
                $results[] = array(
                    'type'=>'success', 
                    'message'=> 'Created usr/conf/conf.json file!',
                );
            } else {
                $results[] = array(
                    'type'=>'critical', 
                    'message'=> 'Error creating usr/conf/conf.json file!',
                    'fix'=>'manual',
                );
            }
        } catch (Exception $e) {
            $results[] = array(
                'type'=>'critical', 
                'message'=> 'Error creating usr/conf/conf.json file: '.$e,
                'fix'=>'manual',
            );
        }
    }

    if (is_file(__DIR__."/../../../usr/conf/msg.json")) {
        $results[] = array(
            'type'=>'success', 
            'message'=> 'Found usr/conf/msg.json folder!',
        );
    } else {
        try {
            $conf = json_encode(array('welcome'=>array('Welcome to your new Only Legs installation!')));
            $conf_new = fopen(__DIR__."/../../../usr/conf/msg.json", "w");

            if ($conf_new) {
                fwrite($conf_new, $conf);
                fclose($conf_new);
                
                $results[] = array(
                    'type'=>'success', 
                    'message'=> 'Created usr/conf/msg.json file!',
                );
            } else {
                $results[] = array(
                    'type'=>'critical', 
                    'message'=> 'Error creating usr/conf/msg.json file!',
                    'fix'=>'manual',
                );
            }
        } catch (Exception $e) {
            $results[] = array(
                'type'=>'critical', 
                'message'=> 'Error creating usr/conf/msg.json file: '.$e,
                'fix'=>'manual',
            );
        }
    }
}