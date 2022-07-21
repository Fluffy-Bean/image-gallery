<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>UwU</title>
  <link rel="stylesheet" href="css/master.css">
  <link href="https://fonts.googleapis.com/css2?family=Rubik" rel="stylesheet">
</head>
<body>
  <?php include('ui/header.php'); ?>

  <div class="upload-root">
    <form class="flex-down between" method="POST" action="upload.php" enctype="multipart/form-data">
        <input class="btn alert-default" type="file" name="image" placeholder="select image UwU">
        <hr class="space-small">
        <input class="btn" type="text" name="alt" placeholder="Description/Alt for image">
        <hr class="space-small">
        <button class="btn alert-default" type="submit" name="upload">Upload Image</button>
        <hr class="space-small">
    </form>
    <?php
    if ($_GET["r"] == "success") {
      echo "<p class='alert alert-high'>Your Image uploaded successfully!</p>";
    }elseif ($_GET["r"] == "fail") {
      echo "<p class='alert alert-low'>F, Upload failed</p>";
    }elseif ($_GET["r"] == "nofile") {
      echo "<p class='alert alert-default'>No file lol</p>";
    }else{
      // echo "<p class='alert alert-default'>Select an image to upload</p>";
    }
    ?>
  </div>

  <?php
  // Attempt database connection
  $conn = mysqli_connect("localhost", "uwu", "password", "swag");
  // If connecton failed, notify user
  if (!$conn) {
    echo "<p class='alert fail'>Could not connect to database</p>";
  }


  if (isset($_POST['upload'])) {
    // Get image name
    $get_image_name = $_FILES['image']['name'];

    // Get alt text
    if ($_POST['alt'] != "") {
      $get_alt_text = $_POST['alt'];
    }else{
      $get_alt_text = "No description provided";
    }


    // If image present, continue
    if ($get_image_name != "") {
      // Set file path for image upload
      $image_path = "images/".basename($get_image_name);
      $sql = "INSERT INTO swag_table (imagename, alt) VALUES ('$get_image_name','$get_alt_text')";


      // Uploading image to Table
      mysqli_query($conn, $sql);


      // Checking if image uploaded
      if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
        header("Location:upload.php?r=success");
      }else{
        header("Location:upload.php?r=fail");
      }
    }else{
      // No image present
      header("Location:upload.php?r=nofile");
    }
  }
  ?>

  <?php include('ui/footer.php'); ?>
</body>
</html>
