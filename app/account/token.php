<?php
include __DIR__ . "/../conn.php";
include __DIR__ . "/../app.php";

use App\Account;

$user_info	= new Account();

if (isset($_POST['regenerate'])) {
    if ($user_info->is_admin($conn, $_SESSION['id'])) {
        // Prepare sql
        $sql = "UPDATE tokens SET used = True WHERE code = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $param_token);
        $param_token = $_POST['regenerate'];

        if (mysqli_stmt_execute($stmt)) {
            // Generate Token
            $token_array = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
            $new_token = substr(str_shuffle($token_array), 0, 15);
            
            if (mysqli_query($conn, "INSERT INTO tokens (code, used) VALUES('$new_token', 0)")) {
                echo true;
            } else {
                echo false;
            }
        } else {
            echo false;
        }
    }
}

if (isset($_POST['generate'])) {
    if ($user_info->is_admin($conn, $_SESSION['id'])) {
        // Generate Token
        $token_array = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
        $new_token = substr(str_shuffle($token_array), 0, 15);
        
        if (mysqli_query($conn, "INSERT INTO tokens (code, used) VALUES('$new_token', 0)")) {
            echo true;
        } else {
            echo false;
        }
    }
}

if (isset($_POST['delete'])) {
    if ($user_info->is_admin($conn, $_SESSION['id'])) {
        // Prepare sql
        $sql = "UPDATE tokens SET used = True WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $param_token);
        $param_token = $_POST['delete'];

        if (mysqli_stmt_execute($stmt)) {
            echo true;
        } else {
            echo false;
        }
    }
}

if (isset($_POST['refresh'])) {
    if ($user_info->is_admin($conn, $_SESSION['id'])) {
        $request = mysqli_query($conn, "SELECT * FROM tokens WHERE used = 0");
        if (mysqli_num_rows($request) > 0) {
            while ($token = mysqli_fetch_array($request)) {
                ?>
                    <div style="display: flex; flex-direction: row; gap: 0.5rem;">
                        <button onclick='copyCode(this)' class='btn btn-neutral btn-code'><?php echo $token['code']; ?></button>
                        <button onclick='regenerateCode("<?php echo $token["code"]; ?>", this)' class='btn btn-good btn-icon'><img src="assets/icons/arrow-clockwise.svg"></button>
                        <button onclick='deleteCode(<?php echo $token["id"]; ?>)' class='btn btn-bad btn-icon'><img src="assets/icons/cross.svg"></button>
                    </div>
                <?php
            }
        } else {
            echo "<div class='info-text defaultFonts' style='text-align: center !important; margin-left: 0 !important;'>
                <p>No invite codes/tokens :c</p>
            </div>";
        }
    }
}
