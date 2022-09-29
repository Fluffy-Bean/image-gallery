<?php
    require_once __DIR__."/app/required.php"; 

    use App\Make;
	use App\Account;
	use App\Diff;

	$make_stuff = new Make();
    $user_info = new Account();
    $diff = new Diff();

    if (!isset($_GET['user']) || empty($_GET['user'])) {
        header("Location: index.php");
    } elseif (isset($_GET['user'])) {
        $user = $user_info->get_user_info($conn, $_GET['user']);

    $join_date = new DateTime($user['created_at']);
?>

<!DOCTYPE html>
<html>

<head>
	<?php require_once __DIR__."/assets/ui/header.php"; ?>
</head>

<body>
	<?php require_once __DIR__."/assets/ui/nav.php"; ?>

        <div class="profile-root defaultDecoration defaultSpacing defaultFonts">
            <?php
                if (!empty($user)) {
                    if (is_file("images/pfp/".$user['pfp_path'])) {
                        echo "<img src='images/pfp/".$user['pfp_path']."'>";

                        $pfp_colour = $make_stuff->get_image_colour("images/pfp/".$user['pfp_path']);
                        if (!empty($pfp_colour)) {
                            $pfp_colour = $pfp_colour;
                        } else {
                            $pfp_colour = "var(--bg-3)";
                        }
                        ?>
                            <style>
                                .profile-root {
                                    background-image: linear-gradient(to right, <?php echo $pfp_colour; ?>, var(--bg-3), var(--bg-3)) !important;
                                }
                            </style>
                        <?php
                    } else {
                        echo "<img src='assets/no_image.png'>";
                    }
                    ?>
                        <h2>
                            <?php
                                echo $user['username'];
                                if ($user_info->is_admin($conn, $user['id'])) echo "<span style='color: var(--accent); font-size: 16px; margin-left: 0.5rem;'>Admin</span>";
                            ?>
                        </h2>
                        <div class="profile-info">
                            <p id="joinDate">Member since: <?php echo $join_date->format('d/m/Y T'); ?></p>
                            <p id="postCount"></p>
                        </div>
                        
                    <?php
                } else {
                    echo "<img src='assets/no_image.png'>
                    <h2>Failed to load user info</h2>";
                }
            ?>
        </div>

        <div id="gallery" class="gallery-root defaultDecoration">
            <?php

                // Reading images from table
                $sql = "SELECT * FROM images WHERE author = ? ORDER BY id DESC";

                if ($stmt = mysqli_prepare($conn, $sql)) {
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "i", $param_user_id);
                    
                    $param_user_id = $_GET['user'];
                    
                    $stmt->execute();
                    $query = $stmt->get_result();
                    
                    while ($image = mysqli_fetch_array($query)) {
                        // Getting thumbnail
                        if (file_exists("images/thumbnails/".$image['imagename'])) {
                            $image_path = "images/thumbnails/".$image['imagename'];
                        } else {
                            $image_path = "images/".$image['imagename'];
                        }
    
                        // Check for NSFW tag
                        if (str_contains($image['tags'], "nsfw")) {
                            echo "<div class='gallery-item'>
                                <a href='image.php?id=".$image['id']."' class='nsfw-warning'><img class='svg' src='assets/icons/warning_red.svg'><span>NSFW</span></a>
                                <a href='image.php?id=".$image['id']."'><img class='gallery-image nsfw-blur' loading='lazy' src='".$image_path."' id='".$image['id']."'></a>
                                </div>";
                        } else {
                            echo "<div class='gallery-item'>
                                <a href='image.php?id=".$image['id']."'><img class='gallery-image' loading='lazy' src='".$image_path."' id='".$image['id']."'></a>
                                </div>";
                        }			
                    }
                }
            ?>
        </div>

        <script>
            var postCount = $("#gallery").children().length;
            $("#postCount").html("Posts: "+postCount);

            var updateDate = new Date('<?php echo $join_date->format('m/d/Y T'); ?>');
            var format = {year: 'numeric', month: 'short', day: 'numeric'};
                            
            updateDate = updateDate.toLocaleDateString('en-GB', format);

            $("#joinDate").html("Member since: "+updateDate);
        </script>

    <?php
        }

        require_once __DIR__."/assets/ui/footer.php";
    ?>
</body>
</html>
