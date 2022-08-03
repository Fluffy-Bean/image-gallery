<?php
// Used for background dimming
echo "<div class='flyout-dim'></div>";
// Div Start
echo "<div class='flyout flex-down default-window between'>";


// Header for the flyout, must be included
echo "<h2 class='space-bottom'>Header</h2>";
// Flyout content, must be included!!!!
echo "<p class='space-bottom'>Description</p>";
// Flyout button, not required so must need more information when added
echo $action;
// Exit button + Div End
echo "<button class='btn alert-default space-top flyout-close'>Close</button>
</div>";
// Must be included with flyout.php
echo "<script src='scripts/flyout.js'></script>";
?>

<?php
include "ui/required.php";

/*
  Get image ID

  Image ID should be written in the URL of the page as ?id=69
  If ID cannot be obtained, give error.      ID going here ^^
*/
if (isset($_GET['id'])) {
  // Get all image info
  $image = get_image_info($conn, $_GET['id']);

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
  //echo "<p class='alert alert-low space-bottom-large'>No ID present</p>";

  // Replacement "no image" image and description
  //$image_path = "assets/no_image.png";
  //$image_alt = "No image could be found, sowwy";
}


/*
  Get all user details

  This gets the user info from the image
*/
if (isset($image['author'])) {
  $user = get_user_info($conn, $image['author']);
}

