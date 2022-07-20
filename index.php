<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>UwU</title>
  <link rel="stylesheet" href="sussy.css">
  <link href="https://fonts.googleapis.com/css2?family=Rubik" rel="stylesheet">
</head>
<body>
  <h1>Hewwo UwU</h1>
  <p>Fluffy's test website on uploading images to a database!</p>

  <form method="POST" action="index.php?" enctype="multipart/form-data">
  	  <input class="btn" type="file" name="image" placeholder="select image UwU">
  	  <button class="btn" type="submit" name="upload">Upload Image</button>
  </form>

  <?php
  // Get page URL and parse to get query
  $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  $parse = parse_url($url);

  $base_url = $parse["scheme"]."://".$parse["host"].$parse["path"]."?";

  parse_str($parse["query"], $query);

  // Check Query to check file upload status
  if ($query["r"] == "epic") {
    echo "<p class='alert success'>Your Image uploaded successfully!</p>";
  }elseif ($query["r"] == "fail") {
    echo "<p class='alert fail'>F, Upload failed</p>";
  }elseif ($query["r"] == "nofile") {
    echo "<p class='alert fail'>No file lol</p>";
  }elseif ($query["r"] == "del") {
    echo "<p class='alert success'>Deleted file</p>";
  }elseif ($query["r"] == "nodel") {
    echo "<p class='alert fail'>Could not delete file</p>";
  }else{
    echo "<p class='alert default'>Select an image to upload</p>";
  }

  // My terrible workaround for not being able to show deletion status up here
  if (isset($_GET['d'])) {
    echo "<p class='alert default'>Image ".$_GET['d']." has been modified, <a href='#deleted'>view status here</a></p>";
  }

  // Attempt database connection
  $conn = mysqli_connect("localhost", "uwu", "password", "swag");

  // If connecton failed, notify user
  if (!$conn) {
    echo "<p class='alert fail'>Could not connect to database</p>";
  }
  if (isset($_POST['upload'])) {
    // Get image name
    $get_image_name = $_FILES['image']['name'];

    // If image present, continue
    if ($get_image_name != "") {
      // Set file path for image upload
      $image_path = "images/".basename($get_image_name);
      $sql = "INSERT INTO swag_table (imagename) VALUES ('$get_image_name')";

      // Uploading image to Table
      mysqli_query($conn, $sql);

      // Checking if image uploaded
      if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
        header("Location:index.php?r=epic");
      }else{
        header("Location:index.php?r=fail");
      }
    }else{
      // No image present
      header("Location:index.php?r=nofile");
    }
  }
  ?>
  <!--<p class="alert fail">Whatever gets uploaded will have to manually get removed.</p>-->

  <div class="gallery">
    <?php
    // Reading images from table
    $img = mysqli_query($conn, "SELECT * FROM swag_table");
    while ($row = mysqli_fetch_array($img)) {
      echo "<div class='item'>";
      // Image loading
      echo "<img loading='lazy' src='images/".$row['imagename']."' id='".$row['id']."'>";
      // Image hover details
      echo "<form class='detail' method='GET' enctype='multipart/form-data'>";
      echo "<p class='identity default'>ID: ".$row['id']."</p>";
      echo "<button class='delete_button btn b-colour' type='submit' name='d' value='".$row['id']."'>Delete</button>";

      echo "</form>";
      echo "</div>";
    }
    ?>
  </div>

  <?php
  // Check if query is set
  if (isset($_GET['d'])) {
    // Get all image detail
    $delete_select = "SELECT * FROM swag_table WHERE id = ".$_GET['d'];
    $delete_result = mysqli_query($conn,$delete_select);
    $img_records = mysqli_fetch_assoc($delete_result);

    // Get image name and its file path
    $file_name = $img_records['imagename'];
    $file_path = "images/".$file_name;

    // Try deleting image
    if(unlink($file_path)) {
      // If deleted, delete from Table
      $img_delete_request = "DELETE FROM swag_table WHERE id =".$img_records[id];
      $img_delete = mysqli_query($conn,$img_delete_request);
      if ($img_delete) {
        // Deleted image
        echo "<p class='alert success' id='deleted'>Successfully deleted: ".$file_name."/".$_GET['d']."</p>";
      }
    }else{
      // Could not delete from file
      echo "<p class='alert fail' id='deleted'>Failed to delete or no file under the name: ".$file_name." ".$_GET['d']."</p>";
    }
  }
  ?>
</body>
</html>
