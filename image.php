<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gallery</title>
  <link rel="stylesheet" href="css/master.css">
  <link href="https://fonts.googleapis.com/css2?family=Rubik" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@600&amp;display=swap">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@500&amp;display=swap">
</head>
<body>
  <?php
  include("ui/header.php");
  include_once("ui/conn.php");

  // Update toast
  if ($_GET["update"] == "success") {
    echo "<p class='alert alert-high space-bottom-large'>Information updated</p>";
  } elseif ($_GET["update"] == "skip") {
    echo "<p class='alert alert-default space-bottom-large'>No alt, skip</p>";
  }

  // If ID present pull all image data
  if (isset($_GET['id'])) {
    $get_image = "SELECT * FROM swag_table WHERE id = ".$_GET['id'];
    $image_results = mysqli_query($conn, $get_image);
    $image = mysqli_fetch_assoc($image_results);

    // Check if image is avalible
    if (isset($image['imagename'])) {
      // Display image
      $image_path = "images/".$image['imagename'];
      $image_alt = $image['alt'];
    } else {
      // ID not avalible toast
      echo "<p class='alert alert-low space-bottom-large'>Could not find image with ID: ".$_GET['id']."</p>";

      // Replacement "no image" image and description
      $image_path = "assets/no_image.png";
      $image_alt = "No image could be found, sowwy";
    }
  } else {
    // No ID toast
    echo "<p class='alert alert-low space-bottom-large'>No ID present</p>";

    // Replacement "no image" image and description
    $image_path = "assets/no_image.png";
    $image_alt = "No image could be found, sowwy";
  }

  // Get all user details
  if (isset($image['author'])) {
    $get_user = "SELECT * FROM users WHERE id = ".$image['author'];
    $user_results = mysqli_query($conn, $get_user);
    $user = mysqli_fetch_assoc($user_results);
  } else
  ?>

  <div class="image-container">
    <?php
    // Displaying image
    echo "<img class='image' id='".$image['id']."' src='".$image_path."' alt='".$image_alt."'>";
    ?>
  </div>

  <div class="image-description default-window">
    <h2>Description</h2>
    <?php
    // Image Description/Alt
    if (isset($image_alt)) {
      echo "<p>".$image_alt."</p>";
    } else {
      echo "<p>No description provided</p>";
    }
    ?>
  </div>

  <div class="image-detail flex-down default-window">
    <h2>Details</h2>
    <?php
    // Image ID
    if (isset($image['author'])) {
      if (isset($user['username'])) {
        echo "<p>Author: ".$user['username']."</p>";
      } else {
        echo "<p>Author: Deleted User</p>";
      }
    } else {
      echo "<p>Author: No author</p>";
    }

    // Image ID
    echo "<p>ID: ".$image['id']."</p>";

    // File name
    echo "<p>File Name: ".$image['imagename']."</p>";

    // Image Upload date
    echo "<p>Last updated: ".$image['upload']." (+0)</p>";

    // Image resolution
    list($width, $height) = getimagesize($image_path);
    echo "<p>Image resolution: ".$width."x".$height."</p>";

    // Image download
    echo "<a class='btn alert-high space-top' href='images/".$image['imagename']."' download='".$image['imagename']."'><img class='svg' src='assets/icons/download.svg'>Download image</a>";
    ?>
  </div>

  <?php
  // Check if user is admin or the owner of image, if yes, display the edit and delete div
  if (isset($_SESSION['id']) && $image['author'] == $_SESSION['id'] || $_SESSION['id'] == 1) {
    // Deleting image
    if (isset($_POST['delete'])) {
      // Delete from table
      $image_delete_request = "DELETE FROM swag_table WHERE id =".$image['id'];
      $image_delete = mysqli_query($conn,$image_delete_request);

      if ($image_delete) {
        // See if image is in the directory
        if (is_file("images/".$image['imagename'])) {
          unlink("images/".$image['imagename']);
        }
        // Delete thumbnail if exitsts
        if (is_file("images/thumbnails/".$image['imagename'])) {
          unlink("images/thumbnails/".$image['imagename']);
        }
        header("Location:index.php?del=true&id=".$image['id']);
      } else {
        $error = "Could not delete image";
      }
    }
    // Delete form
    echo "<div class='danger-zone flex-down default-window'>";
    echo "<h2>Danger zone</h2>";

    // Image hover details
    echo "<form class='detail' method='POST' enctype='multipart/form-data'>";
    echo "<button class='btn alert-low' type='submit' name='delete' value='".$image['id']."'><img class='svg' src='assets/icons/trash.svg'>Delete image</button>";
    if (isset($error)) {
      echo "<p class='alert alert-fail' id='deleted'>".$error."</p>";
    }
    echo "</form>";

    // Edit image button
    echo "<a class='btn alert-low space-top' href='https://superdupersecteteuploadtest.fluffybean.gay/edit.php?id=".$image['id']."'><img class='svg' src='assets/icons/edit.svg'>Modify image content</a>";
    echo "</div>";
  }
  ?>

  <?php
  include("ui/top.html");
  include("ui/footer.php");
  ?>
</body>
</html>
