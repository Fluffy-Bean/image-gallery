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
    if (isset($_POST['token'])) {
      // Check if invite code is empty
      if (empty($_POST['token'])) {
        $error = "Enter Invite Code ;3";
      } else {
        // Prepare sql for sus
        $sql = "SELECT id FROM tokens WHERE code = ? AND used = 0";

        if ($stmt = mysqli_prepare($conn, $sql)) {
          mysqli_stmt_bind_param($stmt, "s", $param_code);

          $param_code = $_POST['token'];

          // Ask sql nicely if other usernames exist and store info
          if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) == 1) {
              $token = trim($_POST["token"]);
            } else {
              $error = "Invite code not valid";
            }
          } else {
            $error = "Sussy things happened on our end and couldn't check token";
          }

          // Outa here with this
          mysqli_stmt_close($stmt);
        }
      }
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
          // Prepare sql
          $sql = "UPDATE tokens SET used = True WHERE code = ?";
          $stmt = mysqli_prepare($conn, $sql);
          mysqli_stmt_bind_param($stmt, "s", $param_token);
          $param_token = $_POST['token'];

          if (mysqli_stmt_execute($stmt)) {
            //
            // Hey fluffy why didn't you do this
            // Hey fluffy, thats not how you do this
            // Thats wrong! Do this instead!!!!!!
            //
            // I DON'T KNOW HOW TO DO THIS, BUT IT WORKS
            // SO LEAVE ME ALONEEEEEEEEEE
            // anyway....

            // Generate Token
            $token_array = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
            $new_token = substr(str_shuffle($token_array), 0, 10);

            // Prepare sql
            $sql = "INSERT INTO tokens (code, used) VALUES(?, False)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $param_new_token);
            $param_new_token = $new_token;
            mysqli_stmt_execute($stmt);
          }

          // Yupeee! Account was made
          $success = "Account made for ".$username."!!!!!!";
        } else {
          $error = "Something went fuckywucky, please try later";
        }
      }
    }
  }
  ?>

  <div class="signup-root default-window">
    <h2 class="space-bottom">Make account</h2>
    <p class="space-bottom">And amazing things happened here...</p>
    <form class="flex-down between" method="POST" action="signup.php" enctype="multipart/form-data">
      <input class="btn alert-default space-bottom-large" type="text" name="username" placeholder="Username">
      <input class="btn alert-default space-bottom" type="password" name="password" placeholder="Password">
      <input class="btn alert-default space-bottom-large" type="password" name="confirm_password" placeholder="Re-enter Password">
      <input class="btn alert-default space-bottom-large" type="text" name="token" placeholder="Invite Code">
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
