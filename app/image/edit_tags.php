/*
  Tags Confirm
*/
if (isset($_POST['tags_confirm']) && $privilaged) {
  // Unset all the variables, needed by flyout
  unset($header, $content, $action);

  // Clean tags before adding
  function clean($string) {
    // Change to lowercase
    $string = strtolower($string);
    // Replace hyphens
    $string = str_replace('-', '_', $string);
    // Regex
    $string = preg_replace('/[^A-Za-z0-9\_ ]/', '', $string);
    // Return string
    return preg_replace('/ +/', ' ', $string);
  }

  // Clean input
  $tags_string = tag_clean(trim($_POST['add_tags']));

  // getting ready forSQL asky asky
  $sql = "UPDATE swag_table SET tags=? WHERE id=?";

  // Checking if databse is doing ok
  if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "si", $param_tags, $param_id);

    // Setting parameters
    $param_tags = $tags_string;
    $param_id = $image["id"];

    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
      header("Location:image.php?id=".$image["id"]."&update=success");
    } else {
      header("Location:image.php?id=".$image["id"]."&update=error");
    }
  }
}
