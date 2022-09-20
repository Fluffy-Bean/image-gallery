<?php
// Include server connection
include dirname(__DIR__)."/server/conn.php";
include dirname(__DIR__)."/app.php";

use App\Account;

$user_info = new Account();
$user_ip = $user_info->get_ip();

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
if (isset($_POST['submit_login'])) {
    $error = 0;
    $ban_query = mysqli_query($conn, "SELECT * FROM bans WHERE ipaddress = '$user_ip' ORDER BY id DESC LIMIT 1");

    while ($ban_check = mysqli_fetch_assoc($ban_query)) {
        $ban_time = $ban_check['time'];
        $ban_perm = $ban_check['permanent'];
    }

    $ban_diff = time() - strtotime($ban_time);

    if ($ban_perm) {
        ?>
            <script>
                sniffleAdd('Bye bye!', 'You have been banned, contact the owner if you feel that this was a mistake', 'var(--red)', 'assets/icons/warning.svg');
            </script>
        <?php

        $error += 1;
    } elseif (($ban_diff / 60) <= 60) {
        ?>
            <script>
                sniffleAdd('Slow down!', 'You have attempted to login/signup too many times in 10 minutes. Come back in <?php echo round(60-($ban_diff/60)); ?> minutes', 'var(--red)', 'assets/icons/warning.svg');
            </script>
        <?php

        $error += 1;
    } else {
        $attemps = 0;
        $log_query = mysqli_query($conn, "SELECT * FROM logs WHERE ipaddress = '$user_ip' ORDER BY id DESC LIMIT 5");
    
        while ($log_array = mysqli_fetch_assoc($log_query)) {
            $log_diff = time() - strtotime($log_array['time']);

            if ($log_array['action'] == 'Failed to enter correct Password' && ($log_diff / 60) <= 10) {
                $attemps += 1;
            } elseif ($log_array['action'] == 'Failed to enter correct Invite Code' && ($log_diff / 60) <= 10) {
                $attemps += 1;
            }
        }

        if ($attemps >= 5) {
            mysqli_query($conn,"INSERT INTO bans (ipaddress, reason, length, permanent) VALUES('$user_ip','Attempted password too many times', '60', '0')");
        }
    }

    if ($error <= 0) {
        // Checking if Username is empty
        if (empty(trim($_POST["username"]))) {
            ?>
                <script>
                    sniffleAdd('Who dis?', 'You must enter a username to login!', 'var(--red)', 'assets/icons/cross.svg');
                </script>
            <?php
            $error += 1;
        } else {
            $username = trim($_POST["username"]);
        }
        
        // Check if Password is empty
        if (empty(trim($_POST["password"]))) {
            ?>
                <script>
                    sniffleAdd('Whats the magic word?', 'Pls enter the super duper secrete word(s) to login!', 'var(--red)', 'assets/icons/cross.svg');
                </script>
            <?php
            $error += 1;
        } else {
            $password = trim($_POST["password"]);
        }
    }

    if ($error <= 0) {
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
                                    sniffleAdd('O hi <?php echo $_SESSION["username"]; ?>', 'You are now logged in! You will be redirected in a few seconds', 'var(--green)', 'assets/icons/hand-waving.svg');
                                    setTimeout(function(){window.location.href = "index.php";}, 2000);
                                    //window.location.href = "../index.php?login=success";
                                </script>
                            <?php

                            mysqli_query($conn,"INSERT INTO logs (ipaddress, action) VALUES('$user_ip','New loggin to ".$_SESSION['username']."')");
                        } else {
                            ?>
                                <script>
                                    sniffleAdd('Sus', 'Username or Password WRONG, please try again :3', 'var(--red)', 'assets/icons/cross.svg');
                                </script>
                            <?php
                            mysqli_query($conn,"INSERT INTO logs (ipaddress, action) VALUES('$user_ip','Failed to enter correct Password')");
                        }
                    }
                } else {
                    ?>
                        <script>
                            sniffleAdd('Sus', 'Username or Password WRONG, please try again :3', 'var(--red)', 'assets/icons/cross.svg');
                        </script>
                    <?php
                    mysqli_query($conn,"INSERT INTO logs (ipaddress, action) VALUES('$user_ip','Failed to enter correct Username')");
                }
            } else {
                ?>
                    <script>
                        sniffleAdd('woops...', 'Sowwy, something went wrong on our end :c', 'var(--red)', 'assets/icons/cross.svg');
                    </script>
                <?php
            }
            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
}


