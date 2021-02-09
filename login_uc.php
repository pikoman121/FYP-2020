<!--login_uc.php-->

<!----------------------------------------------------->
<!--http://localhost:8080/present_app/login_uc.php -->
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

	<!----------------------------------------------------->
	<?php 

		if(isset($_POST['register_uc_button']))
		//if register_button value is not null
		//$_POST is a super global variable used to collect form data
		//after submitting a HTML form with method 'POST'
		{
			echo'

			<script>

			$(document).ready(function()
			{
				$("#first").hide();
				$("#second").show();
			});

			</script>

			';
			//adds a jquery script to body
			//Bug fix: prevents jumping to sign in page when registration 
			//button is clicked and there are invalid fields with error message displayed 
		}
	?>
	<!----------------------------------------------------->

	<div class="wrapper">

		<!--Display 'form alerts'-->

		<?php if(in_array("<div class='form_alert'> ✓ Your registration is successful. A confirmation email has been sent to you.</div>",$error_array)) echo "<div class='form_alert'> ✓ Your registration is successful. A confirmation email has been sent to you.</div>"; ?>

		<?php if(in_array("<div class='form_alert'> ! Registration unsuccessful. Contact support.</div>",$error_array)) echo "<div class='form_alert'> ! Registration unsuccessful. Contact support.</div>"; ?>

		<!-------------------------->


		<div class="login_box">

			<button  class="previous" onclick="location.href='login_select.php'" type="button">
		         		⬅︎
		    </button>

			<div class="login_header">

				<img src="assets/images/logos/present_white.png">

				<br><br>

				Unit Coordinator Login / Register

				<br>
			
			</div>

				<!--------------------------Login Form--------------------------->

				<div id="first">

					<form action="login_uc.php" method="POST">

						<!--Login Form Fields-->

						<!--Email Section -->
						<input type="email" name="log_email" placeholder="Email Address" value="<?php
						//PHP code to restore previously entered data (in event of error)
						if(isset($_SESSION['log_email'])){
							echo $_SESSION['log_email'];}?>"required >
						<br>

						<!--Password Section -->
						<input type="password" name="log_password" placeholder="Password">
						<br>

						<!--Display Error Message (incorrect input) -->
						<?php if(in_array("Email or Password was Incorrect.<br>", $error_array))
						echo "Email or Password was Incorrect.<br>" ?>

						<!--Submit Button -->
						<input type="submit" name="login_uc_button" value="Login">
						<br>

						<!--Sign Up Prompt (see also register.js)-->
						<a href="#" id="signup" class="signup"> Register as a Unit Coordinator here!</a>

						<br><br>

						<!--Forget Password-->
						<a href="forget_password.php" class="return_site"> Forget your password </a>

					</form>
					
				</div>

				<!-------------------------Registration Form---------------------------->

				<div id="second">

					<form action="login_uc.php" method="POST">

					<!--Registration Form Fields-->

					<!--Security Code ***-->
					<input type="password" id="security_field"  name="reg_security_code" placeholder="Security Code" required>
				
					<!--Error Messages -->
					<?php if(in_array("<br>Security code is invalid.<br>",$error_array))
					//Display error messages in error array 
					echo "<br> Security code is invalid.<br>";?>		

					<br>

					<!--First Name Section -->
					<input type="text" name="reg_fname" placeholder="First Name" value="<?php
					//PHP code to restore previously entered data  (in event of error)
					if(isset($_SESSION['reg_fname'])){
						echo $_SESSION['reg_fname'];}?>"required >

					<!--Error Messages -->
					<?php if(in_array("<br> Your first name must be between 2 and 25 characters.<br>",$error_array))
					//Display error messages in error array 
					echo "<br> Your first name must be between 2 and 25 characters.";?>		
					<br>


					<!--Last Name Section -->
					<input type="text" name="reg_lname" placeholder="Last Name" value="<?php
					//PHP code to restore previously entered data  (in event of error)
					if(isset($_SESSION['reg_lname'])){
						echo $_SESSION['reg_lname'];}?>"required>
					
					<!--Error Messages -->
					<?php if(in_array("<br> Your last name must be between 2 and 25 characters.<br>",$error_array))
					//Display error messages in error array 
					echo "<br> Your last name must be between 2 and 25 characters.";?>
					<br>

					<!--Telephone Section -->
					<input type="text" name="reg_tel" placeholder="Telephone" value="<?php
					//PHP code to restore previously entered data  (in event of error)
					if(isset($_SESSION['reg_tel'])){
						echo $_SESSION['reg_tel'];}?>"required>

					<!--Error Messages -->
					<?php if(in_array("<br> Your telephone number can only contain numbers.<br>",$error_array))
					//Display error messages in error array 
					echo "<br> Your telephone number can only contain numbers.<br>";?>
					<br>
		

					<!--Email Section -->
					<input type="email" name="reg_email" placeholder="Email" value="<?php
					//PHP code to restore previously entered data  (in event of error)
					if(isset($_SESSION['reg_email'])){
						echo $_SESSION['reg_email'];}?>"required>
					<br>

					<input type="email" name="reg_email2" placeholder="Confirm Email" value="<?php
					//PHP code to restore previously entered data (in event of error)
					if(isset($_SESSION['reg_email2'])){
						echo $_SESSION['reg_email2'];}?>" required>


					<!--Error Messages -->	
					<?php if(in_array("<br> Email already in use.<br>",$error_array))
					//Display error messages in error array 
					echo "<br> Email already in use.";
					else if(in_array("<br> Emails don't match.<br>",$error_array))
					//Display error messages in error array 
					echo "<br> Emails don't match.";
					else if(in_array("<br> Invalid email format.<br>",$error_array))
					//Display error messages in error array 
					echo "<br> Invalid email format.";?>
				    <br>

					<!--Password Section -->
					<input type="password" name="reg_password" placeholder="Password" required>
					<br>
					<input type="password" name="reg_password2" placeholder="Confirm Password" required>

					<!--Error Messages -->
					<?php if(in_array("<br> Your passwords do not match.<br>",$error_array))
					//Display error messages in error array 
					echo "<br> Your passwords do not match.";
					else if(in_array("<br> Your password can only contain english characters or numbers.<br>",$error_array))
					//Display error messages in error array 
					echo "<br> Your password can only contain english characters or numbers.";
					else if(in_array("<br> Your password must be between 5 and 30 characters.<br>",$error_array))
					//Display error messages in error array 
					echo "<br> Your password must be between 5 and 30 characters.";?>
				    <br>

					<!--Register Button-->
					<input type="submit" name="register_uc_button" value="Register">
					<br>

					<!--Sign In Prompt (see also register.js) -->
					<a href="#" id="signin" class="signin"> Already have an account? Sign in here!</a>
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