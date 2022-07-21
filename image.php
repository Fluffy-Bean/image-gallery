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

    echo "<img class='image' src='images/".$image['imagename']."' id='".$image['id']."'>";

    if (!isset($_GET['id'])) {
      echo "cannot obtain image";
    }
    ?>
  </div>

  <div class="image-detail flex-down">
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
    echo "<form class='detail' method='GET' enctype='multipart/form-data'>";
    echo "<p class='identity default'>ID: ".$row['id']."</p>";
    echo "<button class='delete_button btn b-colour' type='submit' name='d' value='".$row['id']."'>Delete</button>";
    echo "</form>";

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
  </div>

  <?php include('ui/footer.php'); ?>
</body>
</html>
