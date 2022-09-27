<?php
session_start();
// Include server connection
include dirname(__DIR__)."/server/conn.php";
include dirname(__DIR__)."/app.php";

use App\Account;
use App\Image;
use App\Make;

$user_info = new Account();
$image_info = new Image();
$make_stuff = new Make();

$user_ip = $user_info->get_ip();

/*
 |-------------------------------------------------------------
 | Delete image
 |-------------------------------------------------------------
 | This is the scarries code I written. I hate writing anything
 | like this, please help
 |-------------------------------------------------------------
*/
if (isset($_POST['submit_delete'])) {
  // Get all image info
  $image_array = $image_info->get_image_info($conn, $_POST['id']);

  // If user owns image or has the ID of 1
  if ($image_info->image_privilage($image_array['author']) || $user_info->is_admin($conn, $_SESSION['id'])) {
    // Delete from table
    $sql = "DELETE FROM images WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
      mysqli_stmt_bind_param($stmt, "i", $_POST['id']);

      // Attempt to execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
        // See if image is in the directory
        if (is_file(dirname(__DIR__)."/images/".$image_array['imagename'])) {
          unlink(dirname(__DIR__)."/images/".$image_array['imagename']);
        }
        // Delete thumbnail if exitsts
        if (is_file(dirname(__DIR__)."/images/thumbnails/".$image_array['imagename'])) {
          unlink(dirname(__DIR__)."/images/thumbnails/".$image_array['imagename']);
        }
        // Delete preview if exitsts
        if (is_file(dirname(__DIR__)."/images/previews/".$image_array['imagename'])) {
          unlink(dirname(__DIR__)."/images/previews/".$image_array['imagename']);
        }
        // TP user to the homepage with a success message
        mysqli_query($conn,"INSERT INTO logs (ipaddress, action) VALUES('$user_ip','Deleted image ".$_POST['id']."')");
        $_SESSION['del'] = $_POST['id'];
        ?>
        <script>
          window.location.replace("index.php");
        </script>
        <?php
      } else {
        ?>
        <script>
          sniffleAdd('Oopsie', 'The image failed to delete off of the servers, contact Fluffy about his terrible programming', 'var(--warning)', 'assets/icons/cross.svg');
          flyoutClose();
        </script>
        <?php
      }
    } else {
      ?>
      <script>
        sniffleAdd('Error :c', 'An error occured on the servers', 'var(--warning)', 'assets/icons/cross.svg');
        flyoutClose();
      </script>
      <?php
    }
  } else {
    ?>
    <script>
      sniffleAdd('Denied', 'It seems that you do not have the right permitions to edit this image.', 'var(--warning)', 'assets/icons/cross.svg');
      flyoutClose();
    </script>
    <?php
  }
}


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
if (isset($_POST['submit_description'])) {
  // Get all image info
  $image_array = $image_info->get_image_info($conn, $_POST['id']);
  // If user owns image or has the ID of 1
  if ($image_info->image_privilage($image_array['author']) || $user_info->is_admin($conn, $_SESSION['id'])) {
    // getting ready forSQL asky asky
    $sql = "UPDATE images SET alt=? WHERE id=?";

    // Checking if databse is doing ok
    if ($stmt = mysqli_prepare($conn, $sql)) {
      mysqli_stmt_bind_param($stmt, "si", $param_alt, $param_id);

      // Setting parameters
      $param_alt = $_POST['input'];
      $param_id = $_POST['id'];

      // Attempt to execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
        ?>
        <script>
          sniffleAdd('Success!!!', 'Description has been updated successfully! You may need to refresh the page to see the new information.', 'var(--success)', 'assets/icons/check.svg');
          flyoutClose();
        </script>
        <?php
      } else {
        ?>
        <script>
          sniffleAdd('Error :c', 'An error occured on the servers', 'var(--warning)', 'assets/icons/cross.svg');
          flyoutClose();
        </script>
        <?php
      }
    } else {
      ?>
      <script>
        sniffleAdd('Error :c', 'An error occured on the servers', 'var(--warning)', 'assets/icons/cross.svg');
        flyoutClose();
      </script>
      <?php
    }
  } else {
    ?>
    <script>
      sniffleAdd('Denied', 'It seems that you do not have the right permitions to edit this image.', 'var(--warning)', 'assets/icons/cross.svg');
      flyoutClose();
    </script>
    <?php
  }
}


/*
 |-------------------------------------------------------------
 | Edit Tags
 |-------------------------------------------------------------
 | This is so garbage lmfao
 |-------------------------------------------------------------
*/
if (isset($_POST['submit_tags'])) {
  // Get all image info
  $image_array = $image_info->get_image_info($conn, $_POST['id']);
  // If user owns image or has the ID of 1
  if ($image_info->image_privilage($image_array['author']) || $user_info->is_admin($conn, $_SESSION['id'])) {
    // Clean input
    $tags_string = $make_stuff->tags(trim($_POST['input']));

    // getting ready forSQL asky asky
    $sql = "UPDATE images SET tags=? WHERE id=?";

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
          sniffleAdd('Success!!!', 'Tags have been modified successfully! You may need to refresh the page to see the new information.', 'var(--success)', 'assets/icons/check.svg');
          flyoutClose();
        </script>
        <?php
      } else {
        ?>
        <script>
          sniffleAdd('Error :c', 'An error occured on the servers', 'var(--warning)', 'assets/icons/cross.svg');
          flyoutClose();
        </script>
        <?php
      }
    } else {
      ?>
      <script>
        sniffleAdd('Error :c', 'An error occured on the servers', 'var(--warning)', 'assets/icons/cross.svg');
        flyoutClose();
      </script>
      <?php
    }
  } else {
    ?>
    <script>
      sniffleAdd('Denied', 'It seems that you do not have the right permitions to modify tags here.', 'var(--warning)', 'assets/icons/cross.svg');
      flyoutClose();
    </script>
    <?php
  }
}


/*
 |-------------------------------------------------------------
 | Edit Author
 |-------------------------------------------------------------
 | If this has security problems I'm so fucked
 |-------------------------------------------------------------
*/
if (isset($_POST['submit_author'])) {
  // If user has the ID of 1
  if ($user_info->is_admin($conn, $_SESSION['id'])) {
    // getting ready forSQL asky asky
    $sql = "UPDATE images SET author=? WHERE id=?";

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
          sniffleAdd('Success!!!', 'The Author has been updated successfully! You may need to refresh the page to see the new information.', 'var(--success)', 'assets/icons/check.svg');
          flyoutClose();
        </script>
        <?php
      } else {
        ?>
        <script>
          sniffleAdd('Oopsie....', 'An error occured on the servers', 'var(--warning)', 'assets/icons/cross.svg');
          flyoutClose();
        </script>
        <?php
      }
    }
  } else {
    ?>
    <script>
      sniffleAdd('Denied', 'Sussy wussy.', 'var(--warning)', 'assets/icons/cross.svg');
      flyoutClose();
    </script>
    <?php
  }
}