<?php
session_start();
// Include server connection
include dirname(__DIR__) . "/conn.php";
include dirname(__DIR__) . "/app.php";

use App\Account;
use App\Image;
use App\Group;

$user_info = new Account();
$image_info = new Image();
$group_info = new Group();

$user_ip = $user_info->get_ip();

/*
 |-------------------------------------------------------------
 | Image Groups
 |-------------------------------------------------------------
 | The Long-awaited feature
 |-------------------------------------------------------------
*/
if (isset($_POST['group_submit'])) {
    $query = $group_info->get_group_info($conn, $_POST['group_id']);

    if ($_SESSION['id'] == $query['author'] || $user_info->is_admin($conn, $_SESSION['id'])) {
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
                        window.location.href = "group.php?id=<?php echo $_POST['group_id']; ?>";
                    </script>
                <?php
                $_SESSION['msg'] = "Updated the image group!";
            } else {
                ?>
                    <script>
                        sniffleAdd('Oopsie....', 'An error occured on the servers', 'var(--warning)', 'assets/icons/cross.svg');
                    </script>
                <?php
            }
        }
    } else {
        ?>
            <script>
                sniffleAdd('Gwa Gwa', 'You\'re not privilaged enough to do thissss!', 'var(--warning)', 'assets/icons/cross.svg');
            </script>
        <?php
    }
}

/*
 |-------------------------------------------------------------
 | Edit title
 |-------------------------------------------------------------
 | 
 |-------------------------------------------------------------
*/
if (isset($_POST['title_submit'])) {
    $query = $group_info->get_group_info($conn, $_POST['group_id']);
    
    if ($_SESSION['id'] == $query['author'] || $user_info->is_admin($conn, $_SESSION['id'])) {
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
                        sniffleAdd('Success!!!', 'The title has been updated successfully! You may need to refresh the page to see the new information.', 'var(--success)', 'assets/icons/check.svg');
                        flyoutClose();
                    </script>
                <?php
            } else {
                ?>
                    <script>
                        sniffleAdd('Error :c', 'An error occured on the servers', 'var(--warning)', 'assets/icons/cross.svg');
                        flyoutClose();
                    </script>
                <?php
            }
        } else {
            ?>
                <script>
                    sniffleAdd('Error :c', 'An error occured on the servers', 'var(--warning)', 'assets/icons/cross.svg');
                    flyoutClose();
                </script>
            <?php
        }
    } else {
        ?>
            <script>
                sniffleAdd('Denied', 'It seems that you do not have the right permitions to edit this image.', 'var(--warning)', 'assets/icons/cross.svg');
                flyoutClose();
            </script>
        <?php
    }
}

/*
 |-------------------------------------------------------------
 | New Group
 |-------------------------------------------------------------
 | 
 |-------------------------------------------------------------
*/
if (isset($_POST['new_group_submit'])) {
    if ($user_info->is_loggedin($conn)) {        
        $group_name = "New Group";
        $sql = "INSERT INTO groups (group_name, author, image_list) VALUES('$group_name', '".$_SESSION['id']."', '')";
        
        mysqli_query($conn, $sql);

        $group_id = mysqli_insert_id($conn);

        ?>
            <script>
                window.location.href = "group.php?id=<?php echo $group_id; ?>";
            </script>
        <?php
        $_SESSION['msg'] = "New Group successfully made!";
    } else {
        ?>
            <script>
                sniffleAdd('Denied', 'You must have an account to preform this action!', 'var(--warning)', 'assets/icons/cross.svg');
            </script>
        <?php
    }
}

/*
 |-------------------------------------------------------------
 | Delete Group
 |-------------------------------------------------------------
 | 
 |-------------------------------------------------------------
*/
if (isset($_POST['group_delete'])) {
    $query = $group_info->get_group_info($conn, $_POST['group_id']);
    
    if ($_SESSION['id'] == $query['author'] || $user_info->is_admin($conn, $_SESSION['id'])) {
        $sql =  "DELETE FROM groups WHERE id = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $_POST['group_id']);
            
            if ($stmt->execute()) {
                ?>
                    <script>
                        flyoutClose();
                        setTimeout(function(){window.location.href = "group.php";}, 500);
                    </script>
                <?php
                $_SESSION['msg'] = "Group successfully yeeted out";
            } else {
                ?>
                    <script>
                        sniffleAdd('Ouchie', 'Something went wrong while deleting the group', 'var(--warning)', 'assets/icons/cross.svg');
                        flyoutClose();
                    </script>
                <?php
            }
        } else {
            ?>
                <script>
                    sniffleAdd('Ouchie', 'Something went wrong while deleting the image group', 'var(--warning)', 'assets/icons/cross.svg');
                    flyoutClose();
                </script>
            <?php
        }
    } else {
        ?>
            <script>
                sniffleAdd('Denied!!!', 'You do not have the right permitions to delete this group', 'var(--warning)', 'assets/icons/cross.svg');
                flyoutClose();
            </script>
        <?php
    }
}