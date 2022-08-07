/*
  Confirm deleting user

  user must be privilaged to do this action this the privilaged == true
*/
if (isset($_POST['delete_confirm']) && $privilaged) {
  // Unset all the variables, needed by flyout
  unset($header, $content, $action);

  // Delete from table
  $image_delete_request = "DELETE FROM swag_table WHERE id =".$image['id'];
  $image_delete = mysqli_query($conn,$image_delete_request);

  if ($image_delete) {
    // See if image is in the directory
    if (is_file("images/".$image['imagename'])) {
      unlink("images/".$image['imagename']);
    }
    // Delete thumbnail if exitsts
    if (is_file("images/thumbnails/".$image['imagename'])) {
      unlink("images/thumbnails/".$image['imagename']);
    }
    header("Location:index.php?del=true&id=".$image['id']);
  } else {
    header("Location: image.php?id=".$image['id']."&del=fail>");
  }
}
