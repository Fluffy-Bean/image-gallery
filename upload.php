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
      // Setting image up for upload
      $image_name = $_FILES['image']['name'];
      if (isset($image_name)) {
        // Set file path for image upload
        $image_basename = basename($image_name);
        $image_path = "images/".$image_basename;

        // Check if errors occured
        if (empty($error)) {
          // Prepare sql for destruction and filtering the sus
          $sql = "INSERT INTO swag_table (imagename, alt, author) VALUES (?, ?, ?)";

          // Can contact database?
          if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind the smelly smelly
            mysqli_stmt_bind_param($stmt, "sss", $param_image_name, $param_alt_text, $param_user_id);

            // Setting up parameters
            $param_image_name = $image_name;
            $param_alt_text = $_POST['alt'];
            $param_user_id = $_SESSION["id"];

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
              // Move files onto server
              if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
                // Make thumbnail
                $image_thumbnail = new Imagick($image_path);
                $image_format = $image_thumbnail->getImageFormat();
                // If image is GIF
                if ($image_format == 'GIF') {
                  $image_thumbnail = $image_thumbnail->coalesceImages();
                }
                // Resize image
                $image_thumbnail->resizeImage(300,null,null,1,null);
                $image_thumbnail->writeImage("images/thumbnails/".$image_basename);

                $success = "Your Image uploaded successfully!";
              } else {
                $error = "F, Upload failed";
              }
            } else {
              $error = "Something went fuckywucky, please try later";
            }
          }
        }
      } else {
        // No image present
        $error = "No file lol";
      }
    }
  } else {
    $error = "You must be logged in to upload images";
    //header("Location: https://superdupersecteteuploadtest.fluffybean.gay");
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

  <?php
  include("ui/top.html");
  include("ui/footer.php");
  ?>
</body>
</html>
