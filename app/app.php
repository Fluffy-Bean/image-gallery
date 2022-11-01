<?php
namespace App;

use Exception;
use Throwable;
use Imagick;

class Make {
    function thumbnail($image_path, $thumbnail_path, $resolution = 300): Exception|string
    {
        try {
            $thumbnail = new Imagick($image_path);
            $thumbnail->resizeImage($resolution,null,null,1,null);
            $thumbnail->writeImage($thumbnail_path);

            return "success";
        } catch (Exception $e) {
            return $e;
        }
    }

    /*
        Clean up long text input and turn into an array for tags

        Returns clean string of words with equal white space between it
    */
    function tags($string): string
    {
        $string         = str_replace('-', '_', $string);
        $string         = preg_replace('/[^A-Za-z0-9\_ ]/', '', $string);
        $string         = strtolower($string);
        $string         = preg_replace('/ +/', ' ', $string);
        $string         = explode(' ', $string);
        $string_list    = array();

        foreach ($string as $i) {
            if (!in_array($i, $string_list)) {
                $string_list[] = $i;
            }
        }

        $string = implode(' ', $string_list);

        return trim($string);
    }

    function get_image_colour($img): ?string
    {
        $img_type = pathinfo($img, PATHINFO_EXTENSION);

        if ($img_type == "png") {
            $img = imagecreatefrompng($img);
        } elseif ($img_type == "jpg" || $img_type == "jpeg") {
            $img = imagecreatefromjpeg($img);
        } elseif ($img_type == "webp") {
            $img = imagecreatefromwebp($img);
        } else {
            return null;
        }

        try {
            $w = imagesx($img);
            $h = imagesy($img);
            $r = $g = $b = 0;
    
            for($y = 0; $y < $h; $y++) {
                for($x = 0; $x < $w; $x++) {
                    $rgb = imagecolorat($img, $x, $y);
                    $r += $rgb >> 16;
                    $g += $rgb >> 8 & 255;
                    $b += $rgb & 255;
                }
            }
    
            $pxls = $w * $h;
            $r = dechex(round($r / $pxls));
            $g = dechex(round($g / $pxls));
            $b = dechex(round($b / $pxls));
    
            if(strlen($r) < 2) {
                $r = 0 . $r;
            }
            if(strlen($g) < 2) {
                $g = 0 . $g;
            }
            if(strlen($b) < 2) {
                $b = 0 . $b;
            }
    
            return "#" . $r . $g . $b;
        } catch (Throwable $e) {
            return null;
        }
    }
}

class Account {
    /*
        Check if user is loggedin

        Returns True if user is
        Returns False if user is NOT
    */
    function is_loggedin($conn): bool
    {
        $error = 0;

        if (!isset($_SESSION["loggedin"]) || !$_SESSION["loggedin"]) $error += 1;

        if (empty($this->get_user_info($conn, $_SESSION["id"])) || $this->get_user_info($conn, $_SESSION["id"]) == null) $error += 1;

        if ($error > 0) {
            return false;
        } else {
            return true;
        }
    }
    /*
        Get full user info from database

        Returns array with user info
    */
    function get_user_info($conn, $id): bool|array|null
    {
        $sql = "SELECT id, username, created_at, pfp_path FROM users WHERE id = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_user_id);
            
            $param_user_id = $id;
            
            $stmt->execute();
            $query = $stmt->get_result();
            
            // Fetching associated info
            $user_array = mysqli_fetch_assoc($query);
        }
    
        return($user_array);
    }
    /*
        Check if user is admin

        Returns True if user is privilaged
        Returns False if user is NOT privilaged
    */
    function is_admin($conn, $id): bool
    {
        if (isset($id) && !empty($id)) {
            // Setting SQL query
            $sql = "SELECT admin FROM users WHERE id = ?";

            if ($stmt = mysqli_prepare($conn, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "i", $param_user_id);
                
                $param_user_id = $id;
                
                $stmt->execute();
                $query = $stmt->get_result();
                
                // Fetching associated info
                $user_array = mysqli_fetch_assoc($query);
            }
            

            if ($user_array['admin'] || $id == 1) {
                return True;
            } else {
                return False;
            }
        } else {
            return False;
        }
    }
    /*
        Get target IP, used for logging
    */
    function get_ip(): mixed
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $target_ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $target_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $target_ip = $_SERVER['REMOTE_ADDR'];
        }

        return $target_ip;
    }
}

