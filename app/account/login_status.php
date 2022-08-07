<?php
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
