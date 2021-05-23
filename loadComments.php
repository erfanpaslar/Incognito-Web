<?php include "db.php";
session_start();

$commentsNewCounts = $_POST["commentNewCount"];
$query = "SELECT * FROM comments ORDER BY comment_id DESC LIMIT 100";
$query = mysqli_query($connection, $query);

$nowDate = date("Y-m-d");
while ($row = mysqli_fetch_array($query)) {
	$commentId = $row['comment_id'];
	$commentContent = $row['comment_content'];
	$commentDate = $row['comment_date'];
	$commentStatus = $row['comment_status'];
	$commentUrl = $row['comment_url'];

	if (isset($_SESSION["userId"])) {
		$diff = abs(strtotime($nowDate) - strtotime($commentDate));

		$years = floor($diff / (365 * 60 * 60 * 24));
		$months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
		$days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));

		$outDate = "";
		if ($years) {
			$outDate .= $years . " سال, ";
		}
		if ($months) {
			$outDate .= $months . " ماه, ";
		}
		if ($days) {
			$outDate .= $days . " روز ";
		}
		$outDate .= "پیش";
?>
		<div id="com<?php echo $commentId; ?>" class="comment boxShadow <?php echo $commentStatus ? "" : "red" ?>"><?php echo $commentContent; ?>
			<span class="commentDate"><?php echo !$years && !$months && !$days ? "امروز" : $outDate ?></span>
			<button id="<?php echo $commentId; ?>" onClick="setStatus(<?php echo $commentId; ?>, <?php echo $commentStatus; ?>)" class="commentStatus status <?php echo $commentStatus ? "commentStatusOk" : "" ?>"></button>
			<?php
			if (!empty($commentUrl)) {
				echo "<span class='response'><a class='a-response' href='http://$commentUrl' target='_blank'>در پاسخ به <img class='forward' src='images/telegram.svg'/></a></span>";
			}
			?>
		</div>
	<?php
	} else if ($commentStatus) { ?>
		<div class="comment boxShadow"><?php echo $commentContent; ?>
			<span class="commentDate"><?php echo !$years && !$months && !$days ? "امروز" : $outDate ?></span>
			<?php
			if (!empty($commentUrl)) {
				echo "<span class='response'><a class='a-response' href='http://$commentUrl' target='_blank'>در پاسخ به <img class='forward' src='/images/telegram.svg'/></a></span>";
			}
			?>
		</div>
<?php
	}
}
?>