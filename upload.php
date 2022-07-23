<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>UwU</title>
  <link rel="stylesheet" href="css/master.css">
  <link href="https://fonts.googleapis.com/css2?family=Rubik" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@600&amp;display=swap">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@500&amp;display=swap">
</head>
<body>
  <?php include("ui/header.php"); ?>

  <div class="upload-root">
    <h2 class="space-bottom">Upload image</h2>
    <form class="flex-down between" method="POST" action="upload.php" enctype="multipart/form-data">
        <input class="btn alert-default space-bottom" type="file" name="image" placeholder="select image UwU">
        <input class="btn alert-default space-bottom" type="text" name="alt" placeholder="Description/Alt for image">
        <button class="btn alert-default" type="submit" name="upload">Upload Image</button>
    </form>
    <?php
    if ($_GET["r"] == "success") {
      // Image uploaded
      echo "<p class='alert alert-high space-top'>Your Image uploaded successfully!</p>";
    } elseif ($_GET["r"] == "fail") {
      // Upload failed
      echo "<p class='alert alert-low space-top'>F, Upload failed</p>";
    } elseif ($_GET["r"] == "nofile") {
      // No file was present
      echo "<p class='alert alert-default space-top'>No file lol</p>";
    } else {
      // echo "<p class='alert alert-default'>Select an image to upload</p>";
    }
    ?>
  </div>

  <?php
  include_once("ui/conn.php");


  if (isset($_POST['upload'])) {
    // Get image name
    $get_image_name = $_FILES['image']['name'];

    // Get alt text
    if (empty($_POST['alt'])) {
      $get_alt_text = "No description provided";
    } else {
      $get_alt_text = $_POST['alt'];
    }


    // If image present, continue
    if (!empty($get_image_name)) {
      // Set file path for image upload
      $image_basename = basename($get_image_name);
      $image_path = "images/".$image_basename;
      $sql = "INSERT INTO swag_table (imagename, alt) VALUES ('$get_image_name','$get_alt_text')";


      // Uploading image to Table
      mysqli_query($conn, $sql);


      // Checking if image uploaded
      if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
        // Make thumbnail
        $image_thumbnail = new Imagick($image_path);
        // Get image format
        $image_format = $image_thumbnail->getImageFormat();
        // If image is gif
        if ($image_format == 'GIF') {
          $image_thumbnail = $image_thumbnail->coalesceImages();
        }
        // Resize image
        $image_thumbnail->resizeImage(300,null,null,1,null);
        // Save image
        $image_thumbnail->writeImage("images/thumbnails/".$image_basename);

        header("Location:upload.php?r=success");
      } else {
        // Could not move images to folder
        header("Location:upload.php?r=fail");
      }
    } else {
      // No image present
      header("Location:upload.php?r=nofile");
    }
  }
  ?>

  <?php include("ui/footer.php"); ?>
</body>
</html>
