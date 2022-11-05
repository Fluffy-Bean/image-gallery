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