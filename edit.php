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
  <?php include("ui/header.php"); ?>

  <div class="edit-root">
    <h2>Modify Information</h2>
    <p class="space-below">This is a dangerous place to step foot into... tread carefully.</p>
    <form class="flex-down between" method="POST" action="edit.php" enctype="multipart/form-data">
        <input class="btn alert-default space-bottom" type="text" name="alt" placeholder="Description/Alt for image">
        <?php echo "<button class='btn alert-default' type='submit' name='id' value=".$_GET["id"]."><img class='svg' src='assets/icons/edit.svg'>Update information</button>"; ?>
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
    }
    ?>
  </div>

  <?php
  include("ui/conn.php");

  if (isset($_POST['id'])) {
    if (empty($_POST['alt'])) {
      header("Location:edit.php?r=noinfo");

    } else {
      $sql = $conn->prepare("UPDATE swag_table SET alt=? WHERE id=?");
      $sql->bind_param("si", $alt, $id);

      $alt = $_POST['alt'];
      $id = $_POST['id'];

      if ($sql->execute()) {
        //header("Location:edit.php?r=success");
        header("Location:https://superdupersecteteuploadtest.fluffybean.gay/image.php?id=".$_POST['id']."&update=success");
      } else {
        header("Location:edit.php?r=fail");
      }
    }
  }
  ?>

  <?php include("ui/footer.php"); ?>
</body>
</html>
