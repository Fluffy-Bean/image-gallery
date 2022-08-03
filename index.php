<?php include "ui/required.php"; ?>

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
  <?php
  include "ui/nav.php";
  ?>

  <div class="alert-banner">
    <?php
    /*
      If theres a success in updating the image,
      it'll let the user know
    */
    // Deletion toast
    if ($_GET["del"] == "true") {
      echo notify("Successfully deleted image: ".$_GET['id'], "high");
    }
    // Account toast
    if ($_GET["login"] == "success") {
      echo notify("O hi ".$_SESSION['username'], "high");
    }
    ?>
    <script src='scripts/alert.js'></script>
  </div>

  <?php
  // Show search
  if ($_GET["srch"] == "show") {
    $header = "Search for a tags!";
    $content = "Here you can search for funnies! Like raccoons!!!!!!!!!";
    $action = "<form class='flex-down between' method='POST' enctype='multipart/form-data'>
      <input class='btn alert-default space-bottom' type='text' name='search' placeholder='👀'>
      <button class='btn alert-high' type='submit' name='search_confirm' value=''><img class='svg' src='assets/icons/binoculars.svg'>Search</button>
    </form>";

    flyout($header, $content, $action);
  }
  /*
    Search Confirm
  */
  if (isset($_POST['search_confirm'])) {
    // Unset all the variables, needed by flyout
    unset($header, $content, $action);

    // Clean input
    $tags_string = tag_clean(trim($_POST['search']));

    header("Location:index.php?q=".$tags_string);
  }
  if (isset($_GET["q"])) {
    echo "<p class='alert alert-default space-bottom'>Search results for: ".$_GET['q']."</p>";
  }
  ?>

  <div class="info-text center">
    <?php
    // Welcome depending on if user is logged in or not
    if (isset($_SESSION["username"])) {
      echo "<h1>Welcome ".$_SESSION['username']."!</h1>";
    } else {
      echo "<h1>Welcome!</h1>";
    }

    // Random welcome message
    $welcome_message = array("*internal screaming*", "Sussy Wussy", "What is this world?", "Don't forget to drink water!", "Bruh", "This is so poorly programmed", "Sorry", "Fluffy made this!", "maybe", "I'm gay");
    echo "<p>".$welcome_message[array_rand($welcome_message, 1)]."</p>";
    ?>
  </div>

  <div class="gallery-root flex-left">
    <?php
    // Reading images from table
    $image_request = mysqli_query($conn, "SELECT * FROM swag_table ORDER BY id DESC");

    while ($image = mysqli_fetch_array($image_request)) {
      // If search is set
      if (isset($_GET['q']) && !empty($_GET['q'])) {
        // Make search into an array
        $search_array = explode(" ", $_GET['q']);

        // Get images tags for comparing
        $image_tag_array = explode(" ", $image['tags']);

        // Compare arrays
        $compare_results = array_intersect($image_tag_array, $search_array);
        if (count($compare_results) > 0) {
          // Getting thumbnail
          if (file_exists("images/thumbnails/".$image['imagename'])) {
            $image_path = "images/thumbnails/".$image['imagename'];
          } else {
            $image_path = "images/".$image['imagename'];
          }

          // Image loading
          echo "<div class='gallery-item'>";
          echo "<a href='image.php?id=".$image['id']."'><img class='gallery-image' loading='lazy' src='".$image_path."' id='".$image['id']."'></a>";
          echo "</div>";
        }
      } else {
        // Getting thumbnail
        if (file_exists("images/thumbnails/".$image['imagename'])) {
          $image_path = "images/thumbnails/".$image['imagename'];
        } else {
          $image_path = "images/".$image['imagename'];
        }

        // Image loading
        echo "<div class='gallery-item'>";
        echo "<a href='image.php?id=".$image['id']."'><img class='gallery-image' loading='lazy' src='".$image_path."' id='".$image['id']."'></a>";
        echo "</div>";
      }
    }
    ?>
  </div>

  <?php
  include "ui/top.html";
  include "ui/footer.php";
  ?>
</body>
</html>