/*
 |-------------------------------------------------------------
 | Signup
 |-------------------------------------------------------------
 | The dreaded signup. Please save me...
 |-------------------------------------------------------------
*/
if (isset($_POST['submit_signup'])) {
    $error = 0;
    $ban_query = mysqli_query($conn, "SELECT * FROM bans WHERE ipaddress = '$user_ip' ORDER BY id DESC LIMIT 1");

    while ($ban_check = mysqli_fetch_assoc($ban_query)) {
        $ban_time = $ban_check['time'];
        $ban_perm = $ban_check['permanent'];
    }

    $ban_diff = time() - strtotime($ban_time);

    if ($ban_perm) {
        ?>
            <script>
                sniffleAdd('Bye bye!', 'You have been banned, contact the owner if you feel that this was a mistake', 'var(--red)', 'assets/icons/warning.svg');
            </script>
        <?php

        $error += 1;
    } elseif (($ban_diff / 60) <= 60) {
        ?>
            <script>
                sniffleAdd('Slow down!', 'You have attempted to login/signup too many times in 10 minutes. Come back in <?php echo round(60-($ban_diff/60)); ?> minutes', 'var(--red)', 'assets/icons/warning.svg');
            </script>
        <?php

        $error += 1;
    } else {
        $attemps = 0;
        $log_query = mysqli_query($conn, "SELECT * FROM logs WHERE ipaddress = '$user_ip' ORDER BY id DESC LIMIT 5");
    
        while ($log_array = mysqli_fetch_assoc($log_query)) {
            $log_diff = time() - strtotime($log_array['time']);

            if ($log_array['action'] == 'Failed to enter correct Password' && ($log_diff / 60) <= 10) {
                $attemps += 1;
            } elseif ($log_array['action'] == 'Failed to enter correct Invite Code' && ($log_diff / 60) <= 10) {
                $attemps += 1;
            }
        }

        if ($attemps >= 5) {
            mysqli_query($conn,"INSERT INTO bans (ipaddress, reason, length, permanent) VALUES('$user_ip','Attempted password too many times', '60', '0')");
        }
    }

    if ($error <= 0) {
        if (empty(trim($_POST["username"]))) {
            // Username not entered
            ?>
                <script>
                    sniffleAdd('Hmmm', 'You must enter a username!', 'var(--red)', 'assets/icons/cross.svg');
                </script>
            <?php
            $error = $error + 1;
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))) {
            // Username entered contains illegal characters
            ?>
                <script>
                    sniffleAdd('Sussy Wussy', 'Very sus. Username can only contain letters, numbers, and underscores', 'var(--red)', 'assets/icons/cross.svg');
                </script>
            <?php
            $error = $error + 1;
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
                        // Username taken
                        ?>
                            <script>
                                sniffleAdd('A clone?', 'Sorry, but username was already taken by someone else', 'var(--red)', 'assets/icons/cross.svg');
                            </script>
                        <?php
                        $error = $error + 1;
                    } else {
                        $username = trim($_POST["username"]);
                    }
                } else {
                    ?>
                        <script>
                            sniffleAdd('Reee', 'We had a problem on our end, sowwy', 'var(--red)', 'assets/icons/cross.svg');
                        </script>
                    <?php
                    $error = $error + 1;
                }
                // Outa here with this
                mysqli_stmt_close($stmt);
            }
        }
    
        // Validate sussness of Password
        if (empty(trim($_POST["password"]))) {
            // No password entered
            ?>
                <script>
                    sniffleAdd('What', 'You must enter a password, dont want just anyone seeing your stuff uwu', 'var(--red)', 'assets/icons/cross.svg');
                </script>
            <?php
            $error = $error + 1;
        } elseif(strlen(trim($_POST["password"])) < 6){
            // Password not long enough 👀
            ?>
                <script>
                    sniffleAdd('👀', 'Nice (Password) but its not long enough 👀', 'var(--red)', 'assets/icons/cross.svg');
                </script>
            <?php
            $error = $error + 1;
        } else {
            $password = trim($_POST["password"]);
        }
        
        // Validate sussiness of the other Password
        if (empty(trim($_POST["confirm_password"]))) {
            // Did not confirm passowrd
            ?>
                <script>
                    sniffleAdd('Eh?', 'Confirm the password pls, its very important you remember what it issss', 'var(--red)', 'assets/icons/cross.svg');
                </script>
            <?php
            $error = $error + 1;
        } else {
            $confirm_password = trim($_POST["confirm_password"]);
            if (empty($error) && $confirm_password != $password) {
                // Password and re-entered Password does not match
                ?>
                    <script>
                        sniffleAdd('Try again', 'Passwords need to be the same, smelly smelly', 'var(--red)', 'assets/icons/cross.svg');
                    </script>
                <?php
                $error = $error + 1;
            }
        }
    
        // Check for invite code
        if (isset($_POST['token'])) {
            // Check if invite code is empty
            if (empty($_POST['token'])) {
                ?>
                    <script>
                        sniffleAdd('smelly', 'Enter Invite Code ;3', 'var(--red)', 'assets/icons/cross.svg');
                    </script>
                <?php
                mysqli_query($conn,"INSERT INTO logs (ipaddress, action) VALUES('$user_ip','Failed to enter correct Invite Code')");
                $error = $error + 1;
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
                            ?>
                                <script>
                                    sniffleAdd('Argh', 'Your invite code/token did not check out, woopsie!', 'var(--red)', 'assets/icons/cross.svg');
                                </script>
                            <?php
                            $error = $error + 1;
                        }
                    } else {
                        ?>
                            <script>
                                sniffleAdd('Woops', 'The server or website died inside and could not process your information, sowwy!', 'var(--red)', 'assets/icons/cross.svg');
                            </script>
                        <?php
                        $error = $error + 1;
                    }
                    // Outa here with this
                    mysqli_stmt_close($stmt);
                }
            }
        }
    }

    // Checking for errors
    if ($error <= 0) {
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
                    $new_token = substr(str_shuffle($token_array), 0, 15);
        
                    // Prepare sql
                    $sql = "INSERT INTO tokens (code, used) VALUES(?, False)";
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmt, "s", $param_new_token);
                    $param_new_token = $new_token;
                    mysqli_stmt_execute($stmt);
                }
        
                // Yupeee! Account was made
                ?>
                    <script>
                        sniffleAdd('Success!', 'You account made for <?php echo $username; ?>!!!!! You must now login', 'var(--green)', 'assets/icons/hand-waving.svg');
                        //setTimeout(function(){window.location.href = "../account/login.php";}, 2000);
                        loginShow();
                    </script>
                <?php
                mysqli_query($conn,"INSERT INTO logs (ipaddress, action) VALUES('$user_ip','New account (".$username.") has been made')");
            } else {
                ?>
                    <script>
                        sniffleAdd('Bruh', 'Something went fuckywucky, please try later', 'var(--red)', 'assets/icons/cross.svg');
                    </script>
                <?php
            }
        }
    }
}

