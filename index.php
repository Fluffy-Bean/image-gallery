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
  if ($_GET["del"] == "true") {
    echo "<p class='alert alert-high'>Successfully deleted image: ".$_GET['id']."</p>";
  }
  ?>

  <div class="info-text center">
    <h1>Fluffys Super Duper Secrete Project!</h1>
    <p>Fluffy's test website on uploading images to a database!</p>
  </div>

  <?php
  // Attempt database connection
  $conn = mysqli_connect("localhost", "uwu", "password", "swag");
  // If connecton failed, notify user
  if (!$conn) {
    echo "<p class='alert alert-high'>Could not connect to database</p>";
  }


  // My terrible workaround for not being able to show deletion status up here
  if (isset($_GET['d'])) {
    echo "<p class='alert default'>Image ".$_GET['d']." has been modified, <a href='#deleted'>view status here</a></p>";
  }
  ?>
  <div class="gallery-root flex-left">
    <?php
    // Reading images from table
    $img = mysqli_query($conn, "SELECT * FROM swag_table");
    while ($row = mysqli_fetch_array($img)) {
      echo "<div class='gallery-item'>";
      // Image loading
      echo "<a href='https://superdupersecteteuploadtest.fluffybean.gay/image.php?id=".$row['id']."'><img class='gallery-image' loading='lazy' src='images/".$row['imagename']."' id='".$row['id']."'></a>";
      echo "</div>";
    }
    ?>
  </div>

  <?php include('ui/footer.php'); ?>
</body>
</html>
