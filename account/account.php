<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lynx Gallery</title>

  <!-- Stylesheets -->
  <link rel="stylesheet" href="../css/master.css">
  <link rel="stylesheet" href="../css/normalise.css">

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
  <script src="../Sniffle/sniffle.js"></script>
  <link rel='stylesheet' href='../Sniffle/sniffle.css'>

  <!-- Flyout script! -->
  <script src="../Flyout/flyout.js"></script>
  <link rel='stylesheet' href='../Flyout/flyout.css'>
</head>
<body>
  <?php
  include "../ui/required.php";
  include "../ui/nav.php";
  ?>

  <div class="account-root default-window">
    <h2>Account settings</h2>
    <?php
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
      echo "<p>O hi ".$_SESSION["username"].".</p>";

      // Join code
      if ($_SESSION["id"] == 1) {
        echo "<h3 class='space-top'>Invite Codes</h3>";
        $token_request = mysqli_query($conn, "SELECT * FROM tokens WHERE used = 0");
        while ($token = mysqli_fetch_array($token_request)) {
            echo "<p class='text-box space-top center alert-default'>".$token['code']."</p>";
        }
      }

      echo "<h3 class='space-top'>Danger ahead</h3>";
      // Reset password
      echo "<p class='text-box space-top-large center alert-default'>Resetting your password regularly is a good way of keeping your account safe</p>";
      echo "<a class='btn alert-low space-top-small' href='https://superdupersecteteuploadtest.fluffybean.gay/account/password-reset.php'><img class='svg' src='../assets/icons/password.svg'>Reset Password</a>";

      // Logout
      echo "<p class='text-box space-top-large center alert-default'>Don't leave! I'm with the science team!</p>";
      echo "<a class='btn alert-low space-top-small' href='https://superdupersecteteuploadtest.fluffybean.gay/account/logout.php'><img class='svg' src='../assets/icons/sign-out.svg'>Logout</a>";
    } else {
      echo "<p class='space-bottom-large'>You must be logged in to change your account settings!</p>";
      echo "<a class='btn alert-high space-top-large' href='https://superdupersecteteuploadtest.fluffybean.gay/account/login.php'>Login!</a>";
    }
    ?>
  </div>

  <?php include "../ui/footer.php"; ?>
</body>
</html>
