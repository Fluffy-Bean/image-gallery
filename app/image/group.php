<?php
session_start();
// Include server connection
include dirname(__DIR__) . "/server/conn.php";
include dirname(__DIR__) . "/app.php";

use App\Account;
use App\Image;

$user_info = new Account();
$image_info = new Image();

$user_ip = $user_info->get_ip();

/*
 |-------------------------------------------------------------
 | Image Groups
 |-------------------------------------------------------------
 | The Long-awaited feature
 |-------------------------------------------------------------
*/
if (isset($_POST['group_submit'])) {
    $sql = "SELECT author FROM groups WHERE id= ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_user_id);
        
        $param_user_id = $_POST['group_id'];
        
        $stmt->execute();
        $query = $stmt->get_result();

        if ($_SESSION['id'] == $query || $user_info->is_admin($conn, $_SESSION['id'])) {
            $sql = "UPDATE groups SET image_list = ? WHERE id = ?";

            // Checking if databse is doing ok
            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "si", $param_images, $param_id);

                // Setting parameters
                $param_images = implode(" ", $_POST['group_images']);
                $param_id = $_POST['group_id'];

                // Attempt to execute the prepared statement
                if (mysqli_stmt_execute($stmt)) {
                    ?>
                        <script>
                            sniffleAdd('Success!!!', 'Updates the image group! Redirecting.... soon', 'var(--green)', 'assets/icons/check.svg');
                            setTimeout(function(){window.location.href = "group.php?id=<?php echo $_POST['group_id']; ?>";}, 2000);
                        </script>
                    <?php
                } else {
                    ?>
                        <script>
                            sniffleAdd('Oopsie....', 'An error occured on the servers', 'var(--red)', 'assets/icons/cross.svg');
                        </script>
                    <?php
                }
            }
        } else {
            ?>
                <script>
                    sniffleAdd('Gwa Gwa', 'You\'re not privilaged enough to do thissss!', 'var(--red)', 'assets/icons/cross.svg');
                </script>
            <?php
        }
    }
}
