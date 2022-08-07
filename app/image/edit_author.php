/*
  Author confirm
*/
if (isset($_POST['author_confirm']) && is_admin($_SESSION['id'])) {
  // Unset all the variables, needed by flyout
  unset($header, $content, $action);

  // getting ready forSQL asky asky
  $sql = "UPDATE swag_table SET author=? WHERE id=?";

  // Checking if databse is doing ok
  if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "si", $param_author, $param_id);

    // Setting parameters
    $param_author = $_POST['update_author'];
    $param_id = $image["id"];

    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
      header("Location:image.php?id=".$image["id"]."&update=success");
    } else {
      header("Location:image.php?id=".$image["id"]."&update=error");
    }
  }
}
