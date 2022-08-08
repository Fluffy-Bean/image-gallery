/*
  Tags Confirm
*/
if (isset($_POST['tags_confirm']) && $privilaged) {
  // Unset all the variables, needed by flyout
  unset($header, $content, $action);




      header("Location:image.php?id=".$image["id"]."&update=success");
    } else {
      header("Location:image.php?id=".$image["id"]."&update=error");
    }
  }
}
<?php
/*
 |-------------------------------------------------------------
 | Edit Description
 |-------------------------------------------------------------
 | This script took probably over 24hours to write, mostly
 | because of my stupidity. But it (mostly) works now which is
 | good. Reason for all the includes and session_start is due
 | to the need of checking if the person owns the image. If this
 | check is not done, someone could come by and just edit the
 | Jquery code on the front-end and change the image ID. Which
 | isnt too great :p
 |-------------------------------------------------------------
*/
session_start();
// Include server connection
include "../server/conn.php";
// Include required checks
include "get_image_info.php";
include "image_privilage.php";
// Tag cleaning
include "../format/string_to_tags.php";


if (isset($_POST['submit'])) {
  // Get all image info
  $image_array = get_image_info($conn, $_POST['id']);
  // If user owns image or has the ID of 1
  if (image_privilage($image_array['author']) || $_SESSION['id'] == 1) {
    // Clean input
    $tags_string = tag_clean(trim($_POST['input']));

    // getting ready forSQL asky asky
    $sql = "UPDATE swag_table SET tags=? WHERE id=?";

    // Checking if databse is doing ok
    if ($stmt = mysqli_prepare($conn, $sql)) {
      mysqli_stmt_bind_param($stmt, "si", $param_tags, $param_id);

      // Setting parameters
      $param_tags = $tags_string;
      $param_id = $_POST['id'];

      // Attempt to execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
        ?>
        <script>
          sniffleAdd('Success!!!', 'Tags have been modified successfully! You may need to refresh the page to see the new information.', 'var(--green)', '<?php echo $root_dir; ?>assets/icons/check.svg');
          flyoutClose();
        </script>
        <?php
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
        sniffleAdd('Error :c', 'An error occured on the servers', 'var(--red)', '<?php echo $root_dir; ?>assets/icons/cross.svg');
        flyoutClose();
      </script>
      <?php
    }
  } else {
    ?>
    <script>
      sniffleAdd('Denied', 'It seems that you do not have the right permitions to modify tags here.', 'var(--red)', '<?php echo $root_dir; ?>assets/icons/cross.svg');
      flyoutClose();
    </script>
    <?php
  }
}
