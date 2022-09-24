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
                            setTimeout(function() {
                                window.location.href = "group.php?id=<?php echo $_POST['group_id']; ?>";
                            }, 2000);
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

/*
 |-------------------------------------------------------------
 | Edit Description
 |-------------------------------------------------------------
 | This script took probably over 24hours to write, mostly
 | because of my stupidity. But it (mostly) works now which is
 | good. Reason for all the includes and session_start is due
 | to the need of checking if the person owns the image. If this
 | check is not done, someone could come by and just edit the
 | Jquery code on the front-end and change the image ID. Which
 | isnt too great :p
 |-------------------------------------------------------------
*/
if (isset($_POST['title_submit'])) {
    $sql = "SELECT author FROM groups WHERE id= ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_user_id);

        $param_user_id = $_POST['group_id'];

        $stmt->execute();
        $query = $stmt->get_result();

        if ($_SESSION['id'] == $query || $user_info->is_admin($conn, $_SESSION['id'])) {
            // getting ready forSQL asky asky
            $sql = "UPDATE groups SET group_name = ? WHERE id = ?";

            // Checking if databse is doing ok
            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "si", $param_title, $param_id);

                // Setting parameters
                $param_title = $_POST['group_title'];
                $param_id = $_POST['group_id'];

                // Attempt to execute the prepared statement
                if (mysqli_stmt_execute($stmt)) {
                    ?>
                        <script>
                            sniffleAdd('Success!!!', 'The title has been updated successfully! You may need to refresh the page to see the new information.', 'var(--green)', 'assets/icons/check.svg');
                            flyoutClose();
                        </script>
                    <?php
                } else {
                    ?>
                        <script>
                            sniffleAdd('Error :c', 'An error occured on the servers', 'var(--red)', 'assets/icons/cross.svg');
                            flyoutClose();
                        </script>
                    <?php
                }
            } else {
                ?>
                    <script>
                        sniffleAdd('Error :c', 'An error occured on the servers', 'var(--red)', 'assets/icons/cross.svg');
                        flyoutClose();
                    </script>
                <?php
            }
        } else {
            ?>
                <script>
                    sniffleAdd('Denied', 'It seems that you do not have the right permitions to edit this image.', 'var(--red)', 'assets/icons/cross.svg');
                    flyoutClose();
                </script>
            <?php
        }
    }
}

if (isset($_POST['new_group_submit'])) {
    if ($user_info->is_loggedin()) {        
        $group_name = $_SESSION['username']." new image group";
        $sql = "INSERT INTO groups (group_name, author, image_list) VALUES('$group_name', '".$_SESSION['id']."', '')";
        
        mysqli_query($conn, $sql);

        $group_id = mysqli_insert_id($conn);

        ?>
            <script>
                window.location.href = "group.php?id=<?php echo $group_id; ?>";
            </script>
        <?php
    } else {
        ?>
            <script>
                sniffleAdd('Denied', 'You must have an account to preform this action!', 'var(--red)', 'assets/icons/cross.svg');
            </script>
        <?php
    }
}
