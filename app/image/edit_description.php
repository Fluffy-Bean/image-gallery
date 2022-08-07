<?php
/*
 |-------------------------------------------------------------
 | Check if user has edit permitions
 |-------------------------------------------------------------
 | This is repetetive, but I'm worried people will find a way
 | around the front-end protection (if any), so best to do a
 | second check.
 |
 | I also want to add that this is probably a very long and
 | poor way of doing the check, so I'm sorry for anyone who has
 | the unfortunate task of reading this code.
 |-------------------------------------------------------------
*/
$conn_ip = "localhost";
$conn_username = "uwu";
$conn_password = "fennec621";
$conn_database = "swag";

$conn = mysqli_connect($conn_ip, $conn_username, $conn_password , $conn_database);
if ($conn->connect_error) {
  // Send notification that connection couldn't be made
}
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
function get_image_info($conn, $id) {
  // Setting SQL query
  $sql = "SELECT * FROM swag_table WHERE id = ".$id;
  // Getting results
  $query = mysqli_query($conn, $sql);
  // Fetching associated info
  $image_array = mysqli_fetch_assoc($query);

  return($image_array);
}


// Get image ID to search up
$image_post_id = $_POST['image_id'];
$image_info = get_image_info($conn, $image_post_id);

if (isset($_POST['description'])) {
  // If privilaged, continue
  if (image_privilage($image_info['id'])) {
    // getting ready forSQL asky asky
    $sql = "UPDATE swag_table SET alt=? WHERE id=?";

    // Checking if databse is doing ok
    if ($stmt = mysqli_prepare($conn, $sql)) {
      mysqli_stmt_bind_param($stmt, "si", $param_alt, $param_id);

      // Setting parameters
      $param_alt = $_POST['description'];
      $param_id = $image_post_id;

      // Attempt to execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
        echo "<script>sniffleAdd('Info','Description has been updated successfully! You may need to refresh the page to see the new information.','var(--green)')</script>";
      } else {
        echo "<script>sniffleAdd('Error','An error occured on the servers','var(--red)')</script>";
      }
    }
  } else {
    echo "<script>sniffleAdd('Error','You do not have access to this','var(--red)')</script>";
  }
}
