<?php
// Attempt database connection
$conn = mysqli_connect("localhost", "uwu", "fennec621", "swag");
// If connecton failed, notify user
if ($conn->connect_error) {
  echo "<p class='alert alert-low'>Could not connect to database</p>";
}
?>