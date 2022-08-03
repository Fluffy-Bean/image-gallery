<?php
/*
  Connect to database

  In the future I want this section to be configurable, but that'll require some work to be done.
  For now it's hard-coded, shouldn't be an issue as most people wont be changing this often anyway
*/
// Setting up connection variables
$conn_ip = "localhost";
$conn_username = "uwu";
$conn_password = "fennec621";
$conn_database = "swag";

$conn = mysqli_connect($conn_ip, $conn_username, $conn_password , $conn_database);
if ($conn->connect_error) {
  // Send notification that connection couldn't be made
}


/*
  Start session

  This is important as most pages use the PHP session and will complain if its not possible to access.
*/
session_start();


/*
  Check which directory user is in

  I don't know if theres a better way of doing this? If there is please let me know
*/
if (is_file("index.php")) {
  $root_dir = "";
} else {
  $root_dir = "../";
}


/*
  Include functions

  Maybe I should put all the functions in this file? Dunno
*/
include $root_dir."ui/functions.php";

/*
  Notification system

  This is the notification system used by the website. Probably a little too much for what its used for
*/
echo "<div id='notify-root' class='notify-root'></div>";
?>
<script>
  console.log(" . . /|/| . . . . . . . \n .. /0 0 \\ . . . . . .. \n (III% . \\________, . . \n .. .\\_, .%###%/ \\'\\,.. \n . . . .||#####| |'\\ \\. \n .. . . ||. . .|/. .\\V. \n . . . .|| . . || . . . \n .. . . ||. . .||. . .. \n . . . .|| . . || . . . \n .. . . ||. . .||. . .. \n . . . .|| . . || . . . \n .. . . ||. . .||. . .. \n . . . .|| . . || . . . \n .. . . ||. . .||. . .. \n . . . .|| . . || . . . \n .. . . ||. . .||. . .. \n . . . .|| . . || . . . \n .. . . ||. . .||. . .. \n . . . .|| . . || . . . \n .. . . ||. . .||. . .. \n . . . .|| . . || . . . \n .. . . ||. . .||. . .. \n . . . cc/ . .cc/ . . .");
</script>
