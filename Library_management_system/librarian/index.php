<?php
	session_start();
	require "../db_connect.php";
	require "../message_display.php";
	require "../verify_logged_out.php";
	require "../header.php";
?>

<html>
	<head>
		<title>LMS</title>
		<link rel="stylesheet" type="text/css" href="../css/global_styles.css">
		<link rel="stylesheet" type="text/css" href="../css/form_styles.css">
		<link rel="stylesheet" type="text/css" href="css/index_style.css">
	</head>
	<body>
		<form class="cd-form" method="POST" action="#">
			<center><legend>Librarian Login</legend></center>

			<div class="error-message" id="error-message">
				<p id="error"></p>
			</div>

			<div class="icon">
				<input class="l-user" type="text" name="l_user" placeholder="Username" required />
			</div>

			<div class="icon">
				<input class="l-pass" type="password" name="l_pass" placeholder="Password" required />
			</div>

			<input type="submit" value="Login" name="l_login"/>
		</form>
		<p align="center"><a href="../index.php" style="text-decoration:none;">Go Back</a></p>
	</body>

	<?php
		if(isset($_POST['l_login'])) {
			$hashed_password = sha1($_POST['l_pass']); // Store hashed password in a variable
		
			$query = $con->prepare("SELECT id FROM librarian WHERE username = ? AND password = ?;");
			$query->bind_param("ss", $_POST['l_user'], $hashed_password); // Pass the variable by reference
			$query->execute();
			$result = $query->get_result();
		
			if($result->num_rows != 1) {
				echo error_without_field("Invalid username/password combination");
			} else {
				$_SESSION['type'] = "librarian";
				$row = $result->fetch_assoc();
				$_SESSION['id'] = $row['id'];
				$_SESSION['username'] = $_POST['l_user'];
				header('Location: home.php');
				exit; // Ensure no further code execution after redirection
			}
		}
		
	?>
</html>
