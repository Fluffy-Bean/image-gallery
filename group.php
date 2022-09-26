<?php
    require_once __DIR__."/app/required.php";
    
    use App\Account;
    use App\Image;
    use App\Group;
	use App\Diff;

	$user_info = new Account;
    $image_info = new Image;
    $group_info = new Group;
    $diff = new Diff();

    if (isset($_GET['id'])) {
        $group = $group_info->get_group_info($conn, $_GET['id']);

        if (!isset($group) || empty($group)) {
            header("Location: group.php");
            $_SESSION['err'] = "You followed a broken link";
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <?php require_once __DIR__."/assets/ui/header.php"; ?>
    </head>
<body>
	<?php
        require_once __DIR__."/assets/ui/nav.php";

        if (isset($_SESSION['msg'])) {
            ?>
                <script>
                    sniffleAdd("Info", "<?php echo $_SESSION['msg']; ?>", "var(--green)", "assets/icons/check.svg");
                </script>
            <?php
            unset($_SESSION['msg']);
        }    
    ?>

    <?php
        if (isset($_GET['id'])) {
            $image_list = array_reverse(explode(" ", $group['image_list']));

            echo "<div class='group-banner defaultDecoration defaultSpacing defaultFonts'>
            <div class='group-description'>";

            $author_info = $user_info->get_user_info($conn, $group['author']);
            echo "<h2>".$group['group_name']."<span>by</span><a href='profile.php?user=".$author_info['id']."'>".$author_info['username']."</a></h2>";

            $group_members = $group_info->get_group_members($conn, $_GET['id']);
            if (!empty($group_members)) {
                $members_array = array();
                foreach ($group_members as $member) {
                    $member_info = $user_info->get_user_info($conn, $member);
                    if (!empty($member_info['username'])) $members_array[] = "<a href='profile.php?user=".$member_info['id']."'>".$member_info['username']."</a>";
                }
                echo "<p>Featured: ".implode(", ", $members_array)."</p>";
            }

            if (!empty($group['image_list'])) echo "<p>Images: ".count(explode(" ", $group['image_list']))."</p>";

            $upload_time = new DateTime($group['created_on']);
            echo "<p id='updateTime'>Created at: ".$upload_time->format('d/m/Y H:i:s T')."</p>";
            ?>
                <script>
                    var updateDate = new Date('<?php echo $upload_time->format('m/d/Y H:i:s T'); ?>');
                    updateDate = updateDate.toLocaleDateString('en-GB', {year: 'numeric', month: 'short', day: 'numeric'});
                    $("#updateTime").html("Created at: "+updateDate);
                </script>
            <?php

            echo "<p>Last Modified: ".$diff->time($group['last_modified'])."</p>";

            if ($_GET['mode'] == "edit") {
                if ($_SESSION['id'] == $group['author'] || $user_info->is_admin($conn, $_SESSION['id'])) {
                    echo "<button id='deleteGroup' class='btn btn-bad'>Delete</button>";
                    ?>
                        <script>
                            $('#deleteGroup').click(function() {
                                var header = "Are you surrrrrre?";
                                var description = "The images will still be up, but all your hard work setting this group up will be gone!";
                                var actionBox = "<form id='titleForm' method='POST'>\
                                <button id='deleteSubmit' class='btn btn-bad' type='submit'><img class='svg' src='assets/icons/trash.svg'>Delete group</button>\
                                </form>";
                                flyoutShow(header, description, actionBox);
                                
                                $("#titleForm").submit(function(event) {
                                    event.preventDefault();
                                    var deleteSubmit = $("#deleteSubmit").val();
                                    $("#sniffle").load("app/image/group.php", {
                                        group_id: <?php echo $_GET['id']; ?>,
                                        group_delete: deleteSubmit
                                    });
                                });
                            });
                        </script>
                    <?php

                    echo "<button id='editTitle' class='btn btn-bad'>Update title</button>";
                    ?>
                        <script>
                            $('#editTitle').click(function() {
                                var header = "Newwww photo group name!";
                                var description = "What will it be? uwu";
                                var actionBox = "<form id='titleForm' action='app/image/edit_description.php' method='POST'>\
                                <input id='titleText' class='btn btn-neutral' type='text' placeholder='New title'>\
                                <button id='titleSubmit' class='btn btn-bad' type='submit'><img class='svg' src='assets/icons/edit.svg'>Update title</button>\
                                </form>";
                                flyoutShow(header, description, actionBox);
                                
                                $("#titleForm").submit(function(event) {
                                    event.preventDefault();
                                    var titleText = $("#titleText").val();
                                    var titleSubmit = $("#titleSubmit").val();
                                    $("#sniffle").load("app/image/group.php", {
                                        group_id: <?php echo $_GET['id']; ?>,
                                        group_title: titleText,
                                        title_submit: titleSubmit
                                    });
                                });
                            });
                        </script>
                    <?php

                    $image_request = mysqli_query($conn, "SELECT * FROM images");

                    echo "<form id='groupForm'>"; 
                    while ($image = mysqli_fetch_array($image_request)) {
                        if (in_array($image['id'], $image_list)) {
                            echo "<input style='display: none;' type='checkbox' id='".$image['id']."' name='".$image['id']."' checked/>";
                        } else {
                            echo "<input style='display: none;' type='checkbox' id='".$image['id']."' name='".$image['id']."'/>";
                        } 
                    }
                    echo "<button id='groupSubmit' class='btn btn-good' type='submit'>Save changes</button>
                    </form>";

                    echo "<a href='group.php?id=".$_GET['id']."' class='btn btn-neutral'>Back</a>";
                }
            } else {
                if ($_SESSION['id'] == $group['author'] || $user_info->is_admin($conn, $_SESSION['id'])) {
                    echo "<a href='group.php?id=".$_GET['id']."&mode=edit' class='btn btn-neutral'>Edit</a>";
                }
            }

            echo "</div>";

            $cover_image = $image_info->get_image_info($conn, $image_list[array_rand($image_list, 1)]);
            if (!empty($cover_image['imagename'])) {
                ?>
                    <div class='group-cover'>
                    <span></span>
                    <img <?php if(str_contains($cover_image['tags'], "nsfw")) echo "class='nsfw-blur'"; ?> src='images/<?php echo $cover_image['imagename']; ?>'/>
                    </div>
                <?php
            }

            echo "</div>";
        } else {
            if (isset($_SESSION['err'])) {
                ?>
                    <script>
                        sniffleAdd("Error", "<?php echo $_SESSION['msg']; ?>", "var(--red)", "assets/icons/trash.svg");
                    </script>
                <?php
                unset($_SESSION['err']);
            }
        }
    ?>

    <?php 
    if (empty($group['image_list']) && $_GET['mode'] != "edit" && !empty($_GET['id'])) {
        echo "<div id='gallery' class='gallery-root defaultDecoration' style='display: none;'>";
    } else {
        echo "<div id='gallery' class='gallery-root defaultDecoration' >";
    }
            if (isset($_GET['id']) && !empty($_GET['id'])) {
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

                                checkedBoxes.forEach(element => { images.push(element.id); });

                                return images;
                            }

                            $("#groupForm").submit(function(event) {
                                event.preventDefault();

                                var groupSubmit = $("#groupSubmit").val();
                                var images = getList();

                                if (images <= 0) {
                                    sniffleAdd('Oppsie', 'Groups need at least 1 image in them. Alternativly, you can delete this group.', 'var(--red)', 'assets/icons/cross.svg');
                                } else {
                                    $("#sniffle").load("app/image/group.php", {
                                        group_images: images,
                                        group_id: <?php echo $_GET['id']; ?>,
                                        group_submit: groupSubmit
                                    });
                                }
                            });
                        </script>
                    <?php
                } else {
                    foreach ($image_list as $image) {
                        // Reading images from table
                        try {
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
                        } catch(Exception $e) {
                            $e;
                        }
                    }
                }
            } elseif (!isset($_GET['id']) && empty($_GET['id'])) {
                if ($_SESSION["loggedin"]) {
                    echo "<div class='group-make'>
                        <button id='createGroup'><img class='svg' src='assets/icons/plus.svg'><span>Make new group</span></button>
                        </div>";

                    ?>
                        <script>
                            $('#createGroup').click(function() {                                
                                $("#sniffle").load("app/image/group.php", {
                                    new_group_submit: "uwu"
                                });
                            });
                        </script>
                    <?php
                }

                $group_list = mysqli_query($conn, "SELECT * FROM groups ORDER BY id DESC");

                foreach ($group_list as $group) {
                    $image_list = array_reverse(explode(" ", $group['image_list']));
                    $image = $image_info->get_image_info($conn, $image_list[array_rand($image_list, 1)]);

                    // Getting thumbnail
                    if (!empty($image['imagename'])) {
                        if (file_exists("images/thumbnails/".$image['imagename'])) {
                            $image_path = "images/thumbnails/".$image['imagename'];
                        } else {
                            $image_path = "images/".$image['imagename'];
                        } 
                    } else {
                        $image_path = "assets/no_image.png";
                    }
                    

                    // Check for NSFW tag
                    if (str_contains($image['tags'], "nsfw")) {
                        echo "<div class='gallery-item group-item'>
                            <a href='group.php?id=".$group['id']."' class='nsfw-warning gallery-group'><img class='svg' src='assets/icons/warning_red.svg'><span>NSFW</span></a>
                            <a href='group.php?id=".$group['id']."'><img class='gallery-image nsfw-blur' loading='lazy' src='".$image_path."' id='".$group['id']."'></a>
                            <a href='group.php?id=".$group['id']."' class='group-name'>".$group['group_name']."</a>
                            </div>";
                    } else {
                        echo "<div class='gallery-item group-item'>
                            <a href='group.php?id=".$group['id']."' class='gallery-group'></a>
                            <a href='group.php?id=".$group['id']."'><img class='gallery-image' loading='lazy' src='".$image_path."' id='".$group['id']."'></a>
                            <a href='group.php?id=".$group['id']."' class='group-name'>".$group['group_name']."</a>
                            </div>";
                    }
                }
            }
        ?>
    </div>

    <?php require_once __DIR__."/assets/ui/footer.php"; ?>
</body>
</html>