<?php
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
  Clean up long text input and turn into an array for tags

  Returns clean string of words with equal white space between it
*/
function tag_clean($string) {
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


/*
  Check if user is loggedin

  Returns True if user is
  Returns False if user is NOT
*/
function loggedin() {
  if (isset($_SESSION["loggedin"]) == true && $_SESSION["loggedin"] == true) {
    return True;
  } else {
    return False;
  }
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


/*
  Takes in max 3 min 2 inputs:

  Header is displayed ontop of the flyout
    Takes in text input

  Description is displayed in the center of the flyout
    Takes in text input

  Action is displayed above the cancel button
    Takes in any HTML input

  Returns nothing but must include:
  <script src='scripts/flyout.js'></script>
  At the bottom of the HTML document
*/
function flyout($header, $content, $action) {
  // Used for background dimming
  echo "<div class='flyout-dim'></div>";
  // Div Start
  echo "<div class='flyout flex-down default-window between'>";

  // Header for the flyout, must be included
  if (isset($header) && !empty($header)) {
    echo "<h2 class='space-bottom'>".$header."</h2>";
  } else {
    echo "<h2 class='space-bottom'>Header</h2>";
  }

  // Flyout content, must be included!!!!
  if (isset($content) && !empty($content)) {
    echo "<p class='space-bottom'>".$content."</p>";
  } else {
    echo "<h2 class='space-bottom'>Description</h2>";
  }

  // Flyout button, not required so must need more information when added
  if (isset($action) && !empty($action)) {
    echo $action;
  }

  // Exit button + Div End
  echo "<button class='btn alert-default space-top flyout-close'>Close</button>
  </div>";
}
?>
