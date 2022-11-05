<?php
session_start();

include dirname(__DIR__) . "/conn.php";

if (isset($_POST['fix'])) {
    $autofix_start = microtime(true);

    echo "<p><span style='color: var(--accent);'>[INFO]</span> Starting autofix</p>";

    if (empty($_SESSION['id'])) {
        echo "<p><span style='color: var(--warning);'>[ERROR]</span> You are not logged in</p>";
        exit();
    } elseif ($_SESSION['id'] != 1) {
        echo "<p><span style='color: var(--warning);'>[ERRO]</span>  You cannot use Autofix as an Admin currently.</p>";
        exit();
    }

    define('ROOT', true); // Only run scripts from this file
    
    echo "<p>==== Databases ====</p>";
    include_once "database.php";

    echo "<p>==== Folders ====</p>";
    include_once "folders.php";

    $autofix_end    = microtime(true);
    $autofix_time   = ($autofix_end - $autofix_start);
    $autofix_time   = round($autofix_time, 6) * 1000;

    echo "<p><span style='color: var(--accent);'>[INFO]</span> Autofix complete in $autofix_time ms</p>";
}

if (isset($_POST['check'])) {
    function check_json(): array
    {
        $results = array();

        if (!is_file(__DIR__."/../../usr/conf/msg.json")) {
            $results[] = array(
                'type'=>'warning', 
                'message'=>'msg.json is missing', 
                'fix'=>'auto'
            );
        }

        if (!is_file(__DIR__."/../../usr/conf/conf.json")) {
            if (is_file(__DIR__."/../../usr/conf/manifest.json")) {
                $results[] = array(
                    'type'=>'critical', 
                    'message'=>'manifest.json is deprecated, please rename it to conf.json', 
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
            $manifest = json_decode(file_get_contents(__DIR__."/../../usr/conf/conf.json"), true);

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

        return $results;
    }

    function check_dir(): array
    {
        $results = array();

        $files = array(
            'usr/images', 
            'usr/images/pfp', 
            'usr/images/previews', 
            'usr/images/thumbnails'
        );

        foreach ($files as $file) {
            if (!is_dir(__DIR__."/../../$file")) {
                $results[] = array(
                    'type'=>'critical', 
                    'message'=>"$file is missing", 
                    'fix'=>'auto'
                );
            }
        }

        return $results;
    }

    function check_version(): array
    {
        $results    = array();

        // Local app info
        $app_local  = json_decode(file_get_contents(__DIR__."/../gallery.json"), true);

        // Repo app info
        $curl_url   = "https://raw.githubusercontent.com/Fluffy-Bean/image-gallery/".$app_local['branch']."/app/settings/manifest.json";
        $curl       = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $curl_url);
        $result     = curl_exec($curl);
        curl_close($curl);

        $app_repo   = json_decode($result, true);

        if ($app_local['version'] < $app_repo['version']) {
            $results[] = array(
                'type'=>'critical',
                'message'=>'You are not running the latest version of the app v'.$app_repo['version'],
                'link'=>'https://github.com/Fluffy-Bean/image-gallery',
                'fix'=>'manual'
            );
        } elseif ($app_local['version'] > $app_repo['version']) {
            $results[] = array(
                'type'=>'critical',
                'message'=>'You are running a version of the app that is newer than the latest release v'.$app_repo['version'],
                'link'=>'https://github.com/Fluffy-Bean/image-gallery',
                'fix'=>'manual'
            );
        }

        if (PHP_VERSION_ID < 80000) {
            $results[] = array(
                'type'=>'warning',
                'message'=>'Your current version of PHP is '.PHP_VERSION.' The reccomended version is 8.0.0 or higher',
                'link'=>'https://www.php.net/downloads.php',
                'fix'=>'manual'
            );
        }

        return $results;
    }

    function check_permissions(): array
    {
        $results = array();

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
            if (!is_writable(__DIR__."/../../$file")) {
                $results[] = array(
                    'type'=>'critical', 
                    'message'=>"$file is not writable", 
                    'fix'=>'manual'
                );
            }
        }

        foreach ($files as $file) {
            if (!fileperms(__DIR__."/../../$file")) {
                $results[] = array(
                    'type'=>'critical', 
                    'message'=>"PHP does not have permitions for $file", 
                    'fix'=>'manual'
                );
            }
        }

        return $results;
    }

    if ($_SESSION['id'] == 1) {
        $results = array();

        foreach (check_json() as $result) $results[] = $result;
        foreach (check_dir() as $result) $results[] = $result;
        foreach (check_permissions() as $result) $results[] = $result;
        foreach (check_version() as $result) $results[] = $result;

        if (empty($results)) {
            echo "<p class='alert alert-good'>No errors! Lookin' good :3</p>";
        } else {
            foreach ($results as $result) {
                if ($result['type'] == 'critical') {
                    echo "<p class='alert alert-bad'><span class='badge badge-critical'>Critical</span> ";
                } else {
                    echo "<p class='alert alert-warning'><span class='badge badge-warning'>Warning</span> ";
                }
        
                if ($result['fix'] == 'auto') {
                    echo "<span class='badge badge-primary'>Auto fix available</span> ";	
                    $autofix_enable = true;
                } elseif ($result['fix'] == 'manual') {
                    echo "<span class='badge badge-critical'>Manual fix required</span> ";
                }
        
                if (isset($result['link'])) {
                    echo "<a class='link badge badge-primary' href='".$result['link']."'>Recources</a> ";
                }
                
                echo $result['message']."</p>";
            }
        
            if ($autofix_enable) {
                echo "<button class='btn btn-bad' onclick=\"$('#sanityCheck').load('app/sanity/sanity.php', {fix: 'true'});\">
                        Attempt Autofix
                    </button>";
            }
        }
    } else {
        echo "<p class='alert alert-bad'>You do not have permission todo this action!!!!!</p>";
    }    
}