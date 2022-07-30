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

  // Include flyout for extra actions
  include("ui/flyout.php");

  /*
    If theres a success in updating the image,
    it'll let the user know
  */
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
  }


  /*
    Check if the user is an admin session id = 1
    Or the owner of the image, image author == session id

    This may not be the best system of doing this, but much better than not having it at all
    I plan on adding an array of privilaged users that user with the id of 1 can modify,
    sort of like a mod/admin list of users
  */
  if (isset($_SESSION['id']) && $image['author'] == $_SESSION['id'] || $_SESSION['id'] == 1) {
    $privilaged = True;
  } else {
    $privilaged = False;
  }


  /*
    Delete flyout

    This goes with the confirm script below, to use flyout, you must include the js script and php function
  */
  if (isset($_POST['delete_flyout']) && $privilaged) {
    $header = "Are you sure?";
    $content = "Deleting this image is pernament, there is no going back after this!!!!!";
    $action = "<form method='POST' enctype='multipart/form-data'>
    <button class='btn alert-low' type='submit' name='delete_confirm' value='".$image['id']."'><img class='svg' src='assets/icons/trash.svg'>Delete image</button>
    </form>";

    flyout($header, $content, $action);
  }
  /*
    Confirm deleting user

    user must be privilaged to do this action this the privilaged == true
  */
  if (isset($_POST['delete_confirm']) && $privilaged) {
    // Unset all the variables, needed by flyout
    unset($header, $content, $action);

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


  /*
    Test flyout button
  */
  if (isset($_POST['test_flyout'])) {
    $header = "Sus";
    $content = "This is a test UwU. You are currently viewing image: ".$_GET['id'];
    $action = "<a class='btn alert-high'>This button does nothing!</a> <a class='btn alert-low space-top-small'>I'm another button, but scawwy</a>";

    flyout($header, $content, $action);
  }
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
    if (isset($image_alt) && !empty($image_alt)) {
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

    // Flyout test button
    ?>
    <form method='POST'>
      <button class='btn alert-high space-top flyout-display' type='submit' name='test_flyout'>Test button</button>
    </form>
  </div>

  <div class="tags-root default-window">
    <h2>Tags</h2>
    <div class="tags flex-left">
      <?php
      function clean($string) {
        $string = str_replace('-', '_', $string);
        $string = preg_replace('/[^A-Za-z0-9\_ ]/', '', $string);
        return preg_replace('/ +/', ' ', $string);
      }
      $tags_string = "This is a test of ta.gs and their s//ystem_of_ignoring ran!!dom characters BUT THIS DOES$$$$$$.NT WORK YET!!!!";
      $tags_string = strtolower($tags_string);
      $tags_string = clean($tags_string);
      $image_tags_array = explode(" ", $tags_string);

      foreach ($image_tags_array as $tag) {
        echo "<p class='tag alert-high'>".$tag."</p>";
      }
      ?>
    </div>
  </div>

  <?php
  // Check if user is admin or the owner of image, if yes, display the edit and delete div
  if ($privilaged) {
    // Danger zone
    echo "<div class='danger-zone flex-down default-window'>
    <h2>Danger zone</h2>";

    // Delete Button
    echo "<form method='POST'>
      <button class='btn alert-low space-top flyout-display' type='submit' name='delete_flyout'><img class='svg' src='assets/icons/trash.svg'>Delete image</button>
    </form>";

    // Edit image button
    echo "<a class='btn alert-low space-top-small' href='https://superdupersecteteuploadtest.fluffybean.gay/edit.php?id=".$image['id']."'><img class='svg' src='assets/icons/edit.svg'>Modify image content</a>";
    echo "</div>";
  }
  ?>

  <?php
  // Must be included with flyout.php
  echo "<script src='scripts/flyout.js'></script>";

  include("ui/top.html");
  include("ui/footer.php");
  ?>
</body>
</html>
