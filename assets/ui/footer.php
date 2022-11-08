<?php
	if (isset($sql_start) && isset($sql_end)) {
		$sql_time = $sql_end - $sql_start;
		$sql_time = round($sql_time, 6) * 1000;

		if ($sql_time > 0) {
			$sql_time = "(SQL ".$sql_time."ms)";
		} else {
			$sql_time = "(SQL <0ms)";
		}
	} else {
		$sql_time = "";
	}

	if (isset($exec_start)) {
		$exec_end = microtime(true);

		$exec_time = ($exec_end - $exec_start);
		$exec_time = round($exec_time, 6) * 1000;
	} else {
		$exec_time = 0;
	}
?>

<footer>
	<p>Copyright <?php echo $user_settings['user_name']; ?></p>
	<a class='link' href="https://github.com/Fluffy-Bean/image-gallery">Made by Fluffy</a>
	<p>V<?php echo $web_info['version']; ?></p>
	<?php
		if ($exec_time != 0) {
			echo "<p>Generated in ".$exec_time."ms ".$sql_time."</p>";
		}
	?>
</footer>
