<?php
/*
  Connect to database

  Dunno what else to put here lol
*/
$conn_ip = "192.168.0.79:3306";
$conn_username = "uwu";
$conn_password = "fennec621";
$conn_database = "gallery";

/*
  echo $_SERVER['DOCUMENT_ROOT'].dirname($_SERVER['SCRIPT_NAME']);
  echo $_SERVER['PHP_SELF'];
*/

$conn = mysqli_connect($conn_ip, $conn_username, $conn_password , $conn_database);
if ($conn->connect_error) {
  ?>
    <script>
      sniffleAdd('Error','Could not make a connection to the server, please try again later','var(--red)','assets/icons/warning.svg');
    </script>
  <?php
}

session_start();
