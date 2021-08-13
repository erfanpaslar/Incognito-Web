<?php
session_start(); ?>
<!DOCTYPE html>
<html lang="fa-IR" dir="rtl">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>CSE Incognito</title>
	<link rel="icon" type="image/svg+xml" href="images/incognito.svg">
	<link rel="stylesheet" href="css/styles.css" />
	<link href="https://cdn.rawgit.com/rastikerdar/vazir-code-font/v1.1.2/dist/font-face.css" rel="stylesheet" type="text/css" />
	<link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazir-font@v28.0.0/dist/font-face.css" rel="stylesheet" type="text/css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


	<script>
		// Disable back button (I'm really sorry but it is a single page website)
		history.pushState(null, document.title, location.href);
		window.addEventListener('popstate', function(event) {
			history.pushState(null, document.title, location.href);
		});

		$(document).ready(function() {
			$(".more").click(function() {
				hideAll();
				document.getElementById("allComments").classList.remove("hide");
				$("#comments").load("loadComments.php", {
					commentNewCount: "all"
				});
			});
		});

		function setStatus(id, status) {
			$("#errorMessage").load("setStatus.php", {
				commentId: id,
				commentStatus: status,
			});
			document.getElementById(id).setAttribute('onclick', `setStatus(${id},${status? 0 :1})`)
			document.getElementById("com" + id).classList.toggle("red");
			document.getElementById(id).classList.toggle("commentStatusOk");
		}

		$(document).ready(function() {
			$("#loginBtn").click(function() {
				hideAll();
				document.getElementById("entry").classList.remove("hide");
				let username = document.getElementById("username").value;
				let password = document.getElementById("password").value;
				$("#errorMessage").load("login.php", {
					username: username,
					password: password,
				});
				window.location.href = window.location.href.split("?")[0];
			});
		});
	</script>

</head>

<body style="direction: rtl">
	<label class="switch ">
		<input type="checkbox" checked id="toggle-mode" />
		<span class="slider round"></span>
	</label>
	<button id="incognito" class="homeBtn" onClick="goHome()"></button>
	<a class="tele" href="https://t.me/Cseincognito"></a>

	<?php include "db.php";
	if (isset($_SESSION['userId'])) {
	?>
		<script>
			document.getElementById("incognito").style.backgroundImage = "url(./images/incognitoA.svg)"
			console.log("Hi Admin");
		</script>
	<?php
	} else {
	?>
		<script>
			document.getElementById("incognito").style.backgroundImage = "url(./images/incognito.svg)"
		</script>
	<?php
	}

	?>

	<div id="entry" class="">
		<div id="preview" class="preview"></div>
		<div class="grid grid--1x2">
			<div class="btn-container">
				<button class="btn btn-right boxShadow" onClick="showAdd()">ثبت پیام جدید</button>
			</div>
			<div class="btn-container">
				<button class="btn more btn-left boxShadow">پیام‌های دیگران</button>
			</div>
		</div>
		<h1 id="errorMessage" class="errorMessage hide">پیام شما دریافت شد.</h1>
	</div>

	<?php
	if (isset($_POST['isSubmit'])) {
		$content = $_POST["content"];
		$theUrl = $_POST["theUrl"];
		$lowerUrl = strtolower($theUrl);
		$output_array = array();
		preg_match('/^((https:\/\/|http:\/\/|)(www.|)(t|telegram)\.me\/)(cseincognito|c\/1401897537)\/[0-9]{1,10}$/', $lowerUrl, $output_array);

		if (empty($output_array)) {
			$theUrl = '';
		} else {
			$theUrl = $output_array[0];
			$theUrl = str_replace("https://", "", $theUrl);
			$theUrl = str_replace("http://", "", $theUrl);
		}

		if (0 < strlen($content) && strlen($content) <= 4000) {
			$content = htmlspecialchars($content);
			$stmtAddComment = mysqli_prepare($connection, "INSERT INTO comments (comment_content, comment_url, comment_date) VALUES (?, ?, now() ) ");
			mysqli_stmt_bind_param($stmtAddComment, 'ss', $content, $theUrl);
			mysqli_stmt_execute($stmtAddComment);
			mysqli_stmt_close($stmtAddComment);
			echo "<script>document.getElementById('errorMessage').classList.remove('hide')</script>";
		}
	}
	?>
	<div id="addComment" class="hide">
		<h1 class="comments-title">هیچکی نمیفهمه!</h1>

		<form action="" method="POST" id="addCommentForm">
			<div class="btn-container">
				<textarea class="textArea inp boxShadow" name="content" id="formContent" cols="30" rows="10" maxlength="4000"></textarea>
			</div>

			<div id="linkContainer" class="btn-container hide">
				<input class="inp bigInput" name="theUrl" id="theUrl" maxlength="100" placeholder="https://t.me/CseIncognito/1 (Not required) " dir="ltr" />
			</div>

			<div class="btn-container">
				<button id="notSubmit" type="Submit" name="isSubmit" class="btn btn-alone boxShadow">ثبت پیام</button>
			</div>

			<h3 id="wantToAnswerBtn" style="text-align: center; cursor:pointer;font-size: 1rem;" onclick="wantToAnswer()">میخوای به یکی از پیام‌های داخل کانال جواب بدی؟</h3>
		</form>
	</div>

	<script>
		function submitAddComment(token) {
			document.getElementById('addCommentForm').submit();
		}
	</script>


	<div id="allComments" class="hide">
		<h1 class="comments-title">آخرین پیام‌ها</h1>
		<div id="comments" class="comments">
		</div>
	</div>

	<div id="login" class="hide">
		<h1 class="comments-title"><?php echo isset($_SESSION['userId']) ? "تغییر رمز عبور" : "ورود" ?></h1>
		<div class="btn-container">
			<input id="username" class="inp theInp boxShadow" type="<?php echo isset($_SESSION['userId']) ? 'password' : 'text' ?>" name="username" autocomplete=" on" placeholder="<?php echo isset($_SESSION['userId']) ? 'Password' : 'Username' ?>" dir="ltr" />
		</div>
		<div class="btn-container">
			<input id="password" class="inp theInp boxShadow" type="password" autocomplete="on" name="password" placeholder="<?php echo isset($_SESSION['userId']) ? 'Re enter password' : 'Password' ?>" dir="ltr" />
		</div>
		<div class="btn-container">
			<button id="loginBtn" class="btn btn-alone boxShadow"><?php echo isset($_SESSION['userId']) ? "تغییر رمز" : "ورود" ?></button>
		</div>
	</div>

	<script src="js/app.js"></script>
	<script>
		// prevent resubmitting form
		if (window.history.replaceState) {
			window.history.replaceState(null, null, window.location.href);
		}
	</script>
	<script>
		const urlParams = new URLSearchParams(window.location.search);
		const admin = urlParams.get('admin');
		if (admin == "admin") {
			hideAll();
			document.getElementById("login").classList.remove("hide");
		}
	</script>


</body>

</html>
