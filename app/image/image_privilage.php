<?php
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
