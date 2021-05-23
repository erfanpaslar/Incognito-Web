<?php
include "db.php";
$TELEGRAM_BOT_TOKEN = '<YOUR API TOKEN>';
if (isset($_GET["TOKEN"])) {
	if (isset($_GET["last"])) {
		if ($_GET["TOKEN"] == $TELEGRAM_BOT_TOKEN) {
			$last = htmlspecialchars($_GET["last"]);
			$query = "SELECT * FROM comments WHERE comment_from_bot=0 ORDER BY comment_id ";
			$query = mysqli_query($connection, $query);
			$allData = array();
			while ($row = mysqli_fetch_array($query)) {
				if ($row['comment_id'] > $last) {
					$commentId = $row['comment_id'];
					$commentContent = $row['comment_content'];
					$commentUrl = $row['comment_url'];
					$data["id"] = (int) $row['comment_id'];
					$data["content"] = $row['comment_content'];

					if (preg_match("/\/(\d+)$/", $row['comment_url'], $matches)) {
						$commentIdInChannel = $matches[1];
					} else {
						$commentIdInChannel = -1;
					}
					$data["reply_id"] = (int)$commentIdInChannel;

					array_push($allData, $data);
				}
			}
			$myJSON = json_encode($allData);
			echo $myJSON;
		}
	}

	if (isset($_GET["accept"])) {
		if ($_GET["TOKEN"] == $TELEGRAM_BOT_TOKEN) {
			$rejectId = htmlspecialchars($_GET["accept"]);
			$stmtUpdateStatus = "UPDATE comments SET comment_status = 1 WHERE comment_id=? ";
			$stmtUpdateStatus = mysqli_prepare($connection, $stmtUpdateStatus);
			mysqli_stmt_bind_param($stmtUpdateStatus, "i", $rejectId);
			mysqli_stmt_execute($stmtUpdateStatus);
		}
	}

	if (isset($_GET["content"]) && isset($_GET["reply_id"]) && isset($_GET["status"])) {
		if ($_GET["TOKEN"] == $TELEGRAM_BOT_TOKEN) {
			$theContent = $_GET["content"];
			$theUrl = $_GET["reply_id"];
			$commentStatus = (int)htmlspecialchars($_GET["status"]);
			if (!empty($theContent) && strlen($theContent) <= 4000) {
				if ($theContent[0] == '"') {
					$theContent = rtrim(substr($theContent, 1), '"');
				} else if ($theContent[0] == "'") {
					$theContent = rtrim(substr($theContent, 1), "'");
				}
				$theContent = htmlspecialchars($theContent);

				if ($theUrl == -1 || empty($theUrl)) {
					$theUrl = "";
				} else {
					$theUrl = "t.me/CseIncognito/" . $theUrl;
				}
				$stmtAddComment = mysqli_prepare($connection, "INSERT INTO comments (comment_content, comment_url, comment_date, comment_status, comment_from_bot) VALUES (?, ?, now(), ?, 1 ) ");
				mysqli_stmt_bind_param($stmtAddComment, 'ssi', $theContent, $theUrl, $commentStatus);
				mysqli_stmt_execute($stmtAddComment);
				mysqli_stmt_close($stmtAddComment);
				echo $connection->insert_id;
			}
		}
	}
} else {
	echo "Access Denied.";
}
