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
  <?php
  include("ui/header.php");
  include_once("ui/conn.php");

  // Get image ID
  // Getting all image info from table
  $get_image = "SELECT * FROM swag_table WHERE id = ".$_GET['id'];
  $image_results = mysqli_query($conn, $get_image);
  $image = mysqli_fetch_assoc($image_results);

  if (!isset($_GET['id'])) {
    echo "<p class='alert alert-low'>No ID present</p>";

    $image_path = "assets/no_image.png";
    $image_alt = "No image could be found, sowwy";
  } elseif (empty($image['imagename'])) {
    echo "<p class='alert alert-low'>Could not find image with ID: ".$_GET['id']."</p>";

    $image_path = "assets/no_image.png";
    $image_alt = "No image could be found, sowwy";
  } else {
    $image_path = "images/".$image['imagename'];
    $image_alt = $image['alt'];
  }
  ?>

  <div class="image-container">
    <?php echo "<img class='image' id='".$image['id']."' src='".$image_path."' alt='".$image_alt."'>"; ?>
  </div>

  <div class="image-description">
    <h2>Description</h2>
    <?php
    // Image Description/Alt
    if (empty($image_alt)) {
      echo "<p>Image uploaded prior to description being added</p>";
    }else{
      echo "<p>".$image_alt."</p>";
    }

    ?>
  </div>

  <div class="image-detail flex-down">
    <h2>Details</h2>
    <?php
    // Image ID
    echo "<p>ID: ".$image['id']."</p>";

    // File name
    echo "<p>File Name: ".$image['imagename']."</p>";

    // Image Upload date
    echo "<p>Upload Date: ".$image['upload']."</p>";

    // Image resolution
    list($width, $height) = getimagesize($image_path);
    echo "<p>Image resolution: ".$width."x".$height."</p>";
    ?>
  </div>

  <div class="danger-zone flex-down">
    <h2>Danger zone</h2>
    <?php
    // Image hover details
    echo "<form class='detail' method='POST' enctype='multipart/form-data'>";
    echo "<button class='btn alert-low' type='submit' name='delete' value='".$image['id']."'>Delete image</button>";
    echo "</form>";

    // Check if query is set
    if (isset($_POST['delete'])) {
      // Try deleting image
      if(unlink("images/".$image['imagename'])) {
        // If deleted, delete from Table
        $image_delete_request = "DELETE FROM swag_table WHERE id =".$image['id'];
        $image_delete = mysqli_query($conn,$image_delete_request);
        if ($image_delete) {
          // Deleted image
          header("Location:index.php?del=true&id=".$image['id']);
        }
      } else {
        // Could not delete from file
        echo "<p class='alert alert-fail' id='deleted'>Error: Coult not delete image</p>";
      }
    }
    ?>
  </div>

  <?php include("ui/footer.php"); ?>
</body>
</html>
