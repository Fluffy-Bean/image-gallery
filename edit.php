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
  include("ui/conn.php");

  if (isset($_POST['id'])) {
    // Getting all image info from table
    $get_image = "SELECT * FROM swag_table WHERE id = ".$_POST['id'];
    $image_results = mysqli_query($conn, $get_image);
    $image = mysqli_fetch_assoc($image_results);

    // Checking if user has edit rights
    if (isset($_SESSION['id']) && $image['author'] == $_SESSION['id'] || $_SESSION['id'] == 1) {
      if (isset($_POST['alt'])) {
        $sql = $conn->prepare("UPDATE swag_table SET alt=? WHERE id=?");
        $sql->bind_param("si", $alt, $id);

        $alt = $_POST['alt'];
        $id = $_POST['id'];

        if ($sql->execute()) {
          header("Location:https://superdupersecteteuploadtest.fluffybean.gay/image.php?id=".$_POST['id']."&update=success");
        } else {
          $error = "Something fuckywucky";
        }
      } else {
        $error = "No description/alt, pls give";
      }
    } else {
      $error = "You do not have edit rights";
    }
  }

  ?>

  <div class="edit-root">
    <h2>Modify Information</h2>
    <p class="space-below">This is a dangerous place to step foot into... tread carefully.</p>
    <form class="flex-down between" method="POST" action="edit.php" enctype="multipart/form-data">
        <input class="btn alert-default space-bottom-large" type="text" name="alt" placeholder="Description/Alt for image">
        <?php echo "<button class='btn alert-default' type='submit' name='id' value=".$_GET["id"]."><img class='svg' src='assets/icons/edit.svg'>Update information</button>"; ?>
    </form>

    <?php
    if (isset($error)) {
      echo "<p class='alert alert-low space-top'>".$error."</p>";
    }
    ?>
  </div>

  <?php include("ui/footer.php"); ?>
</body>
</html>
