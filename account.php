<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Account</title>
  <link rel="stylesheet" href="css/master.css">
  <link href="https://fonts.googleapis.com/css2?family=Rubik" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@600&amp;display=swap">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@500&amp;display=swap">
</head>
<body>
  <?php include("ui/header.php"); ?>

  <div class="account-root">
    <h2>Account settings</h2>
    <?php
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
      echo "<p>O hi ".$_SESSION["username"].".</p>";
      echo "<a class='btn alert-default space-top' href='https://superdupersecteteuploadtest.fluffybean.gay/logout.php'><img class='svg' src='assets/icons/sign-out.svg'>Logout</a>";
    } else {
      echo "<p class='space-bottom-large'>You must be logged in to change your account settings!</p>";
      echo "<a class='btn alert-high space-top-large' href='https://superdupersecteteuploadtest.fluffybean.gay/signup.php'>Sign up!</a>";
    }
    ?>
  </div>

  <?php include("ui/footer.php"); ?>
</body>
</html>
