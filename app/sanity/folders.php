<?php
if (defined('ROOT')) {
    if (is_dir(__DIR__."/../../usr")) {
        echo "<p><span style='color: var(--accent);'>[INFO]</span> Found usr/ folder!</p>";
    } else {
        echo "<p><span style='color: var(--warning);'>[ERRO]</span>  usr/ folder not found</p>";
        echo "<p><span style='color: var(--accent);'>[INFO]</span>  Creating usr/ folder...</p>";

        mkdir("usr");
    }

    if (is_dir(__DIR__."/../../usr/images")) {
        echo "<p><span style='color: var(--accent);'>[INFO]</span> Found usr/images/ folder!</p>";
    } else {
        echo "<p><span style='color: var(--warning);'>[ERRO]</span>  usr/images/ folder not found</p>";
        echo "<p><span style='color: var(--accent);'>[INFO]</span>  Creating usr/images/ folder...</p>";

        mkdir("usr/images");
    }
    if (!is_dir(__DIR__."/../../usr/images/thumbnails")) {
        echo "<p><span style='color: var(--warning);'>[ERRO]</span>  usr/images/thumbnails/ folder not found</p>";
        echo "<p><span style='color: var(--accent);'>[INFO]</span>  Creating usr/images/thumbnails/ folder...</p>";

        mkdir("usr/images/thumbnails");
    }
    if (!is_dir(__DIR__."/../../usr/images/previews")) {
        echo "<p><span style='color: var(--warning);'>[ERRO]</span>  usr/images/previews/ folder not found</p>";
        echo "<p><span style='color: var(--accent);'>[INFO]</span>  Creating usr/images/previews/ folder...</p>";

        mkdir("usr/images/previews");
    }

    if (!is_dir(__DIR__."/../../usr/conf")) {
        echo "<p><span style='color: var(--warning);'>[ERRO]</span>  usr/conf/ folder not found</p>";
        echo "<p><span style='color: var(--accent);'>[INFO]</span>  Creating usr/conf/ folder...</p>";

        mkdir("usr/conf");
    }

    if (is_file(__DIR__."/../../usr/conf/conf.json")) {
        echo "<p><span style='color: var(--accent);'>[INFO]</span> Found usr/conf/conf.json file!</p>";
    } else {
        echo "<p><span style='color: var(--warning);'>[ERRO]</span>  usr/conf/conf.json file not found</p>";
        echo "<p><span style='color: var(--accent);'>[INFO]</span>  Creating usr/conf/conf.json file...</p>";

        try {
            $conf = file_get_contents(__DIR__."/../../usr/conf.default.json");

            if (file_put_contents(__DIR__."/../../usr/conf/conf.json", $conf)) {
                echo "<p><span style='color: var(--accent);'>[INFO]</span>  usr/conf/conf.json file created!</p>";
            } else {
                echo "<p><span style='color: var(--warning);'>[ERRO]</span>  Failed to create usr/conf/conf.json file</p>";
            }
        } catch (Exception $e) {
            echo "<p><span style='color: var(--warning);'>[ERRO]</span>  Could not read usr/conf.default.json file</p>";
        }
    }
}