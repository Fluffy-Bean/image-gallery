<?php
namespace App;

class Make {
    /*
    |-------------------------------------------------------------
    | Create Thumbnails
    |-------------------------------------------------------------
    | Default resolution for a preview image is 300px (max-width)
    | ** Not yet implemented **
    |-------------------------------------------------------------
    */
    function thumbnail($image_path, $thumbnail_path, $resolution) {
        try {
            $thumbnail = new \Imagick($image_path);
            $thumbnail->resizeImage($resolution,null,null,1,null);
            $thumbnail->writeImage($thumbnail_path);

            return "success";
        } catch (\Exception $e) {
            return $e;
        }
    }

    /*
    Clean up long text input and turn into an array for tags

    Returns clean string of words with equal white space between it
    */
    function tags($string) {
        // Replace hyphens
        $string = str_replace('-', '_', $string);
        // Regex
        $string = preg_replace('/[^A-Za-z0-9\_ ]/', '', $string);
        // Change to lowercase
        $string = strtolower($string);
        // Removing extra spaces
        $string = preg_replace('/ +/', ' ', $string);
    
        return $string;
    }
}

class Account {
    /*
    Check if user is loggedin

    Returns True if user is
    Returns False if user is NOT
    */
    function is_loggedin() {
        if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
            return True;
        } else {
            return False;
        }
    }
    /*
    Get full user info from database

    Returns array with user info
    */
    function get_user_info($conn, $id) {
        // Setting SQL query
        $sql = "SELECT * FROM users WHERE id = ".$id;
        // Getting results
        $query = mysqli_query($conn, $sql);
        // Fetching associated info
        $user_array = mysqli_fetch_assoc($query);
    
        return($user_array);
    }
    /*
    Check if user is admin

    Returns True if user is privilaged
    Returns False if user is NOT privilaged
    */
    function is_admin($id) {
        if (isset($id) || !empty($id)) {
            if ($id == 1) {
                return True;
            } else {
                return False;
            }
        } else {
            return False;
        }
    }
}

class Image {
    /*
    Get full image info from database

    Returns array with image info
    */
    function get_image_info($conn, $id) {
        // Setting SQL query
        $sql = "SELECT * FROM swag_table WHERE id = ".$id;
        // Getting results
        $query = mysqli_query($conn, $sql);
        // Fetching associated info
        $image_array = mysqli_fetch_assoc($query);
    
        return($image_array);
    }
    /*
    Check if user is image owner

    Returns True if user is privilaged
    Returns False if user is NOT privilaged
    */
    function image_privilage($id) {
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
