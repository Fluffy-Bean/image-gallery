<?php
/*
 |-------------------------------------------------------------
 | Login
 |-------------------------------------------------------------
 | This is annoying because I want to keep the website secure
 | but I have no clue how to keep things secure with HTML, PHP
 | or JS. So I hope seperating the scripts and putting all this
 | into a PHP file is a good secutiry mesure
 |-------------------------------------------------------------
*/
// Include server connection
include "../server/conn.php";

if (isset($_POST['submit'])) {
    // Checking if Username is empty
    if (empty(trim($_POST["username"]))) {
        ?>
        <script>
            sniffleAdd('Who dis?', 'You must enter a username to login!', 'var(--red)', '../assets/icons/cross.svg');
        </script>
        <?php
    } else {
        $username = trim($_POST["username"]);
    }
    
    // Check if Password is empty
    if (empty(trim($_POST["password"]))) {
        ?>
        <script>
            sniffleAdd('Whats the magic word?', 'Pls enter the super duper secrete word(s) to login!', 'var(--red)', '../assets/icons/cross.svg');
        </script>
        <?php
    } else {
        $password = trim($_POST["password"]);
    }


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
                        ?>
                        <script>
                            sniffleAdd('O hi <?php echo $_SESSION["username"]; ?>', 'You are now logged in! You will be redirected in a few seconds', 'var(--green)', '../assets/icons/hand-waving.svg');
                            setTimeout(function(){window.location.href = "../index.php?login=success";}, 4000);
                        </script>
                        <?php
                    } else {
                        ?>
                        <script>
                            sniffleAdd('Sus', 'Username or Password WRONG, please try again :3', 'var(--red)', '../assets/icons/cross.svg');
                        </script>
                        <?php
                    }
                }
            } else {
                ?>
                <script>
                    sniffleAdd('Sus', 'Username or Password WRONG, please try again :3', 'var(--red)', '../assets/icons/cross.svg');
                </script>
                <?php
            }
        } else {
            ?>
            <script>
                sniffleAdd('woops...', 'Sowwy, something went wrong on our end :c', 'var(--red)', '../assets/icons/cross.svg');
            </script>
            <?php
        }
        // Close statement
        mysqli_stmt_close($stmt);
    }
}
