<?php
if (defined('ROOT') && $_SESSION['id'] == 1) {
    // Local app info
    $app_local  = json_decode(file_get_contents(__DIR__."/../../gallery.json"), true);
    
    // Repo app info
    $curl_url   = "https://raw.githubusercontent.com/Fluffy-Bean/image-gallery/".$app_local['branch']."/app/settings/manifest.json";
    $curl       = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_URL, $curl_url);
    $result     = curl_exec($curl);
    curl_close($curl);
    
    $app_repo   = json_decode($result, true);
    
    // Go to newer file location to prevent errors once the old location is removed
    if (!$app_repo || empty($app_repo)) {
        $curl_url   = "https://raw.githubusercontent.com/Fluffy-Bean/image-gallery/".$app_local['branch']."/app/gallery.json";
        $curl       = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $curl_url);
        $result     = curl_exec($curl);
        curl_close($curl);
    
        $app_repo   = json_decode($result, true);
    }
    
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
}