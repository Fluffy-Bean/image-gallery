<?php
session_start();

include dirname(__DIR__) . "/conn.php";
include dirname(__DIR__) . "/app.php";

use App\Sanity;

$sanity = new Sanity();

if (isset($_POST['autofix'])) {
    $autofix_start = microtime(true);

    echo "<p><span style='color: var(--accent);'>[INFO]</span> Starting autofix</p>";

    if ($_SESSION['id'] != 1) {
        echo "<p><span style='color: var(--warning);'>[ERRO]</span>  You cannot use Autofix as an Admin currently.</p>";
        exit();
    }
    
    $check_sanity = $sanity->get_results();
    if (empty($check_sanity)) {
        echo "<p><span style='color: var(--accent);'>[INFO]</span> Sanity check passed. No errors found.</p>";
        exit();
    } else {
        //echo "<p><span style='color: var(--alert);'>[WARN]</span> Sanity check failed</p>";
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