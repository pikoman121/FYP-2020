<!--login_select.php-->

<!----------------------------------------------------->
<!--http://localhost:8080/present_app/login_select.php -->
<!--http://localhost:8080/phpmyadmin/--> 
<!----------------------------------------------------->

<!----------------------------------------------------->
<?php 

/* Copy in additional files (order matters) */

//'require'/'include' takes code from the specified file and copies 
//it into this file that uses the  statement

require 'config/config.php';
//fetches php config instructions from config.php for connection to dbms

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

	<!--Favicon -->
    <link rel="icon" href="assets/images/favicon/favicon.ico">

	<!----------------------------------------------------->

<body>

	<div class="wrapper">

		<div class="login_box">

			<button  class="previous" onclick="location.href='index_main.php'" type="button">
		         	⬅︎
		    </button>

			<div class="login_header">

				<img src="assets/images/logos/present_white.png">
				
				<br>
				<br>

				Login as:

				<br>
				<br>
			
			</div>

				<!--------------------------Login Selector--------------------------->

				<center>

					<!--login as uc-->
					<button  class="selection button" onclick="location.href='login_uc.php'" type="button">
		         		Unit Coordinator
		     		</button>


		     		<!--representative-->
		     		<button  class="selection button" onclick="location.href='login_rep.php'" type="button">
		         		Representative
		     		</button>

					<br><br>

					<!--return to site button-->
					<a href="index_main.php" class="return_site"> Return to Site </a>

					<br><br>
					
				</center>

				<!----------------------------------------------------->
		</div>

	</div>

</body>

</html>