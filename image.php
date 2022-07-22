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

  <?php
  // Attempt database connection
  $conn = mysqli_connect("localhost", "uwu", "password", "swag");
  // If connecton failed, notify user
  if (!$conn) {
    echo "<p class='alert fail'>Could not connect to database</p>";
  }
  ?>

  <div class="image-container">
    <?php
    // Get image ID
    // Getting all image info from table
    $get_image = "SELECT * FROM swag_table WHERE id = ".$_GET['id'];
    $image_results = mysqli_query($conn, $get_image);
    $image = mysqli_fetch_assoc($image_results);

    if ($image['imagename'] != "") {
      $image_name = "images/".$image['imagename'];
      $image_alt = $image['alt'];
    }else{
      $image_name = "assets/no_image.png";
      $image_alt = "No image could be found, sowwy";
    }

    echo "<img class='image' id='".$image['id']."' src='".$image_name."' alt='".$image_alt."'>";

    if (!isset($_GET['id'])) {
      echo "cannot obtain image";
    }
    ?>
  </div>

  <div class="image-description">
    <h2>Description</h2>
    <?php
    echo "<p>".$image_alt."</p>";
    ?>
  </div>

  <div class="image-detail flex-down">
    <h2>Details</h2>
    <?php
    echo "<p>ID: ".$image['id']."</p>";
    echo "<p>File Name: ".$image['imagename']."</p>";
    echo "<p>Upload Date: ".$image['upload']."</p>";
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
      }else{
        // Could not delete from file
        echo "<p class='alert alert-fail' id='deleted'>Error: Coult not delete image</p>";
      }
    }
    ?>
  </div>

  <?php include('ui/footer.php'); ?>
</body>
</html>
