<?php
/*
 |-------------------------------------------------------------
 | Edit Author
 |-------------------------------------------------------------
 | If this has security problems I'm so fucked
 |-------------------------------------------------------------
*/
session_start();
// Include server connection
include "../server/conn.php";

if (isset($_POST['submit'])) {
  // If user has the ID of 1
  if ($_SESSION['id'] == 1) {
    // getting ready forSQL asky asky
    $sql = "UPDATE swag_table SET author=? WHERE id=?";

    // Checking if databse is doing ok
    if ($stmt = mysqli_prepare($conn, $sql)) {
      mysqli_stmt_bind_param($stmt, "si", $param_author, $param_id);

      // Setting parameters
      $param_author = $_POST['input'];
      $param_id = $_POST["id"];

      // Attempt to execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
        ?>
        <script>
          sniffleAdd('Success!!!', 'The Author has been updated successfully! You may need to refresh the page to see the new information.', 'var(--green)', 'assets/icons/check.svg');
          flyoutClose();
        </script>
        <?php
      } else {
        ?>
        <script>
          sniffleAdd('Oopsie....', 'An error occured on the servers', 'var(--red)', 'assets/icons/cross.svg');
          flyoutClose();
        </script>
        <?php
      }
    }
  } else {
    ?>
    <script>
      sniffleAdd('Denied', 'Sussy wussy.', 'var(--red)', 'assets/icons/cross.svg');
      flyoutClose();
    </script>
    <?php
  }
}