/*
 |-------------------------------------------------------------
 | Toggle Admin
 |-------------------------------------------------------------
 | Please save me
 |-------------------------------------------------------------
*/
if (isset($_POST['toggle_admin'])) {
    if ($user_info->is_admin($conn, $_SESSION['id'])) {
        $is_admin = mysqli_query($conn, "SELECT * FROM users WHERE id = " . $_POST['id'] . " ORDER BY id DESC LIMIT 1");

        while ($user_info = mysqli_fetch_assoc($is_admin)) {
            $admin_status = $user_info['admin'];
            $username = $user_info['username'];
        }

        $sql = "UPDATE users SET admin = ? WHERE id = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ii", $param_admin_status, $param_user_id);

            // Set parameters
            if ($admin_status) {
                $param_admin_status = 0;
                $admin_update_message = "removed from the admins list";
            } elseif (!$admin_status) {
                $param_admin_status = 1;
                $admin_update_message = "added to the admins list";
            }
            $param_user_id = $_POST['id'];

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                ?>
                    <script>
                        sniffleAdd('Bap!', '<?php echo $username; ?> has been <?php echo $admin_update_message; ?>!', 'var(--green)', 'assets/icons/check.svg');
                        flyoutClose();
                    </script>
                <?php
                mysqli_query($conn,"INSERT INTO logs (ipaddress, action) VALUES('$user_ip','$username has been $admin_update_message')");
            } else {
                ?>
                    <script>
                        sniffleAdd('Bruh', 'Something went fuckywucky, please try later', 'var(--red)', 'assets/icons/cross.svg');
                        flyoutClose();
                    </script>
                <?php
            }
        } else {
            ?>
                <script>
                    sniffleAdd('Bruh', 'Something went fuckywucky, please try later', 'var(--red)', 'assets/icons/cross.svg');
                    flyoutClose();
                </script>
            <?php      
        }
    } else {
        ?>
            <script>
                sniffleAdd('Bruh', 'You\'re not an admin, you cannot!!!!', 'var(--red)', 'assets/icons/cross.svg');
                flyoutClose();
            </script>
        <?php      
    }
}


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
if (isset($_POST['password_reset_submit'])) {
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

/*
    Account deletion

    I hate dealing with stuffs being deleted
*/
if (isset($_POST['account_delete_submit'])) {
    $error = 0;

    if (isset($_POST['delete_id'])) {
        if ($_POST['delete_id'] == 1) {
            ?>
                <script>
                    sniffleAdd('Sussy', 'You cannot delete the owners account!!!!!', 'var(--red)', 'assets/icons/cross.svg');
                    flyoutClose();
                </script>
            <?php
            $error += 1;
        } elseif ($_POST['delete_id'] == $_SESSION['id'] && $_POST['delete_id'] != 1) {
            if (isset($_POST['account_password']) && !empty($_POST['account_password'])) {
                $sql = "SELECT id, username, password FROM users WHERE username = ?";

                if ($stmt = mysqli_prepare($conn, $sql)) {
                    // Bind dis shit
                    mysqli_stmt_bind_param($stmt, "s", $param_username);
                
                    // Set parameters
                    $param_username = $_SESSION['username'];
                
                    // Attempt to execute the prepared statement
                    if (mysqli_stmt_execute($stmt)) {
                        // Store result
                        mysqli_stmt_store_result($stmt);
                
                        // Check if username exists, if yes then verify password
                        if (mysqli_stmt_num_rows($stmt) == 1) {
                            // Bind result variables
                            mysqli_stmt_bind_result($stmt, $id, $_SESSION['username'], $hashed_password);
                            if (mysqli_stmt_fetch($stmt)) {
                                if (password_verify($_POST['account_password'], $hashed_password)) {
                                    $delete_id = $_SESSION['id'];
                                } else {
                                    ?>
                                        <script>
                                            sniffleAdd('Sus', 'Try again! ;3', 'var(--red)', 'assets/icons/cross.svg');
                                            flyoutClose();
                                        </script>
                                    <?php
                                    $error += 1;
                                }
                            }
                        } else {
                            ?>
                                <script>
                                    sniffleAdd('Sus', 'Try again! ;3', 'var(--red)', 'assets/icons/cross.svg');
                                    flyoutClose();
                                </script>
                            <?php
                            $error += 1;
                        }
                    } else {
                        ?>
                            <script>
                                sniffleAdd('AAA', 'Something went wrong on our end, sowwy', 'var(--red)', 'assets/icons/cross.svg');
                                flyoutClose();
                            </script>
                        <?php
                        $error += 1;
                    }
                }
            } else {
                ?>
                    <script>
                        sniffleAdd('oof', 'You did not enter a password!', 'var(--red)', 'assets/icons/cross.svg');
                        flyoutClose();
                    </script>
                <?php
                $error += 1;
            }
        } elseif ($_POST['delete_id'] != $_SESSION['id'] && $_SESSION['id'] == 1) {
            $delete_id = $_POST['delete_id'];
        } else {
            ?>
                <script>
                    sniffleAdd('Ono', 'You aren\'t privilaged enough to delete accounts!', 'var(--red)', 'assets/icons/cross.svg');
                    flyoutClose();
                </script>
            <?php
            $error += 1;
        }
    } else {
        ?>
            <script>
                sniffleAdd('Oopsie', 'We couldn\'t find the account that was requested to be deleted', 'var(--red)', 'assets/icons/cross.svg');
                flyoutClose();
            </script>
        <?php
        $error += 1;
    }

    if (empty($_POST['full']) || !isset($_POST['full'])) {
        ?>
            <script>
                sniffleAdd('Oopsie', 'Some error occured, unsure what to delete', 'var(--red)', 'assets/icons/cross.svg');
                flyoutClose();
            </script>
        <?php
        $error += 1;
    }

    if ($error <= 0) {
        if ($_POST['full'] == "true") {
            $image_request = mysqli_query($conn, "SELECT id, imagename FROM images WHERE author = '$delete_id'");

            while ($image = mysqli_fetch_array($image_request)) {
                if (is_file(dirname(__DIR__)."/images/".$image['imagename'])) {
                    unlink(dirname(__DIR__)."/images/".$image['imagename']);
                }
                if (is_file(dirname(__DIR__)."/images/thumbnails/".$image['imagename'])) {
                    unlink(dirname(__DIR__)."/images/thumbnails/".$image['imagename']);
                }
                if (is_file(dirname(__DIR__)."/images/previews/".$image['imagename'])) {
                    unlink(dirname(__DIR__)."/images/previews/".$image['imagename']);
                }

                mysqli_query($conn, "DELETE FROM images WHERE id = ".$image['id']);
            }

            ?>
                <script>
                    sniffleAdd('Progress', 'Deleted all images from the user', 'var(--green)', 'assets/icons/warning.svg');
                    flyoutClose();
                </script>
            <?php
        }
        
        mysqli_query($conn, "DELETE FROM users WHERE id = ".$delete_id);

        if ($_POST['full'] == "true") {
            mysqli_query($conn,"INSERT INTO logs (ipaddress, action) VALUES('$user_ip','Deleted a user account and all their posts')");
        } else {
            mysqli_query($conn,"INSERT INTO logs (ipaddress, action) VALUES('$user_ip','Deleted a user account')");
        }

        if ($_POST['delete_id'] == $_SESSION['id']) {
            ?>
                <script>
                    sniffleAdd('Goodbye!', 'Successfully deleted your account! You will be redirected in a few seconds...', 'var(--green)', 'assets/icons/check.svg');
                    flyoutClose();

                    setTimeout(function(){window.location.href = "app/account/logout.php";}, 2000);
                </script>
            <?php
        } else {
            ?>
                <script>
                    sniffleAdd('Goodbye!', 'Successfully deleted the user!', 'var(--green)', 'assets/icons/check.svg');
                    flyoutClose();
                </script>
            <?php
        }
    }
}