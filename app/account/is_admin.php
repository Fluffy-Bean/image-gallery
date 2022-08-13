<?php
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
