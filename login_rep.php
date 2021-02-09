<!--login_rep.php-->

<!----------------------------------------------------->
<!--http://localhost:8080/present_app/login_rep.php -->
<!--http://localhost:8080/phpmyadmin/--> 
<!----------------------------------------------------->

<!----------------------------------------------------->
<?php 

/* Copy in additional files (order matters) */

//'require'/'include' takes code from the specified file and copies 
//it into this file that uses the  statement

require 'config/config.php';
//fetches php config instructions from config.php for connection to dbms

require 'includes/form_handlers/register_handler.php';
//fetches php register form code from register_handler.php
//contains data that login handler will later acesss (error_array)/ must be included before login handler

require 'includes/form_handlers/login_handler.php';
//fetches php register form code from login_handler.php

?>
<!----------------------------------------------------->


<html>

<head>

	<title> Login/Register </title>

	<!----------------------------------------------------->
	<!-- CSS Style Sheet --> 
	<link rel="stylesheet" type="text/css" href="assets/css/register_styles.css">

	<!-- JQuery CDN Library --> 
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>

	<!-- Javascript --> 
	<script src="assets/js/register.js"></script>

	<!--Favicon -->
    <link rel="icon" href="assets/images/favicon/favicon.ico">

	<!----------------------------------------------------->

<body>

	<div class="wrapper">

		<div class="login_box">

			<button  class="previous" onclick="location.href='login_select.php'" type="button">
		         	⬅︎
		    </button>

			<div class="login_header">

				<img src="assets/images/logos/present_white.png">
				<br><br>

				Representative Login

				<br>
			
			</div>

				<!--------------------------Representative Login Form--------------------------->

				<div id="first">

					<form action="login_rep.php" method="POST">

						<!--Login Form Fields-->

						<!--Email Section -->
						<input type="text" name="log_username" placeholder="Username" value="<?php
						//PHP code to restore previously entered data (in event of error)
						if(isset($_SESSION['log_username'])){
							echo $_SESSION['log_username'];}?>"required >
						<br>

						<!--Password Section -->
						<input type="password" name="log_password" placeholder="Password">
						<br>

						<!--Display Error Message (incorrect input) -->
						<?php if(in_array("Username or Password was Incorrect.<br>", $error_array))
						echo "Username or Password was Incorrect.<br>" ?>

						<!--Submit Button -->
						<input type="submit" name="login_rep_button" value="Login">
						<br>

						<br><br>

						<!--return to site button-->
						<a href="index_main.php" class="return_site"> Return to Site </a>

					</form>
					
				</div>

				<!----------------------------------------------------->
		</div>

	</div>

</body>

</html>