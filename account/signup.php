<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up</title>
  <link rel="stylesheet" href="../css/master.css">
  <link href="https://fonts.googleapis.com/css2?family=Rubik" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@600&amp;display=swap">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@500&amp;display=swap">
</head>
<body>
  <?php
  include("../ui/header.php");
  include_once("../ui/conn.php");

  // Validate susness of Username
  if (isset($_POST['signup'])) {
    if (empty(trim($_POST["username"]))) {
      // Username was taken
      $error = "Enter a username reeeee";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))) {
      // Username entered contains ilegal characters
      $error = "Very sus. Username can only contain letters, numbers, and underscores";
    } else {
      // Prepare sql for sus
      $sql = "SELECT id FROM users WHERE username = ?";

      if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $username_request);

        $username_request = trim($_POST["username"]);

        if (mysqli_stmt_execute($stmt)) {
          // Ask sql nicely if other usernames exist and store info
          mysqli_stmt_store_result($stmt);

          if (mysqli_stmt_num_rows($stmt) == 1) {
            // Username not entered
            $error = "Oopsie, username taken :c";
          } else {
            $username = trim($_POST["username"]);
          }
        } else {
          $error = "Sussy things happened on our end, please try again later";
        }
        // Outa here with this
        mysqli_stmt_close($stmt);
      }
    }

    // Validate sussness of Password
    if (empty(trim($_POST["password"]))) {
      // No password entered
      $error = "Bruh, enter a password";
    } elseif(strlen(trim($_POST["password"])) < 6){
      // Password not long enough 👀
      $error = "(Password) Not long enough 👀";
    } else {
      $password = trim($_POST["password"]);
    }

    // Validate sussiness of the other Password
    if (empty(trim($_POST["confirm_password"]))) {
      // Did not confirm passowrd
      $error = "You must confirm password!!!!!";
    } else {
      $confirm_password = trim($_POST["confirm_password"]);
      if (empty($error) && $confirm_password != $password) {
        // Password and re-entered Password does not match
        $error = "Passwords need to be the same, smelly smelly";
      }
    }

    // Check for invite code
    if (isset($_POST['invite_code'])) {
      if ($_POST['invite_code'] != "supercoolcode") {
        $error = "Seems that you don't have the right invite code, whatever shall you do";
      }
    } else {
      $error = "Enter Invite Code ;3";
    }

    // Checking for errors
    if (empty($error)) {
      $sql = "INSERT INTO users (username, password) VALUES (?, ?)";

      if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

        // Set parameters
        $param_username = $username;
        $param_password = password_hash($password, PASSWORD_DEFAULT);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
          $success = "Account made for ".$username."!!!!!!";
        } else {
          $error = "Something went fuckywucky, please try later";
        }
      }
    }
  }
  ?>

  <div class="signup-root">
    <h2 class="space-bottom">Make account</h2>
    <p class="space-bottom">And amazing things happened here...</p>
    <form class="flex-down between" method="POST" action="signup.php" enctype="multipart/form-data">
      <input class="btn alert-default space-bottom-large" type="text" name="username" placeholder="Username">
      <input class="btn alert-default space-bottom" type="password" name="password" placeholder="Password">
      <input class="btn alert-default space-bottom-large" type="password" name="confirm_password" placeholder="Re-enter Password">
      <input class="btn alert-default space-bottom-large" type="text" name="invite_code" placeholder="Invite Code">
      <button class="btn alert-high" type="submit" name="signup"><img class="svg" src="../assets/icons/sign-in.svg">Sign Up</button>
      <?php
      if (isset($error)) {
        echo "<p class='alert alert-low space-top'>".$error."</p>";
      }
      if (isset($success)) {
        echo "<p class='alert alert-high space-top'>".$success."</p>";
      }
      ?>
    </form>
  </div>

  <?php
  include("../ui/top.html");
  include("../ui/footer.php");
  ?>
</body>
</html>
