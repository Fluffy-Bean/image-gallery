<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>UwU</title>
  <link rel="stylesheet" href="css/master.css">
  <link href="https://fonts.googleapis.com/css2?family=Rubik" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@600&amp;display=swap">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@500&amp;display=swap">
</head>
<body>
  <?php
  include("ui/header.php");

  // Deletion toast
  if ($_GET["del"] == "true") {
    echo "<p class='alert alert-high space-bottom'>Successfully deleted image: ".$_GET['id']."</p>";
  }
  ?>

  <div class="info-text center">
    <h1>Fluffys Super Duper Secrete Project!</h1>
    <p>Fluffy's test website on uploading images to a database!</p>
  </div>

  <div class="gallery-root flex-left">
    <?php
    include_once("ui/conn.php");

    // Reading images from table
    $image_request = mysqli_query($conn, "SELECT * FROM swag_table");

    while ($image = mysqli_fetch_array($image_request)) {
      // Getting thumbnail
      if (file_exists("images/thumbnails/".$image['imagename'])) {
        $image_path = "images/thumbnails/".$image['imagename'];
      } else {
        $image_path = "images/".$image['imagename'];
      }
      
      // Image loading
      echo "<div class='gallery-item'>";
      echo "<a href='https://superdupersecteteuploadtest.fluffybean.gay/image.php?id=".$image['id']."'><img class='gallery-image' loading='lazy' src='".$image_path."' id='".$image['id']."'></a>";
      echo "</div>";
    }
    ?>
  </div>

  <?php include("ui/footer.php"); ?>
</body>
</html>
