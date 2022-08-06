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
  /*
    This is a little secrete for the ones who care, nothing important
  */
  console.log(" . . /|/| . . . . . . . \n .. /0 0 \\ . . . . . .. \n (III% . \\________, . . \n .. .\\_, .%###%/ \\'\\,.. \n . . . .||#####| |'\\ \\. \n .. . . ||. . .|/. .\\V. \n . . . .|| . . || . . . \n .. . . ||. . .||. . .. \n . . . .|| . . || . . . \n .. . . ||. . .||. . .. \n . . . .|| . . || . . . \n .. . . ||. . .||. . .. \n . . . .|| . . || . . . \n .. . . ||. . .||. . .. \n . . . .|| . . || . . . \n .. . . ||. . .||. . .. \n . . . .|| . . || . . . \n .. . . ||. . .||. . .. \n . . . .|| . . || . . . \n .. . . ||. . .||. . .. \n . . . .|| . . || . . . \n .. . . ||. . .||. . .. \n . . . cc/ . .cc/ . . .");

  /*
    Gets Querys from the URL the user is at
    Used by Sniffle to display notificaions
  */
  const params = new Proxy(new URLSearchParams(window.location.search), {
    get: (searchParams, prop) => searchParams.get(prop),
  });
</script>

<!-- Used by Sniffle to add Notifications, div can be displayed all time as it has no width or height initself -->
<div id='sniffle' class='sniffle'></div>

<!-- Div for information flyouts, controlled by Flyout.js -->
<div id='flyoutDim' class='flyout-dim'></div>
<div id='flyoutRoot' class='flyout flex-down'>
  <p id='flyoutHeader' class='flyout-header space-bottom'>Header</p>
  <p id='flyoutDescription' class='flyout-description space-bottom'>Description</p>
  <div id='flyoutActionbox' class='flyout-actionbox space-bottom-small'></div>
  <button onclick='flyoutClose()' class='btn alert-default'>Close</button>
</div>
