<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Account</title>
  <link rel="stylesheet" href="../css/master.css">
  <link href="https://fonts.googleapis.com/css2?family=Rubik" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@600&amp;display=swap">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@500&amp;display=swap">
</head>
<body>
  <?php
  include("../ui/header.php");

  // Initialize the session
  session_start();

  // Check if the user is logged in, otherwise redirect to login page
  if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: https://superdupersecteteuploadtest.fluffybean.gay/account/login.php");
    exit;
  }

  if (isset($_POST['reset'])) {

    // Validate new password
    if (empty(trim($_POST["new_password"]))) {
      $error = "Enter new password!";
    } elseif(strlen(trim($_POST["new_password"])) < 6) {
      $error = "Password not long enough, must be 6 or more characters!";
    } else {
      $new_password = trim($_POST["new_password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
      $error = "Pls confirm the password";
    } else {
      $confirm_password = trim($_POST["confirm_password"]);
      if(empty($error) && ($new_password != $confirm_password)) {
        $error = "Password did not match!!!!";
      }
    }

    // Check for errors
    if (empty($error)) {
      // Prepare for wack
      $sql = "UPDATE users SET password = ? WHERE id = ?";

      if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);

        // Setting up Password parameters
        $param_password = password_hash($new_password, PASSWORD_DEFAULT);
        $param_id = $_SESSION["id"];

        // Attempt to execute (sus)
        if (mysqli_stmt_execute($stmt)) {
          // Password updated!!!! Now goodbye
          session_destroy();
          header("Location: https://superdupersecteteuploadtest.fluffybean.gay/account/login.php");
        } else {
          $error = "Oopsie woopsie, somthing brokie :c";
        }

        // Close connection
        mysqli_close($stmt);
      }
    }

    // Close connection
    mysqli_close($conn);
  }
  ?>

  <div class="alert-banner">
    <?php
    if (isset($error)) {
      echo notify($error, "low");
    }
    ?>
    <script src='../scripts/alert.js'></script>
  </div>

  <div class="password-reset-root default-window">
    <h2 class="space-bottom">Reset Password</h2>
    <p class="space-bottom">After reset, you will be kicked out to login again</p>
    <form class="flex-down between" method="POST" action="password-reset.php" enctype="multipart/form-data">
      <input class="btn alert-default space-bottom" type="password" name="new_password" placeholder="New Password">
      <input class="btn alert-default space-bottom" type="password" name="confirm_password" placeholder="Confirm Password">
      <button class="btn alert-low" type="submit" name="reset"><img class="svg" src="../assets/icons/sign-in.svg">Reset</button>
    </form>
  </div>

  <?php
  include("../ui/top.html");
  include("../ui/footer.php");
  ?>
</body>
</html>
