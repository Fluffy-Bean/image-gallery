<?php
    require_once __DIR__."/app/required.php";
    
    use App\Account;
	use App\Diff;

	$user_info = new Account;
	$diff = new Diff();
?>

<!DOCTYPE html>
<html>

<head>
	<?php require_once __DIR__."/assets/ui/header.php"; ?>
</head>

<body>
	<?php require_once __DIR__."/assets/ui/nav.php"; ?>

    <div class="group-root">
        <?php
            if (!isset($_GET['id']) || empty($_GET['id'])) {
                header("Location: index.php");
            } elseif (isset($_GET['id'])) {
                $sql = "SELECT * FROM groups WHERE id = ?";

                if ($stmt = mysqli_prepare($conn, $sql)) {
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "i", $param_group_id);
                    
                    $param_group_id = $_GET['id'];
                    
                    $stmt->execute();
                    $query = $stmt->get_result();

                    $group = mysqli_fetch_array($query);

                    $image_list = explode(" ", $group['image_list']);
                }
            }
        ?>
        <h2><?php echo $group['group_name']; ?></h2>
        <p>By: <?php echo $group['author']; ?></p>
        <p>Made on: <?php echo $group['created_at']; ?></p>
        <p>Updated on: <?php echo $group['last_modified']; ?></p>
        <?php
            if ($_SESSION['id'] == $group['author'] || $user_info->is_admin($conn, $_SESSION['id'])) {
                $privilaged = True;
            } else {
                $privilaged = False;
            }

            if (isset($_GET['mode']) && $_GET['mode'] == "edit") {
                if (!$privilaged) header("Location: group.php?id=".$_GET['id']);

                echo "<button class='btn btn-bad'>Delete Group</button>";

                $image_request = mysqli_query($conn, "SELECT * FROM images");
                echo "<form id='groupForm'>"; 
                while ($image = mysqli_fetch_array($image_request)) {
                    if (in_array($image['id'], $image_list)) {
                        echo "<input style='display: none;' type='checkbox' id='".$image['id']."' name='".$image['id']."' checked/>";
                    } else {
                        echo "<input style='display: none;' type='checkbox' id='".$image['id']."' name='".$image['id']."'/>";
                    } 
                }
                echo "<button id='groupSubmit' class='btn btn-good' type='submit'>Save Group</button></form>";

                echo "<a href='group.php?id=".$_GET['id']."' class='btn btn-neutral'>Cancel</a>";
            } else {
                if ($privilaged) echo "<a href='group.php?id=".$_GET['id']."&mode=edit' class='btn btn-neutral'>Edit</a>";
            }
        ?>
    </div>

    <div class="gallery-root">
        <?php
            if (isset($_GET['mode']) && $_GET['mode'] == "edit") {                
                $image_request = mysqli_query($conn, "SELECT * FROM images ORDER BY id DESC");

                while ($image = mysqli_fetch_array($image_request)) {
                    // Getting thumbnail
                    if (file_exists("images/thumbnails/".$image['imagename'])) {
                        $image_path = "images/thumbnails/".$image['imagename'];
                    } else {
                        $image_path = "images/".$image['imagename'];
                    }
                    
                    if (in_array($image['id'], $image_list)) {
                        echo "<div id='".$image['id']."' class='gallery-item selectedImage'>
                            <img class='gallery-image' loading='lazy' src='".$image_path."' id='".$image['id']."'>
                            </div>";
                    } else {
                        echo "<div id='".$image['id']."' class='gallery-item'>
                            <img class='gallery-image' loading='lazy' src='".$image_path."' id='".$image['id']."'>
                            </div>";
                    } 
                }

                ?>
                    <script>
                        $(".gallery-item").click(function() {
                            if (this.classList.contains("selectedImage")) {
                                deselect(this);
                            } else {
                                select(this);
                            }
                        });

                        function select(item) {
                            document.getElementById(item.id).checked = true;

                            item.classList.add("selectedImage");
                        }
                        function deselect(item) {
                            document.getElementById(item.id).checked = false;

                            item.classList.remove("selectedImage");
                        }

                        function getList() {
                            var checkedBoxes = document.querySelectorAll('input[type=checkbox]:checked');
                            var images = [];

                            checkedBoxes.forEach(element => {
                                images.push(element.id);
                            });

                            return images;
                        }

                        $("#groupForm").submit(function(event) {
                            event.preventDefault();

                            var groupSubmit = $("#groupSubmit").val();
                            var images = getList();

                            $("#sniffle").load("app/image/group.php", {
                                group_images: images,
                                group_id: <?php echo $_GET['id']; ?>,
                                group_submit: groupSubmit
                            });
                        });
                    </script>
                <?php
            } else {
                foreach ($image_list as $image) {
                    // Reading images from table
                    $image_request = mysqli_query($conn, "SELECT * FROM images WHERE id = ".$image);
    
                    while ($image = mysqli_fetch_array($image_request)) {
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
            }
        ?>
    </div>

    <?php require_once __DIR__."/assets/ui/footer.php"; ?>
</body>
</html>