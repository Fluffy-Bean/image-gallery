<?php
include __DIR__ . "/../conn.php";
include __DIR__ . "/../app.php";

use App\Account;
use App\Diff;

$user_info	= new Account();
$diff		= new Diff();

if (isset($_POST['log'])) {
    if ($user_info->is_admin($conn, $_SESSION['id'])) {
        ?>
            <div class="log">
                <p>ID</p> <p>User IP</p> <p>Action</p> <p>Time</p>
            </div>
        <?php
        // Reading images from table
        $logs_request = mysqli_query($conn, "SELECT * FROM logs ORDER BY id DESC");
    
        while ($log = mysqli_fetch_array($logs_request)) {
            ?>
                <div class="log">
                    <p><?php echo $log['id']; ?></p>
                    <p><?php echo $log['ipaddress']; ?></p>
                    <p><?php echo $log['action']; ?></p>
                    <?php
                        $log_time = new DateTime($log['time']);
                        echo "<p>".$log_time->format('Y-m-d H:i:s T')." (".$diff->time($log['time']).")</p>";
                    ?>
                </div>
            <?php
        }
    }
}
