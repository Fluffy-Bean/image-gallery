<?php
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
