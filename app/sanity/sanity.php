<?php
session_start();

include "../conn.php";

if (isset($_POST['fix'])) {
    $timer_start = microtime(true);

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
    require_once "fix/_database.php";

    echo "<p>==== Folders ====</p>";
    require_once "fix/_folders.php";

    $timer_end    = microtime(true);
    $timer_diff   = ($timer_end - $timer_start);
    $timer_diff   = round($timer_diff, 6) * 1000;

    echo "<p><span style='color: var(--accent);'>[INFO]</span> Autofix complete in $timer_diff ms</p>";
} elseif (isset($_POST['check'])) {
    if (empty($_SESSION['id'])) {
        echo "<p><span style='color: var(--warning);'>[ERROR]</span> You are not logged in</p>";
        exit();
    } elseif ($_SESSION['id'] != 1) {
        echo "<p><span style='color: var(--warning);'>[ERRO]</span>  You cannot use Autofix as an Admin currently.</p>";
        exit();
    }

    $timer_start = microtime(true);

    $autofix_enable = false;
    $results = array();     // Array to store results
    define('ROOT', true);   // Only run scripts from this file
    
    include_once "check/_dir.php";
    include_once "check/_json.php";
    include_once "check/_perms.php";
    include_once "check/_versions.php";
    include_once "check/_database.php";

    if (empty($results)) {
        echo "<p class='alert alert-good'>No errors! Lookin' good :3</p>";
    } else {
        foreach ($results as $result) {
            if ($result['type'] == 'critical') {
                echo "<p class='alert alert-bad'><span class='badge badge-critical'>Critical</span> ";
            } elseif ($result['type'] == 'warning') {
                echo "<p class='alert alert-warning'><span class='badge badge-warning'>Warning</span> ";
            } elseif ($result['type'] == 'success') {
                echo "<p class='alert alert-good'><span class='badge badge-primary'>Success</span> ";
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
    }

    $timer_end    = microtime(true);
    $timer_diff   = ($timer_end - $timer_start);
    $timer_diff   = round($timer_diff, 6) * 1000;

    echo "<p class='alert alert-good'> Scan complete in $timer_diff ms</p>";

    if ($autofix_enable) {
        echo "<button class='btn btn-bad' onclick=\"$('#sanityCheck').load('app/sanity/sanity.php', {fix: 'true'});\">
                Attempt Autofix
            </button>";
    }
} else {
    echo "<p class='alert alert-warning'><span class='badge badge-warning'>Warning</span> Bruh, what do you want?</p>";
}