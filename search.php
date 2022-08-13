<?php
/*if (isset($_GET['q']) && !empty($_GET['q'])) {
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
}*/
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lynx Gallery</title>

  <!-- Stylesheets -->
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="css/normalise.css">

  <!-- Google Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@600">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Secular+One&display=swap">

  <!-- JQuery -->
  <script
    src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
    crossorigin="anonymous">
  </script>

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
  include "ui/nav.php";
  ?>

  <div class="search-root">
    <h2>Where did the search go!</h2>
    <p>Due to how it was implemented originally, it was hard to handle and work with. So I removed it.</p>
    <p>It'll be coming back, but since it's going to be a lot of work it'll have to be much later.</p>
  </div>

  <?php include "ui/footer.php"; ?>
</body>
</html>
