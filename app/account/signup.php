<?php
/*
 |-------------------------------------------------------------
 | Signup
 |-------------------------------------------------------------
 | The dreaded signup. Funfact, the one thing I hate more than
 | late-stage capitalism and this world is JavaScript. Please
 | save me...
 |-------------------------------------------------------------
*/
// Include server connection
include "../server/conn.php";

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

    if (empty(trim($_POST["username"]))) {
        // Username not entered
        ?>
        <script>
            sniffleAdd('Hmmm', 'You must enter a username!', 'var(--red)', '../assets/icons/cross.svg');
        </script>
        <?php
        $error = $error + 1;
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))) {
        // Username entered contains illegal characters
        ?>
        <script>
            sniffleAdd('Sussy Wussy', 'Very sus. Username can only contain letters, numbers, and underscores', 'var(--red)', '../assets/icons/cross.svg');
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
                        sniffleAdd('A clone?', 'Sorry, but username was already taken by someone else', 'var(--red)', '../assets/icons/cross.svg');
                    </script>
                    <?php
                    $error = $error + 1;
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                ?>
                <script>
                    sniffleAdd('Reee', 'We had a problem on our end, sowwy', 'var(--red)', '../assets/icons/cross.svg');
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
            sniffleAdd('What', 'You must enter a password, dont want just anyone seeing your stuff uwu', 'var(--red)', '../assets/icons/cross.svg');
        </script>
        <?php
        $error = $error + 1;
    } elseif(strlen(trim($_POST["password"])) < 6){
        // Password not long enough 👀
        ?>
        <script>
            sniffleAdd('👀', 'Nice (Password) but its not long enough 👀', 'var(--red)', '../assets/icons/cross.svg');
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
            sniffleAdd('Eh?', 'Confirm the password pls, its very important you remember what it issss', 'var(--red)', '../assets/icons/cross.svg');
        </script>
        <?php
        $error = $error + 1;
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($error) && $confirm_password != $password) {
            // Password and re-entered Password does not match
            ?>
            <script>
                sniffleAdd('Try again', 'Passwords need to be the same, smelly smelly', 'var(--red)', '../assets/icons/cross.svg');
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
                sniffleAdd('smelly', 'Enter Invite Code ;3', 'var(--red)', '../assets/icons/cross.svg');
            </script>
            <?php
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
                            sniffleAdd('Argh', 'Your invite code/token did not check out, woopsie!', 'var(--red)', '../assets/icons/cross.svg');
                        </script>
                        <?php
                        $error = $error + 1;
                    }
                } else {
                    ?>
                    <script>
                        sniffleAdd('Woops', 'The server or website died inside and could not process your information, sowwy!', 'var(--red)', '../assets/icons/cross.svg');
                    </script>
                    <?php
                    $error = $error + 1;
                }
                // Outa here with this
                mysqli_stmt_close($stmt);
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
                    sniffleAdd('Success!', 'You account made for <?php echo $username; ?>!!!!! You will be redirected in a moment to login', 'var(--green)', '../assets/icons/hand-waving.svg');
                    setTimeout(function(){window.location.href = "../account/login.php";}, 4000);
                </script>
                <?php
            } else {
                ?>
                <script>
                    sniffleAdd('Bruh', 'Something went fuckywucky, please try later', 'var(--red)', '../assets/icons/cross.svg');
                </script>
                <?php
            }
        }
    }
}