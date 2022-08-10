<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lynx Gallery</title>

  <!-- Stylesheets -->
  <link rel="stylesheet" href="css/master.css">
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

  <!-- Upload Script -->
  <script>
    $(document).ready(function() {
      $("#uploadSubmit").submit(function(event) {
        event.preventDefault();
        var formData = new FormData(this.form);

        $.ajax({
          url: "app/image/upload_image.php",
          type: 'post',
          data: formData,
          contentType: false,
          processData: false,
          success: function(response){
            $("#sniffle").html(response);
          }
        });
      });
    });
  </script>
</head>
<body>
  <?php
  include "ui/required.php";
  include "ui/nav.php";

  // Check if user is logged in
  if (!loggedin()) {
    header("Location: index.php");
  }
  ?>

  <div class="upload-root default-window">
    <h2 class="space-bottom">Upload image</h2>
    <p>In this world you have 2 choices, to upload a really cute picture of an animal or fursuit, or something other than those 2 things.</p>
    <form id="uploadSubmit" class="flex-down between" method="POST" enctype="multipart/form-data">
        <input name="image" class="btn alert-default space-bottom" type="file" placeholder="select image UwU">
        <input name="alt" class="btn alert-default space-bottom-large" type="text" placeholder="Description/Alt for image">
        <button name="submit" class="btn alert-high" type="submit"><img class="svg" src="assets/icons/upload.svg">Upload Image</button>
    </form>
  </div>

  <?php include "ui/footer.php"; ?>
</body>
</html>
