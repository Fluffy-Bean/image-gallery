<?php include "ui/required.php"; ?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lynx Gallery</title>

  <!-- Google Fonts -->
  <link rel="stylesheet" href="css/master.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@600&amp;display=swap">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@500&amp;display=swap">

  <!-- JQuery -->
  <script
    src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
    crossorigin="anonymous">
  </script>
</head>
<body>
  <?php
  include "ui/nav.php";

  // Check if user is logged in
  if (loggedin()) {
    // User is logged in
  } else {
    $error = "You must be logged in to upload images";
    header("Location: index.php");
  }

  // Setting up varibles
  $dir = "images/";
  $thumb_dir = $dir."thumbnails/";
  $image_basename = basename($_FILES["image"]["name"]);
  $image_path = $dir.$image_basename;
  $file_type = pathinfo($image_path,PATHINFO_EXTENSION);

  // Continue if no errors
  if (isset($_POST['upload']) && !empty($_FILES["image"]["name"])) {
    if (empty($error)) {
      $allowed_types = array('jpg', 'jpeg', 'png', 'webp');
      if (in_array($file_type, $allowed_types)) {
        // Upload to server
        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
          // Make thumbnail
          $image_thumbnail = new Imagick($image_path);
          $image_thumbnail->resizeImage(300,null,null,1,null);
          $image_thumbnail->writeImage($thumb_dir.$image_basename);

          // Prepare sql for destruction and filtering the sus
          $sql = "INSERT INTO swag_table (imagename, alt, author) VALUES (?, ?, ?)";

          if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind the smelly smelly
            mysqli_stmt_bind_param($stmt, "sss", $param_image_name, $param_alt_text, $param_user_id);

            // Setting up parameters
            $param_image_name = $_FILES["image"]["name"];
            $param_alt_text = $_POST['alt'];
            $param_user_id = $_SESSION["id"];

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
              $success = "Your Image uploaded successfully!";
            } else {
              $error = "Something went fuckywucky, please try later";
            }
          }
        } else {
          $error = "F, Upload failed";
        }
      } else {
        $error = "File uploaded not supported, file types that are allowed include: JPG, JPEG, PNG and WEBP";
      }
    }
  }
  ?>

  <div class="alert-banner">
    <?php
    if (isset($error)) {
      echo notify($error, "low");
    }
    if (isset($success)) {
      echo notify($success, "high");
    }
    ?>
    <script src='scripts/alert.js'></script>
  </div>

  <div class="upload-root default-window">
    <h2 class="space-bottom">Upload image</h2>
    <p>In this world you have 2 choices, to upload a really cute picture of an animal or fursuit, or something other than those 2 things.</p>
    <form class="flex-down between" method="POST" action="upload.php" enctype="multipart/form-data">
        <input class="btn alert-default space-bottom" type="file" name="image" placeholder="select image UwU">
        <input class="btn alert-default space-bottom-large" type="text" name="alt" placeholder="Description/Alt for image">
        <button class="btn alert-high" type="submit" name="upload"><img class="svg" src="assets/icons/upload.svg">Upload Image</button>
    </form>
  </div>

  <?php
  include "ui/top.html";
  include "ui/footer.php";
  ?>
</body>
</html>
