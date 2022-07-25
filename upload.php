<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Upload</title>
  <link rel="stylesheet" href="css/master.css">
  <link href="https://fonts.googleapis.com/css2?family=Rubik" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@600&amp;display=swap">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@500&amp;display=swap">
</head>
<body>
  <?php
  include("ui/header.php");
  include_once("ui/conn.php");

  if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    if (isset($_POST['upload'])) {
      // Get image name
      $image_name = $_FILES['image']['name'];

      // Get alt text
      if (empty($_POST['alt'])) {
        $get_alt_text = "No description provided";
      } else {
        $get_alt_text = $_POST['alt'];
      }

      // If image present, continue
      if (!empty($image_name)) {
        // Set file path for image upload
        $image_basename = basename($image_name);
        $image_path = "images/".$image_basename;

        // Prepare sql for destruction and filtering the sus
        $sql = $conn->prepare("INSERT INTO swag_table (imagename, alt, author) VALUES (?, ?, ?)");
        $sql->bind_param("sss", $image_name, $get_alt_text, $user_id);

        $user_id = $_SESSION["id"];

        // Uploading image to Table
        $sql->execute();

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

          $success = "Your Image uploaded successfully!";
        } else {
          // Could not move images to folder
          $error = "F, Upload failed";
        }
      } else {
        // No image present
        $error = "No file lol";
      }
    }
  } else {
    $error = "You must be logged in to upload images";
    header("Location: https://superdupersecteteuploadtest.fluffybean.gay");
  }
  ?>

  <div class="upload-root">
    <h2 class="space-bottom">Upload image</h2>
    <p>In this world you have 2 choices, to upload a really cute picture of an animal or fursuit, or something other than those 2 things.</p>
    <form class="flex-down between" method="POST" action="upload.php" enctype="multipart/form-data">
        <input class="btn alert-default space-bottom" type="file" name="image" placeholder="select image UwU">
        <input class="btn alert-default space-bottom-large" type="text" name="alt" placeholder="Description/Alt for image">
        <button class="btn alert-default" type="submit" name="upload"><img class="svg" src="assets/icons/upload.svg">Upload Image</button>
    </form>

    <?php
    if (isset($error)) {
      echo "<p class='alert alert-low space-top'>".$error."</p>";
    }
    if (isset($success)) {
      echo "<p class='alert alert-high space-top'>".$success."</p>";
    }
    ?>
  </div>

  <?php include("ui/footer.php"); ?>
</body>
</html>
