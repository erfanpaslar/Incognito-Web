<?php include "db.php";
session_start();
function escape($string) {
	global $connection;
	return mysqli_real_escape_string($connection, $string);
}
function stripper($string) {
	$a = 1;
	$notAllowedChars = ['<', '>', '(', ')', "\\", '|', '?', "\"", "\'", ';', ' '];
	$array = str_split($string);
	foreach ($array as $char) {
		foreach ($notAllowedChars as $mustNot) {
			if ($char === $mustNot) {
				$a = 0;
			}
		}
	}
	if (!$a) {
		return '';
	} else {
		return $string;
	}
}
if (isset($_SESSION['userId'])) {
	$userId = $_SESSION['userId'];
	$password1 = escape(stripper($_POST['username']));
	$password2 = escape(stripper($_POST['password']));

	if (!empty($password1) && $password1 == $password2 && strlen($password1) >= 4) {
		$userPassword = password_hash($password1, PASSWORD_BCRYPT, array('cost' => 10));
		$stmtUpdatePass = "UPDATE users SET user_password = ? WHERE user_id=? ";
		$stmtUpdatePass = mysqli_prepare($connection, $stmtUpdatePass);
		mysqli_stmt_bind_param($stmtUpdatePass, "si", $userPassword, $userId);
		mysqli_stmt_execute($stmtUpdatePass);
	}
} else {
	$username = escape(stripper($_POST['username']));
	$password = escape(stripper($_POST['password']));

	$query = "SELECT user_id, username, user_password FROM users WHERE username=? ";
	$selectUserQuery = mysqli_query($connection, $query);

	$stmt = mysqli_prepare($connection, $query);
	mysqli_stmt_bind_param($stmt, "s", $username);
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $dbUserId, $dbUsername, $dbUserPassword);

	$dbUsername = '';
	$dbUserPassword = '';
	while (mysqli_stmt_fetch($stmt)) {
		// 
	}

	if (!empty($username) && !empty($password) && password_verify($password, $dbUserPassword)) {
		$_SESSION["userId"] = $dbUserId;
		echo "<script>
	document.getElementById('incognito').style.backgroundImage = 'url(./incognitoA.svg)';
	</script>";
	} else {
		echo "Login Failed";
	}
}
