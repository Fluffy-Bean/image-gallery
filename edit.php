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
  $id = $_GET["id"];
  ?>

  <div class="edit-root">
    <h2>Modify Information</h2>
    <p class="space-below">Make sure that the id of the image is correct!</p>
    <form class="flex-down between" method="POST" action="edit.php" enctype="multipart/form-data">
        <input class="btn alert-default space-bottom" type="text" name="alt" placeholder="Description/Alt for image">
        <?php echo "<button class='btn alert-default' type='submit' name='update' value=".$id.">Update information</button>"; ?>
    </form>
    <?php
    if ($_GET["r"] == "success") {
      // Info updated
      echo "<p class='alert alert-high space-top'>Information updated!</p>";
    } elseif ($_GET["r"] == "fail") {
      // Upload failed
      echo "<p class='alert alert-low space-top'>Something fuckywucky</p>";
    } elseif ($_GET["r"] == "noinfo") {
      // No info was present
      echo "<p class='alert alert-default space-top'>No description/alt, pls give</p>";
    } else {
      // echo "<p class='alert alert-default'>Please enter information</p>";
    }
    ?>
  </div>

  <?php include("ui/conn.php");

  if (isset($_POST['update'])) {
    if (empty($_POST['alt'])) {
      header("Location:edit.php?r=noinfo");

    } else {
      $sql = "UPDATE swag_table SET alt='".$_POST['alt']."' WHERE id=".$_POST['update'];

      if (mysqli_query($conn, $sql)) {
        //header("Location:edit.php?r=success");
        header("Location:https://superdupersecteteuploadtest.fluffybean.gay/image.php?id=".$_POST['update']."&update=success");
      } else {
        header("Location:edit.php?r=fail");
      }
    }
  }
  ?>


  <?php include("ui/footer.php"); ?>
</body>
</html>
