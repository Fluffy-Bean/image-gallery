<?php
$conn_ip = "localhost";
$conn_username = "uwu";
$conn_password = "fennec621";
$conn_database = "swag";

$conn = mysqli_connect($conn_ip, $conn_username, $conn_password , $conn_database);
if ($conn->connect_error) {
  // Send notification that connection couldn't be made
}


if (isset($_POST['submit'])) {
  // getting ready forSQL asky asky
  $sql = "UPDATE swag_table SET alt=? WHERE id=?";

  // Checking if databse is doing ok
  if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "si", $param_alt, $param_id);

    // Setting parameters
    $param_alt = $_POST['description'];
    $param_id = $_POST['id'];

    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
      echo "sniffleAdd('Info','Description has been updated successfully! You may need to refresh the page to see the new information.','var(--green)')";
    } else {
      echo "sniffleAdd('Error','An error occured on the servers','var(--red)')";
    }
  }
}
?>
