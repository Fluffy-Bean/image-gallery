<?php
/*
 |-------------------------------------------------------------
 | Delete image
 |-------------------------------------------------------------
 | This is the scarries code I written. I hate writing anything
 | like this, please help
 |-------------------------------------------------------------
*/
session_start();
// Include server connection
include "../server/conn.php";
// Include required checks
include "get_image_info.php";
include "image_privilage.php";


if (isset($_POST['submit'])) {
  // Get all image info
  $image_array = get_image_info($conn, $_POST['id']);

  // If user owns image or has the ID of 1
  if (image_privilage($image_array['author']) || $_SESSION['id'] == 1) {
    // Delete from table
    $sql = "DELETE FROM swag_table WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
      mysqli_stmt_bind_param($stmt, "i", $param_id);

      // Setting parameters
      $param_id = $_POST['id'];

      // Attempt to execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
        // See if image is in the directory
        if (is_file("../../images/".$image_array['imagename'])) {
          unlink("../../images/".$image_array['imagename']);
        }
        // Delete thumbnail if exitsts
        if (is_file("../../images/thumbnails/".$image_array['imagename'])) {
          unlink("../../images/thumbnails/".$image_array['imagename']);
        }
        // TP user to the homepage with a success message
        ?>
        <script>
          window.location.replace("index.php?del=true&id=<?php echo $_POST['id']; ?>");
        </script>
        <?php
      } else {
        ?>
        <script>
          sniffleAdd('Oopsie', 'The image failed to delete off of the servers, contact Fluffy about his terrible programming', 'var(--red)', '<?php echo $root_dir; ?>assets/icons/cross.svg');
          flyoutClose();
        </script>
        <?php
      }
    } else {
      ?>
      <script>
        sniffleAdd('Error :c', 'An error occured on the servers', 'var(--red)', '<?php echo $root_dir; ?>assets/icons/cross.svg');
        flyoutClose();
      </script>
      <?php
    }
  } else {
    ?>
    <script>
      sniffleAdd('Denied', 'It seems that you do not have the right permitions to edit this image.', 'var(--red)', '<?php echo $root_dir; ?>assets/icons/cross.svg');
      flyoutClose();
    </script>
    <?php
  }
}
// nice uwu
