<?php
if (isset($_GET['q']) && !empty($_GET['q'])) {
  // Make search into an array
  $search_array = explode(" ", $_GET['q']);

  // Get images tags for comparing
  $image_tag_array = explode(" ", $image['tags']);

  // Compare arrays
  $compare_results = array_intersect($image_tag_array, $search_array);
  if (count($compare_results) > 0) {
    // Getting thumbnail
    if (file_exists("images/thumbnails/".$image['imagename'])) {
      $image_path = "images/thumbnails/".$image['imagename'];
    } else {
      $image_path = "images/".$image['imagename'];
    }

    // Image loading
    echo "<div class='gallery-item'>";
    echo "<a href='image.php?id=".$image['id']."'><img class='gallery-image' loading='lazy' src='".$image_path."' id='".$image['id']."'></a>";
    echo "</div>";
  }
}
?>
