<?php
/*
 |-------------------------------------------------------------
 | Uploading Images
 |-------------------------------------------------------------
 | gwa gwa
 |-------------------------------------------------------------
*/
session_start();
// Include server connection
include "../server/conn.php";

if (isset($_POST['submit'])) {
  // Root paths
  $dir = "images/";
  $thumb_dir = $dir."thumbnails/";

  // File paths
  $image_basename = basename($_FILES['image']['name']);
  $image_path = $dir.$image_basename;
  $file_type = pathinfo($image_path,PATHINFO_EXTENSION);

  // Allowed file types
  $allowed_types = array('jpg', 'jpeg', 'png', 'webp');
  if (in_array($file_type, $allowed_types)) {
    // Move file to server
    if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
      // Attempt making a thumbnail
      try {
        $image_thumbnail = new Imagick($image_path);
        $image_thumbnail->resizeImage(300,null,null,1,null);
        $image_thumbnail->writeImage($thumb_dir.$image_basename);
      } catch (Exception $e) {
        ?>
        <script>
          sniffleAdd('Gwha!', 'We hit a small roadbump during making of the thumbail. We will continue anyway!', 'var(--black)', '<?php echo $root_dir; ?>assets/icons/bug.svg');
        </script>
        <?php
      }

      // Prepare sql for destruction and filtering the sus
      $sql = "INSERT INTO swag_table (imagename, alt, author) VALUES (?, ?, ?)";

      if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind the smelly smelly
        mysqli_stmt_bind_param($stmt, "sss", $param_image_name, $param_alt_text, $param_user_id);

        // Setting up parameters
        $param_image_name = $_FILES['image']['name'];
        $param_alt_text = $_POST['alt'];
        $param_user_id = $_SESSION['id'];

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
          ?>
          <script>
            sniffleAdd(':3', 'Your Image uploaded successfully!', 'var(--green)', '<?php echo $root_dir; ?>assets/icons/check.svg');
          </script>
          <?php
        } else {
          ?>
          <script>
            sniffleAdd(':c', 'Something went fuckywucky, please try later', 'var(--red)', '<?php echo $root_dir; ?>assets/icons/cross.svg');
          </script>
          <?php
        }
      }
    } else {
      ?>
      <script>
        sniffleAdd('Hmmff', 'Something happened when moving the file to the server. This may just been a 1-off so try again', 'var(--red)', '<?php echo $root_dir; ?>assets/icons/bug.svg');
      </script>
      <?php
    }
  } else {
    ?>
    <script>
      sniffleAdd('Woopsie', 'The file type you are trying to upload is not supported. Supported files include: JPEG, JPG, PNG and WEBP', 'var(--red)', '<?php echo $root_dir; ?>assets/icons/cross.svg');
    </script>
    <?php
  }
}