/*
  Check user privilge

  This requires the user to be logged in or an admin
*/
if (image_privilage($image['author']) || is_admin($_SESSION['id'])) {
  $privilaged = True;
} else {
  $privilaged = False;
}
?>

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
  <?php include"ui/nav.php"; ?>

  <div class="alert-banner">
    <?php
    /*
      If theres a success in updating the image,
      it'll let the user know
    */
    if ($_GET["update"] == "success") {
      echo notify("Information updated", "high");
    } elseif ($_GET["update"] == "error") {
      echo notify("Something went fuckywucky, please try later", "default");
    }
    if ($_GET["del"] == "fail") {
      echo notify("Failed to delete image", "low");
    }
    ?>
    <p class='alert alert-high space-bottom-large' onclick='closeAlert(this)'></p>
    <script src='scripts/alert.js'></script>
  </div>

  <?php
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
      header("Location: image.php?id=".$image['id']."&del=fail>");
    }
  }

  /*
    Description edit
  */
  if (isset($_POST['description_flyout']) && $privilaged) {
    $header = "Enter new Description/Alt";
    $content = "Whatcha gonna put in there 👀";
    $action = "<form class='flex-down between' method='POST' enctype='multipart/form-data'>
      <input class='btn alert-default space-bottom' type='text' name='update_alt' placeholder='Description/Alt for image'>
      <button class='btn alert-low' type='submit' name='description_confirm' value='".$image["id"]."'><img class='svg' src='assets/icons/edit.svg'>Update information</button>
    </form>";

    flyout($header, $content, $action);
  }
  /*
    Description confirm
  */
  if (isset($_POST['description_confirm']) && $privilaged) {
    // Unset all the variables, needed by flyout
    unset($header, $content, $action);

    // getting ready forSQL asky asky
    $sql = "UPDATE swag_table SET alt=? WHERE id=?";

    // Checking if databse is doing ok
    if ($stmt = mysqli_prepare($conn, $sql)) {
      mysqli_stmt_bind_param($stmt, "si", $param_alt, $param_id);

      // Setting parameters
      $param_alt = $_POST['update_alt'];
      $param_id = $image["id"];

      // Attempt to execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
        header("Location:image.php?id=".$image["id"]."&update=success");
      } else {
        header("Location:image.php?id=".$image["id"]."&update=error");
      }
    }
  }

  /*
    Adding tags
  */
  if (isset($_POST['tags_flyout']) && $privilaged) {
    $header = "Tags";
    $content = "Add image tags here! This is still being tested so your tags may be removed later on. Tags ONLY accept, letters, numbers and underscores. Hyphens will be stitched to underscores and spaces will seperate the different tags from eachother.";
    $action = "<form class='flex-down between' method='POST' enctype='multipart/form-data'>
      <input class='btn alert-default space-bottom' type='text' name='add_tags' placeholder='Tags are seperated by spaces'>
      <button class='btn alert-low' type='submit' name='tags_confirm' value='".$image["id"]."'><img class='svg' src='assets/icons/edit.svg'>Add tags</button>
    </form>";

    flyout($header, $content, $action);
  }
  /*
    Tags Confirm
  */
  if (isset($_POST['tags_confirm']) && $privilaged) {
    // Unset all the variables, needed by flyout
    unset($header, $content, $action);

    // Clean tags before adding
    function clean($string) {
      // Change to lowercase
      $string = strtolower($string);
      // Replace hyphens
      $string = str_replace('-', '_', $string);
      // Regex
      $string = preg_replace('/[^A-Za-z0-9\_ ]/', '', $string);
      // Return string
      return preg_replace('/ +/', ' ', $string);
    }

    // Clean input
    $tags_string = tag_clean(trim($_POST['add_tags']));

    // getting ready forSQL asky asky
    $sql = "UPDATE swag_table SET tags=? WHERE id=?";

    // Checking if databse is doing ok
    if ($stmt = mysqli_prepare($conn, $sql)) {
      mysqli_stmt_bind_param($stmt, "si", $param_tags, $param_id);

      // Setting parameters
      $param_tags = $tags_string;
      $param_id = $image["id"];

      // Attempt to execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
        header("Location:image.php?id=".$image["id"]."&update=success");
      } else {
        header("Location:image.php?id=".$image["id"]."&update=error");
      }
    }
  }

  /*
    Description athor
  */
  if (isset($_POST['author_flyout']) && is_admin($_SESSION['id'])) {
    $header = "Who owns the image?????";
    $content = "Enter ID of image owner";
    $action = "<form class='flex-down between' method='POST' enctype='multipart/form-data'>
      <input class='btn alert-default space-bottom' type='text' name='update_author' placeholder='New user ID'>
      <button class='btn alert-low' type='submit' name='author_confirm' value='".$image["id"]."'><img class='svg' src='assets/icons/edit.svg'>Update information</button>
    </form>";

    flyout($header, $content, $action);
  }
  /*
    Author confirm
  */
  if (isset($_POST['author_confirm']) && is_admin($_SESSION['id'])) {
    // Unset all the variables, needed by flyout
    unset($header, $content, $action);

    // getting ready forSQL asky asky
    $sql = "UPDATE swag_table SET author=? WHERE id=?";

    // Checking if databse is doing ok
    if ($stmt = mysqli_prepare($conn, $sql)) {
      mysqli_stmt_bind_param($stmt, "si", $param_author, $param_id);

      // Setting parameters
      $param_author = $_POST['update_author'];
      $param_id = $image["id"];

      // Attempt to execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
        header("Location:image.php?id=".$image["id"]."&update=success");
      } else {
        header("Location:image.php?id=".$image["id"]."&update=error");
      }
    }
  }
  ?>

  <div class="image-container space-bottom-large">
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
    if (strlen($image['imagename']) > 30) {
      echo "<p>File Name: ".trim(substr($image['imagename'], 0, 30))."...</p>";
    } else {
      echo "<p>File Name: ".$image['imagename']."</p>";
    }

    // File extention
    echo "<p>File Type: ".pathinfo($image['imagename'], PATHINFO_EXTENSION)."</p>";

    // Image resolution
    list($width, $height) = getimagesize($image_path);
    echo "<p>Image resolution: ".$width."x".$height."</p>";

    // Image Upload date
    echo "<p>Last updated: ".$image['upload']." (+0)</p>";

    // Image download
    echo "<a class='btn alert-high space-top' href='images/".$image['imagename']."' download='".$image['imagename']."'><img class='svg' src='assets/icons/download.svg'>Download image</a>";
    ?>
  </div>

  <div class="tags-root default-window">
    <h2>Tags</h2>
    <div class="tags flex-left">
      <?php
      // Get image tags
      if (isset($image['tags']) && !empty($image['tags'])) {
        $image_tags_array = explode(" ", $image['tags']);
        foreach ($image_tags_array as $tag) {
          echo "<p class='tag alert-high'>".$tag."</p>";
        }
      } else {
        echo "<p>No tags present</p>";
      }
      ?>
    </div>
  </div>

  <?php
  /*
    Check if user is privilaged,
    If yes, grant them access to the Danger zone, or "the place that can fuck things up"

    Checking is done prior to here
  */
  if ($privilaged) {
    // Danger zone
    echo "<div class='danger-zone flex-down default-window'>
    <h2>Danger zone</h2>";

    // Delete
    echo "<form method='POST'>
      <button class='btn alert-low flyout-display' type='submit' name='delete_flyout'><img class='svg' src='assets/icons/trash.svg'>Delete image</button>
    </form>";

    // Edit description
    echo "<form method='POST'>
      <button class='btn alert-low space-top-small flyout-display' type='submit' name='description_flyout'><img class='svg' src='assets/icons/edit.svg'>Edit description</button>
    </form>";

    // Edit tags
    echo "<form method='POST'>
      <button class='btn alert-low space-top-small flyout-display' type='submit' name='tags_flyout'><img class='svg' src='assets/icons/edit.svg'>Add image tags</button>
    </form>";

    // Edit authro
    if (is_admin($_SESSION['id'])) {
      echo "<form id='author_form' method='POST' action='ui/image_interaction.php'>
        <input    id='author_header'                                                            type='hidden' name='header'       value='Who owns the image?????'>
        <input    id='author_description'                                                       type='hidden' name='description'  value='Enter ID of image owner'>
        <button   id='author_submit'      class='btn alert-low space-top-small flyout-display'  type='submit' name='author_flyout'><img class='svg' src='assets/icons/edit.svg'>Edit author</button>
      </form>";
    }

    echo "</div>";
  }
  ?>

  <?php
  // Must be included with flyout.php
  echo "<script src='scripts/flyout.js'></script>";

  include "ui/top.html";
  include "ui/footer.php";
  ?>
</body>
</html>
