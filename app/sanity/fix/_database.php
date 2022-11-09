<?php
if (defined('ROOT') && $_SESSION['id'] == 1) {
    function check_database($conn, $database) {
        try {
            $check = $conn->query("SELECT 1 FROM $database LIMIT 1");
    
            if ($check) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }
    
    
    if (check_database($conn, 'images')) {
        echo "<p><span style='color: var(--accent);'>[INFO]</span> Found images table</p>";
    } else {
        echo "<p><span style='color: var(--warning);'>[INFO]</span> Could not find images table</p>";
        echo "<p><span style='color: var(--alert);'>[INFO]</span> Creating test table...</p>";

        $sql = "CREATE TABLE IF NOT EXISTS images ( 
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            imagename VARCHAR(255) NOT NULL UNIQUE,
            alt TEXT NOT NULL,
            tags TEXT NOT NULL,
            author INT(11) NOT NULL,
            last_modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        if ($conn->query($sql) === TRUE) {
            echo "<p><span style='color: var(--accent);'>[INFO]</span> Table images made!</p>";
        }
    }
    
    if (check_database($conn, 'users')) {
        echo "<p><span style='color: var(--accent);'>[INFO]</span> Found users table</p>";
    } else {
        echo "<p><span style='color: var(--warning);'>[INFO]</span> Could not find users table</p>";
        echo "<p><span style='color: var(--alert);'>[INFO]</span> Creating test table...</p>";

        $sql = "CREATE TABLE IF NOT EXISTS users ( 
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            pfp_path VARCHAR(255) NOT NULL,
            admin BOOLEAN NOT NULL DEFAULT FALSE, 
            last_modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        if ($conn->query($sql) === TRUE) {
            echo "<p><span style='color: var(--accent);'>[INFO]</span> Table users made!</p>";
        }
    }
    
    if (check_database($conn, 'groups')) {
        echo "<p><span style='color: var(--accent);'>[INFO]</span> Found groups table</p>";
    } else {
        echo "<p><span style='color: var(--warning);'>[INFO]</span> Could not find groups table</p>";
        echo "<p><span style='color: var(--alert);'>[INFO]</span> Creating test table...</p>";

        $sql = "CREATE TABLE IF NOT EXISTS groups (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            group_name VARCHAR(255) NOT NULL,
            image_list VARCHAR(text) NOT NULL,
            last_modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        if ($conn->query($sql) === TRUE) {
            echo "<p><span style='color: var(--accent);'>[INFO]</span> Table groups made!</p>";
        }
    }
    
    if (check_database($conn, 'logs')) {
        echo "<p><span style='color: var(--accent);'>[INFO]</span> Found logs table</p>";
    } else {
        echo "<p><span style='color: var(--warning);'>[INFO]</span> Could not find logs table</p>";
        echo "<p><span style='color: var(--alert);'>[INFO]</span> Creating test table...</p>";

        $sql = "CREATE TABLE IF NOT EXISTS logs (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            ipaddress VARCHAR(16) NOT NULL,
            action VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        if ($conn->query($sql) === TRUE) {
            echo "<p><span style='color: var(--accent);'>[INFO]</span> Table logs made!</p>";
        }
    }
    
    if (check_database($conn, 'bans')) {
        echo "<p><span style='color: var(--accent);'>[INFO]</span> Found bans table</p>";
    } else {
        echo "<p><span style='color: var(--warning);'>[INFO]</span> Could not find bans table</p>";
        echo "<p><span style='color: var(--alert);'>[INFO]</span> Creating test table...</p>";

        $sql = "CREATE TABLE IF NOT EXISTS bans (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            ipaddress VARCHAR(16) NOT NULL,
            reason VARCHAR(255) NOT NULL,
            time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            length VARCHAR(255) NOT NULL,
            permanent BOOLEAN NOT NULL DEFAULT FALSE
        )";
        if ($conn->query($sql) === TRUE) {
            echo "<p><span style='color: var(--accent);'>[INFO]</span> Table bans made!</p>";
        }
    }
    
    if (check_database($conn, 'tokens')) {
        echo "<p><span style='color: var(--accent);'>[INFO]</span> Found tokens table</p>";
    } else {
        echo "<p><span style='color: var(--warning);'>[INFO]</span> Could not find tokens table</p>";
        echo "<p><span style='color: var(--alert);'>[INFO]</span> Creating test table...</p>";

        $sql = "CREATE TABLE IF NOT EXISTS tokens (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            code VARCHAR(59) NOT NULL,
            used BOOLEAN NOT NULL DEFAULT FALSE,
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        if ($conn->query($sql) === TRUE) {
            echo "<p><span style='color: var(--accent);'>[INFO]</span> Table tokens made!</p>";
        }
    }
    
    if (check_database($conn, 'test')) {
        echo "<p><span style='color: var(--accent);'>[INFO]</span> Found test table</p>";
    } else {
        echo "<p><span style='color: var(--warning);'>[ERRO]</span> Could not find test table</p>";
        echo "<p><span style='color: var(--alert);'>[INFO]</span> Creating test table...</p>";
    }
}