class Image {
    /*
    Get full image info from database

    Returns array with image info
    */
    function get_image_info($conn, $id): bool|array|null
    {
        $sql = "SELECT * FROM images WHERE id = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $id);
            
            $stmt->execute();
            $query = $stmt->get_result();
            
            // Fetching associated info
            $group_array = mysqli_fetch_assoc($query);
        }
    
        return($group_array);
    }
    /*
    Check if user is image owner

    Returns True if user is privilaged
    Returns False if user is NOT privilaged
    */
    function image_privilage($id): bool
    {
        $session_id = $_SESSION['id'];
        if (isset($session_id) || !empty($session_id)) {
            if ($session_id == $id) {
                return True;
            } else {
                return False;
            }
        } else {
            return False;
        }
    }
}

class Group {
    function get_group_info($conn, $id): bool|array|null
    {
        // Setting SQL query
        $sql = "SELECT * FROM groups WHERE id = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $id);
            
            $stmt->execute();
            $query = $stmt->get_result();
            
            // Fetching associated info
            $group_array = mysqli_fetch_assoc($query);
        }
    
        return($group_array);
    }

    function get_group_members($conn, $id): array
    {
        $user_array = array();

        $sql = "SELECT * FROM groups WHERE id = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $id);
            
            $stmt->execute();
            $query = $stmt->get_result();
            
            // Fetching associated info
            $group_array = mysqli_fetch_assoc($query);
        }

        try {
            $image_list = explode(" ", $group_array['image_list']);
            $user_array = array();
            foreach ($image_list as $image) {
                $image_request = mysqli_query($conn, "SELECT author FROM images WHERE id = ".$image);
    
                while ($author = mysqli_fetch_column($image_request)) {
                    if (!in_array($author, $user_array)) {
                        $user_array[] = $author;
                    }
                }
            }
        } catch (Exception) {

        }

        return($user_array);
    }
}

class Diff {
    function time($past_time, $full_date = false): string
    {
        $now    = new \DateTime;
        $ago    = new \DateTime($past_time);
        $diff   = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full_date) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
}

class Setup {
    function create_tables($conn, $sql): bool
    {
        /*
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
            echo "Table users created successfully";
        }

        $sql = "CREATE TABLE IF NOT EXISTS images ( 
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            imagename VARCHAR(255) NOT NULL,
            alt VARCHAR(text) NOT NULL,
            tags VARCHAR(text) NOT NULL,
            author INT(11) NOT NULL,
            last_modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        if ($conn->query($sql) === TRUE) {
            echo "Table images created successfully";
        }

        $sql = "CREATE TABLE IF NOT EXISTS groups (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            group_name VARCHAR(255) NOT NULL,
            image_list VARCHAR(text) NOT NULL,
            last_modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        if ($conn->query($sql) === TRUE) {
            echo "Table groups created successfully";
        }

        $sql = "CREATE TABLE IF NOT EXISTS logs (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            ipaddress VARCHAR(16) NOT NULL,
            action VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        if ($conn->query($sql) === TRUE) {
            echo "Table logs created successfully";
        }

        $sql = "CREATE TABLE IF NOT EXISTS bans (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            ipaddress VARCHAR(16) NOT NULL,
            reason VARCHAR(255) NOT NULL,
            time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            length VARCHAR(255) NOT NULL,
            permanent BOOLEAN NOT NULL DEFAULT FALSE
        )";
        if ($conn->query($sql) === TRUE) {
            echo "Table bans created successfully";
        }

        $sql = "CREATE TABLE IF NOT EXISTS tokens (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            code VARCHAR(59) NOT NULL,
            used BOOLEAN NOT NULL DEFAULT FALSE,
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        if ($conn->query($sql) === TRUE) {
            echo "Table tokens created successfully";
        }
        */
        
        return True;
    }

    function create_usr() {
        if (!is_dir(__DIR__."/../usr")) {
            mkdir("usr");
        }

        if (!is_dir(__DIR__."/../usr/images")) {
            mkdir("usr/images");
        }
        if (!is_dir(__DIR__."/../usr/images/thumbnails")) {
            mkdir("usr/images/thumbnails");
        }
        if (!is_dir(__DIR__."/../usr/images/previews")) {
            mkdir("usr/images/previews");
        }

        if (!is_dir(__DIR__."/../usr/conf")) {
            mkdir("usr/conf");
        }

        if (!is_file(__DIR__."/../usr/conf/conf.json")) {
            $manifest = file_get_contents(__DIR__."/../usr/conf.default.json");
            file_put_contents(__DIR__."/../usr/conf/conf.json", $manifest);
        }
    }
}

