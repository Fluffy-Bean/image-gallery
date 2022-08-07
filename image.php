<?php
function info_check($string){
  if (isset($string) && !empty($string)) {
    return $string;
  } else {
    return "No information provided.";
  }
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
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

  <!-- Sniffle script! -->
  <script src="Sniffle/sniffle.js"></script>
  <link rel='stylesheet' href='Sniffle/sniffle.css'>

  <!-- Flyout script! -->
  <script src="Flyout/flyout.js"></script>
  <link rel='stylesheet' href='Flyout/flyout.css'>
</head>
<body>
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

  include"ui/nav.php"; ?>

  <script>
    if (params.update == "success") {
      sniffleAdd("Info", "Image information updated", "var(--green)");
    }
    if (params.del == "fail") {
      sniffleAdd("Error", "Failed to delete image", "var(--red)");
    }
  </script>

  <div class="image-container space-bottom-large">
    <img class='image' id='<?php echo $image['id']; ?>' src='<?php echo $image_path; ?>' alt='<?php echo $image_alt; ?>'>
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
  ?>
    <!-- Danger zone -->
    <div class='danger-zone flex-down default-window'>
    <h2>Danger zone</h2>

    <!-- Delete -->
    <button id='deleteButton' class='btn alert-low'><img class='svg' src='assets/icons/trash.svg'>Delete image</button>
    <script>
      $('#deleteButton').click(function(){
        var header = "Are you sure?";
        var description = "Deleting this image is pernament, there is no going back after this!!!!!";
        var actionBox = "<button class='btn alert-low'><img class='svg' src='assets/icons/trash.svg'>Delete image</button>";
        flyoutShow(header, description, actionBox);
      });
    </script>

    <!--
     |-------------------------------------------------------------
     | Edit description
     |-------------------------------------------------------------
    -->
    <button id='descriptionButton' class='btn alert-low space-top-small'><img class='svg' src='assets/icons/edit.svg'>Edit description</button>
    <script>
      $('#descriptionButton').click(function(){
        var header = "Enter new Description/Alt";
        var description = "Whatcha gonna put in there 👀";
        var actionBox = "<form id='descriptionConfirm'>\
        <input id='descriptionInput' class='btn alert-default space-bottom' type='text' placeholder='Description/Alt for image'>\
        <button id='descriptionSubmit' class='btn alert-low' type='submit'><img class='svg' src='assets/icons/edit.svg'>Update information</button>\
        </form>\
        <div id='descriptionErrorHandling'></div>";
        flyoutShow(header, description, actionBox);
      });
      $("#descriptionConfirm").submit(function(event) {
        event.preventDefault();
        var descriptionInput = $("#descriptionInput").val();
        var descriptionSubmit = $("#descriptionSubmit").val();
        $("#descriptionErrorHandling").load("app/image/edit_description.php", {
          id: <?php echo $_GET['id']; ?>,
          description: descriptionInput,
          submit: descriptionSubmit
        });
      });
    </script>

    <!-- Edit tags -->
    <button id='tagButton' class='btn alert-low space-top-small'><img class='svg' src='assets/icons/edit.svg'>Add image tags</button>
    <script>
      $('#tagButton').click(function(){
        var header = "Tags";
        var description = "Add image tags here! This is still being tested so your tags may be removed later on. Tags ONLY accept, letters, numbers and underscores. Hyphens will be stitched to underscores and spaces will seperate the different tags from eachother";
        var actionBox = "<input class='btn alert-default space-bottom' type='text' name='add_tags' placeholder='Tags are seperated by spaces'>\
        <button class='btn alert-low'><img class='svg' src='assets/icons/edit.svg'>Add tags</button>";
        flyoutShow(header, description, actionBox);
      });
    </script>

    <!-- Edit authro -->
  <?php
    if (is_admin($_SESSION['id'])) {
  ?>
      <form id='author_form' method='POST' action='ui/image_interaction.php'>
        <input    id='author_header'                                                            type='hidden' name='header'       value='Who owns the image?????'>
        <input    id='author_description'                                                       type='hidden' name='description'  value='Enter ID of image owner'>
        <button   id='author_submit'      class='btn alert-low space-top-small'  type='submit' name='author_flyout'><img class='svg' src='assets/icons/edit.svg'>Edit author</button>
      </form>
  <?php
    }
    echo "</div>";
  }
  ?>

  <?php include "ui/footer.php"; ?>
</body>
</html>
