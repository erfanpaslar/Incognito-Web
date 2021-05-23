<?php include "db.php";
session_start();
if (isset($_SESSION['userId'])) {
	$commentId = $_POST["commentId"];
	$commentStatus = $_POST["commentStatus"];
	$commentStatus = $commentStatus ? 0 : 1;
	$stmtUpdateStatus = "UPDATE comments SET comment_status = ? WHERE comment_id=? ";
	$stmtUpdateStatus = mysqli_prepare($connection, $stmtUpdateStatus);
	mysqli_stmt_bind_param($stmtUpdateStatus, "ii", $commentStatus, $commentId);
	mysqli_stmt_execute($stmtUpdateStatus);
} else {
	echo "Access Denied";
}