class Sanity  {
    function check_json(): array
    {
        $results = array();

        if (!is_file(__DIR__."/../usr/conf/msg.json")) {
            $results[] = array('type'=>'warning', 'message'=>'msg.json is missing');
        }

        if (!is_file(__DIR__."/../usr/conf/conf.json")) {
            if (is_file(__DIR__."/../usr/conf/manifest.json")) {
                $results[] = array('type'=>'critical', 'message'=>'manifest.json is deprecated, please rename it to conf.json');
            } else {
                $results[] = array('type'=>'critical', 'message'=>'conf.json is missing, using conf.default.json instead');
            }
        } else {
            $manifest = json_decode(file_get_contents(__DIR__."/../usr/conf/conf.json"), true);

            if (empty($manifest['user_name']) || $manifest['user_name'] == "[your name]") {
                $results[] = array('type'=>'warning', 'message'=>'conf.json is missing your name');
            }
            if ($manifest['upload']['rename_on_upload']) {
                if (empty($manifest['upload']['rename_to'])) {
                    $results[] = array('type'=>'critical', 'message'=>'conf.json doesnt know what to rename your files to');
                } else {
                    $rename_to      = $manifest['upload']['rename_to'];
                    $rename_rate    = 0;

                    if (str_contains($rename_to, '{{autoinc}}')) $rename_rate = 5;
                    if (str_contains($rename_to, '{{time}}')) $rename_rate = 5;

                    if (str_contains($rename_to, '{{date}}')) $rename_rate += 2;
                    if (str_contains($rename_to, '{{filename}}')) $rename_rate += 2;

                    if (str_contains($rename_to, '{{username}}') || str_contains($rename_to, '{{userid}}')) $rename_rate += 1;

                    if ($rename_rate < 2) {
                        $results[] = array('type'=>'critical', 'message'=>'You will encounter errors when uploading images due to filenames, update your conf.json');
                    } elseif ($rename_rate < 5 && $rename_rate > 2) {
                        $results[] = array('type'=>'warning', 'message'=>'You may encounter errors when uploading images due to filenames, concider modifying your conf.json');
                    }
                }
            }

            if ($manifest['is_testing']) {
                $results[] = array('type'=>'warning', 'message'=>'You are currently in testing mode, errors will be displayed to the user');
            }
        }

        return $results;
    }

    function check_files(): array
    {
        $results = array();

        if (!is_dir("usr/images")) {
            $results[] = array('type'=>'critical', 'message'=>'You need to setup an images folder, follow the guide on the GitHub repo');
        }
        if (!is_dir("usr/images/pfp")) {
            $results[] = array('type'=>'critical', 'message'=>'You need to setup an pfp folder, follow the guide on the GitHub repo');
        }
        if (!is_dir("usr/images/previews")) {
            $results[] = array('type'=>'critical', 'message'=>'You need to setup an previews folder, follow the guide on the GitHub repo');
        }
        if (!is_dir("usr/images/thumbnails")) {
            $results[] = array('type'=>'critical', 'message'=>'You need to setup an thumbnails folder, follow the guide on the GitHub repo');
        }

        return $results;
    }

    function check_version(): array
    {
        $results    = array();
        $app_local  = json_decode(file_get_contents(__DIR__."/app.json"), true);

        $curl_url   = "https://raw.githubusercontent.com/Fluffy-Bean/image-gallery/".$app_local['branch']."/app/settings/manifest.json";
        $curl       = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $curl_url);
        $result     = curl_exec($curl);
        curl_close($curl);

        $app_repo   = json_decode($result, true);

        if ($app_local['version'] < $app_repo['version']) {
            $results[] = array('type'=>'critical', 'message'=>'You are not running the latest version of the app v'.$app_repo['version']);
        } elseif ($app_local['version'] > $app_repo['version']) {
            $results[] = array('type'=>'critical', 'message'=>'You are running a version of the app that is newer than the latest release v'.$app_repo['version']);
        }

        if (PHP_VERSION_ID < 80000) {
            $results[] = array('type'=>'warning', 'message'=>'Your current version of PHP is '.PHP_VERSION.' The reccomended version is 8.0.0 or higher');
        }

        return $results;
    }

    function get_results(): array
    {
        $results = array();

        foreach ($this->check_json() as $result) {
            $results[] = $result;
        }
        foreach ($this->check_files() as $result) {
            $results[] = $result;
        }
        foreach ($this->check_version() as $result) {
            $results[] = $result;
        }

        return $results;
    }
}