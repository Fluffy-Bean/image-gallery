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
