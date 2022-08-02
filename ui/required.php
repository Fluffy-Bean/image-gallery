<?php
// Include required files and commands by every page on header
session_start();

if (is_dir("ui/")) {
  include_once("ui/functions.php");
} else {
  include_once("../ui/functions.php");
}

$conn = mysqli_connect("localhost", "uwu", "fennec621", "swag");
if ($conn->connect_error) {
  echo "<p class='alert alert-low'>Could not connect to database</p>";
}
?>
<script
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous">
</script>
