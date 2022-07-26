<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="css/master.css">
  <link href="https://fonts.googleapis.com/css2?family=Rubik" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@600&amp;display=swap">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@500&amp;display=swap">
</head>
<body>
  <?php
  include("ui/header.php");
  require_once("ui/conn.php");

  // Initialize the session
  session_start();

  // Check if the user is already logged in, if yes then redirect him to welcome page
  if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
      $success = "You're already logged in! No need to try this again";
  }

  if (isset($_POST['login'])) {
    // Checking if Username is empty
    if (empty(trim($_POST["username"]))) {
      $error = "Who are you?";
    } else {
      $username = trim($_POST["username"]);
    }

    // Check if Password is empty
    if (empty(trim($_POST["password"]))) {
      $error = "Pls enter super duper secrete password";
    } else {
      $password = trim($_POST["password"]);
    }

    // Check if no errors occured
    if (empty($error)) {
      // Prepare so SQL doesnt get spooked
      $sql = "SELECT id, username, password FROM users WHERE username = ?";

      if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind dis shit
        mysqli_stmt_bind_param($stmt, "s", $param_username);

        // Set parameters
        $param_username = $username;

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
          // Store result
          mysqli_stmt_store_result($stmt);

          // Check if username exists, if yes then verify password
          if (mysqli_stmt_num_rows($stmt) == 1) {
            // Bind result variables
            mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
            if (mysqli_stmt_fetch($stmt)) {
              if (password_verify($password, $hashed_password)) {
                // Password is correct, so start a new session
                session_start();

                // Store data in session variables
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $id;
                $_SESSION["username"] = $username;

                // let the user know
                $success = "Yupee! You are now logged in";
                header("Location:https://superdupersecteteuploadtest.fluffybean.gay/index.php?login=success");
              } else {
                $error = "Username or Password sus, please try again :3";
              }
            }
          } else {
            $error = "Username or Password sus, please try again :3";
          }
        } else {
          $error = "Sowwy, something went wrong on our end :c";
        }

        // Close statement
        mysqli_stmt_close($stmt);
      }
    }

    // Close connection
    mysqli_close($conn);
  }
  ?>

  <div class="login-root">
    <h2 class="space-bottom">Login</h2>
    <p class="space-bottom">Passwords are important to keep safe. Don't tell anyone your password, not even Fluffy!</p>
    <form class="flex-down between" method="POST" action="login.php" enctype="multipart/form-data">
      <input class="btn alert-default space-bottom" type="text" name="username" placeholder="Username">
      <input class="btn alert-default space-bottom-large" type="password" name="password" placeholder="Password">
      <button class="btn alert-high" type="submit" name="login"><img class="svg" src="assets/icons/sign-in.svg">Login</button>
    </form>
    <?php
    if (isset($error)) {
      echo "<p class='alert alert-low space-top'>".$error."</p>";
    }
    if (isset($success)) {
      echo "<p class='alert alert-high space-top'>".$success."</p>";
    }
    ?>
    <a class='btn alert-default space-top-large' href='https://superdupersecteteuploadtest.fluffybean.gay/signup.php'><img class="svg" src="assets/icons/sign-in.svg">Need an account? Sign up!</a>
  </div>

  <?php
  include("ui/top.html");
  include("ui/footer.html");
  ?>
</body>
</html>
