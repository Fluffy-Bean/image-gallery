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
        $results[] = array(
            'type'=>'success', 
            'message'=> 'Found images table',
        );
    } else {
        $sql = "CREATE TABLE IF NOT EXISTS images ( 
            id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
            imagename VARCHAR(255) UNIQUE,
            alt TEXT,
            tags TEXT,
            author VARCHAR(50) NOT NULL,
            last_modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        try {
            if ($conn->query($sql) === TRUE) {
                $results[] = array(
                    'type'=>'success', 
                    'message'=> 'Table images created!',
                );
            } else {
                $results[] = array(
                    'type'=>'critical', 
                    'message'=> 'Error creating table images: '.$conn->error,
                    'fix'=>'manual',
                );
            }
        } catch (Exception $e) {
            $results[] = array(
                'type'=>'critical', 
                'message'=> 'Error creating table images: '.$e,
                'fix'=>'manual',
            );
        }
    }
    
    if (check_database($conn, 'users')) {
        $results[] = array(
            'type'=>'success', 
            'message'=> 'Found users table',
        );
    } else {
        $sql = "CREATE TABLE IF NOT EXISTS users ( 
            id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            pfp_path VARCHAR(50),
            admin BOOLEAN DEFAULT FALSE,
            last_modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        try {
            if ($conn->query($sql) === TRUE) {
                $results[] = array(
                    'type'=>'success', 
                    'message'=> 'Table users created!',
                );
            } else {
                $results[] = array(
                    'type'=>'critical', 
                    'message'=> 'Error creating table users: '.$conn->error,
                    'fix'=>'manual',
                );
            }
        } catch (Exception $e) {
            $results[] = array(
                'type'=>'critical', 
                'message'=> 'Error creating table users: '.$e,
                'fix'=>'manual',
            );
        }
    }
    
    if (check_database($conn, 'groups')) {
        $results[] = array(
            'type'=>'success', 
            'message'=> 'Found groups table',
        );
    } else {
        $sql = "CREATE TABLE IF NOT EXISTS groups (`
            id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
            group_name TEXT NOT NULL,
            image_list TEXT NOT NULL,
            author VARCHAR(50) NOT NULL,
            last_modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        try {
            if ($conn->query($sql) === TRUE) {
                $results[] = array(
                    'type'=>'success', 
                    'message'=> 'Table groups created!',
                );
            } else {
                $results[] = array(
                    'type'=>'critical', 
                    'message'=> 'Error creating table groups: '.$conn->error,
                    'fix'=>'manual',
                );
            }
        } catch (Exception $e) {
            $results[] = array(
                'type'=>'critical', 
                'message'=> 'Error creating table groups: '.$e,
                'fix'=>'manual',
            );
        }
    }
    
    if (check_database($conn, 'logs')) {
        $results[] = array(
            'type'=>'success', 
            'message'=> 'Found logs table',
        );
    } else {
        $sql = "CREATE TABLE IF NOT EXISTS logs (
            id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
            ipaddress VARCHAR(16) NOT NULL,
            action TEXT NOT NULL,
            time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        try {
            if ($conn->query($sql) === TRUE) {
                $results[] = array(
                    'type'=>'success', 
                    'message'=> 'Table logs created!',
                );
            } else {
                $results[] = array(
                    'type'=>'critical', 
                    'message'=> 'Error creating table logs: '.$conn->error,
                    'fix'=>'manual',
                );
            }
        } catch (Exception $e) {
            $results[] = array(
                'type'=>'critical', 
                'message'=> 'Error creating table logs: '.$e,
                'fix'=>'manual',
            );
        }
    }
    
    if (check_database($conn, 'bans')) {
        $results[] = array(
            'type'=>'success', 
            'message'=> 'Found bans table',
        );
    } else {
        $sql = "CREATE TABLE IF NOT EXISTS bans (            
            id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
            ipaddress VARCHAR(16) NOT NULL,
            reason TEXT NOT NULL,
            time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            length VARCHAR(255) NOT NULL,
            permanent BOOLEAN NOT NULL DEFAULT FALSE
        )";
        
        try {
            if ($conn->query($sql) === TRUE) {
                $results[] = array(
                    'type'=>'success', 
                    'message'=> 'Table bans created!',
                );
            } else {
                $results[] = array(
                    'type'=>'critical', 
                    'message'=> 'Error creating table bans: '.$conn->error,
                    'fix'=>'manual',
                );
            }
        } catch (Exception $e) {
            $results[] = array(
                'type'=>'critical', 
                'message'=> 'Error creating table bans: '.$e,
                'fix'=>'manual',
            );
        }
    }
    
    if (check_database($conn, 'tokens')) {
        $results[] = array(
            'type'=>'success', 
            'message'=> 'Found tokens table',
        );
    } else {
        $sql = "CREATE TABLE IF NOT EXISTS tokens (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            code VARCHAR(16) NOT NULL,
            used BOOLEAN NOT NULL DEFAULT FALSE,
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        
        try {
            if ($conn->query($sql) === TRUE) {
                $results[] = array(
                    'type'=>'success', 
                    'message'=> 'Table tokens created!',
                );
            } else {
                $results[] = array(
                    'type'=>'critical', 
                    'message'=> 'Error creating table tokens: '.$conn->error,
                    'fix'=>'manual',
                );
            }
        } catch (Exception $e) {
            $results[] = array(
                'type'=>'critical', 
                'message'=> 'Error creating table tokens: '.$e,
                'fix'=>'manual',
            );
        }
    }
    
    /* TEST TABLE, NOT TO BE USED IN RRODCTION
    if (check_database($conn, 'test')) {
        $results[] = array(
            'type'=>'success', 
            'message'=> 'Found test table',
        );
    } else {
        $results[] = array(
            'type'=>'critical', 
            'message'=> 'Error creating table tokens: This is a test, you do not need to act on this',
        );
    }
    */
}