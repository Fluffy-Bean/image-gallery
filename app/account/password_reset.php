<?php
/*
 |-------------------------------------------------------------
 | Password Reset
 |-------------------------------------------------------------
 | I want to make it possible to reset the password without
 | access to the account directly with an email reset link or
 | something. I also want to confirm the password change with
 | the old password in the future, as people forget passwords
 | and people can get onto accounts. For now this is a shitty
 | little system thats inplace for those who need it. Hopefully
 | I can make something better in the future...
 |-------------------------------------------------------------
*/
// Initialize the session
session_start();
// Include server connection
include dirname(__DIR__)."/server/conn.php";
include dirname(__DIR__)."/app.php";

use App\Account;

$user_info = new Account();

if (isset($_POST['submit'])) {
    /*
     |-------------------------------------------------------------
     | Set error status to 0
     |-------------------------------------------------------------
     | if there are more than 0 error, then they cannot submit a
     | request
     |-------------------------------------------------------------   
    */
    $error = 0;

    // Validate new password
    if (empty(trim($_POST["new_password"]))) {
        ?>
            <script>
                sniffleAdd('Meep', 'Enter a new password!', 'var(--red)', 'assets/icons/cross.svg');
                flyoutClose();
            </script>
        <?php
        $error += 1;
    } elseif(strlen(trim($_POST["new_password"])) < 6) {
        ?>
            <script>
                sniffleAdd('Not long enough...', 'Password, must be 6 or more characters in length uwu', 'var(--red)', 'assets/icons/cross.svg');
                flyoutClose();
            </script>
        <?php
        $error += 1;
    } else {
        $new_password = trim($_POST["new_password"]);
    }
    
    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        ?>
            <script>
                sniffleAdd('Meep', 'You must confirm the password!!!!', 'var(--red)', 'assets/icons/cross.svg');
                flyoutClose();
            </script>
        <?php
        $error += 1;
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($error) && ($new_password != $confirm_password)) {
            ?>
                <script>
                    sniffleAdd('AAAA', 'Passwords do not match!!!', 'var(--red)', 'assets/icons/cross.svg');
                    flyoutClose();
                </script>
            <?php
            $error += 1;
        }
    }

    if (isset($_POST['id']) && $user_info->is_admin($conn, $_SESSION["id"])) {
        $user_id = $_POST['id'];
    } elseif (empty($_POST['id'])) {
        $user_id = $_SESSION["id"];
    } else {
        ?>
            <script>
                sniffleAdd('Oopsie', 'An error occured while figuring out which user to change the password of... Are you an admin?', 'var(--red)', 'assets/icons/cross.svg');
                flyoutClose();
            </script>
        <?php
        $error += 1;
    }

    // Check for errors
    if ($error <= 0) {
        // Prepare for wack
        $sql = "UPDATE users SET password = ? WHERE id = ?";
    
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
    
            // Setting up Password parameters
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $user_id;
    
            // Attempt to execute (sus)
            if (mysqli_stmt_execute($stmt)) {
                // Password updated!!!! Now goodbye
                if ($user_id == $_SESSION["id"]) {
                    // Check if password reset was done by user
                    session_destroy();
                    ?>
                        <script>
                            sniffleAdd('Password updated', 'Now goodbye.... you will be redirected in a moment', 'var(--green)', 'assets/icons/check.svg');
                            setTimeout(function(){window.location.href = "account/login.php";}, 2000);
                        </script>
                    <?php
                } else {
                    // An admin has changed the password
                    ?>
                        <script>
                            sniffleAdd('Password updated', 'Password has been reset for user! But their session may still be active', 'var(--green)', 'assets/icons/check.svg');
                            flyoutClose();
                        </script>
                    <?php
                }
            } else {
                ?>
                    <script>
                        sniffleAdd('Bruh', 'Something happened on our end, sowwy', 'var(--red)', 'assets/icons/cross.svg');
                        flyoutClose();
                    </script>
                <?php
            }
        }
    }
}