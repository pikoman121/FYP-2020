<!--info_panels.php -->

<!----------------------------------------------------->
<!--http://localhost:8080/present_app/info_panels.php -->
<!--http://localhost:8080/phpmyadmin/--> 
<!----------------------------------------------------->

<!--Adds Info panels (header + sidebar) to any file that includes this file-->

<?php

	require ("config/config.php");
	//fetch php config instructions from config.php
	//establishes connection to MySQL database stores starts a new session
	require ("includes/classes/User.php");
	//fetch User class file

	$acctype="";
	//stores an acctype
	$username="";
	//stores a username
	$user_obj="";
	//stores a user obj

	if(isset($_SESSION['acctype']))
	{
		$acctype=$_SESSION['acctype'];
		//get acctype details from session variable
		$username=$_SESSION['username'];
		//get username details from session variable
		$user_obj = new User ($con_mysqli, $username);
		//create a new user object to display user data

		if($acctype=="uc")
		// user is logged in as uc
		{
			//require 'includes/headers/panels_uc.php';
			//use uc header

			//get representative data
			$uc_name = $user_obj->getFirstAndLastName();
			$profile_pic = $user_obj->getProfilePic();
			$upcoming = $user_obj->getUpcomingEvents();
			$numPresentations = $user_obj->getNumPresentations();
			$numPublished = $user_obj->getNumPublished();

			echo 
			/* --- START html panel display for UC users --- */
			"<html>
				<head>
					<title>Present App</title>
					<!----------------------------------------------------->
					<!--Javascript Libraries -->
					<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js\"></script>
					<script> src=\"assets/js/bootstrap.js\"</script>
					<!--CSS Libraries -->
					<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css\">
					<link rel=\"stylesheet\" type=\"text/css\" href=\"assets/css/bootstrap.css\">
					<link rel=\"stylesheet\" type=\"text/css\" href=\"assets/css/styles.css\">
					<!--Favicon -->
					<link rel=\"icon\" href=\"assets/images/favicon/favicon.ico\">
					<!----------------------------------------------------->
				</head>
			<body>
				<!----------- Top Bar ------------>
				<div class=\"top_bar\">
					<!----------- Logo Area (Left) ------------>
					<div class=\"present_logo\" alt=\"logo-img\">
						<!--logo + onclick link to main index-->
						<a href=\"landing.php\" id=\"logo_img\">
							<img src=\"assets/images/logos/present_black.png\">
						</a>
						<!--about-->
						<a href=\"about.php\">
							About
						</a>
						<!--Full Presentation Listing-->
						<a href=\"index_main.php\" id=\"pl\">
							Presentation Listing
						</a>
					</div>
				    <!----------- Navigation Bar (Right) ------------>
					<nav>
						<!--user indicator-->
						<div class=\"user_uc\">
							Unit Coordinator
						</div>
						<!--account settings-->
						<a href=\"uc_info.php\" class=\"nav_icon\" name=\"settings\">
							<i class=\"fa fa-cog fa-lg\"></i> 
						</a>
						<!--logout button-->
						<a href=\"includes/other_handlers/logout.php\" class=\"nav_icon\" name=\"Logout\">
							<i class=\"fa fa-sign-out fa-lg\"></i> 
						</a>
					</nav>
				</div>

				<!----------------------------------------------------->
				<!--Opening tag of wrapper class--> 

				<div class= \"wrapper\">
					<!--------- Side Bar --------->
					<div class=\"side_bar\" id=\"sb\">
						<!--------- Show Profile Picture --------->
						<center>
							<img style=\"width:95px; border-radius:9px;
							box-shadow: 0.5px 0.5px #1D1A1A; margin:15px 0;\" src=\"$profile_pic\">
						</center>
						<!--------- Show UC Name --------->
						<h5 style=\"font-family: 'futuraBoldItalic';; text-align:center; font-size:18px;\">$uc_name</h5>
						<h5 style=\"font-family: 'arial';; text-align:center; font-size:16px;\">Unit Coordinator</h5>
						<br>
						<!--------- Show Events Button --------->
						<center>
							<button class=\"sidebar_button_uc\" onclick=\"location.href='index_uc.php'\">My Events</button>
						</center>
						<br>
						<hr style=\"background-color:#fff;\">
						<br>
						<!--------- Show Upcoming Events --------->
						<h5 style=\"font-family: 'futuraBoldItalic';; text-align:center; font-size:16px;\">Upcoming Events</h5>
						<h5 style=\"font-family: 'arial';; text-align:center; font-size:18px;\"> $upcoming </h5>
						<br>
						<!--------- Total Presentations --------->
						<h5 style=\"font-family: 'futuraBoldItalic';; text-align:center; font-size:16px;\">Total Presentations</h5>
						<h5 style=\"font-family: 'arial';; text-align:center; font-size:18px;\"> $numPresentations </h5>
						<br>
						<!--------- Total Published --------->
						<h5 style=\"font-family: 'futuraBoldItalic';; text-align:center; font-size:16px;\">Total Published</h5>
						<h5 style=\"font-family: 'arial';; text-align:center; font-size:18px;\">$numPublished</h5>
						<br>
						<hr style=\"background-color:#fff;\">
					</div>
			";
			/* --- END html panel display for UC users --- */

		}
		else if($acctype=="rep")
		// user is logged in as rep
		{
			//get representative data
			$rep_name = $user_obj->getFirstAndLastName();
			$profile_pic = $user_obj->getProfilePic();
			$uc_name = $user_obj->getUCName();
			$uc_email = $user_obj->getUCEmail();
			$present_status = $user_obj->getPresentStatus();
			$daysToPresent = $user_obj->getDaysToPresent();

			/* --- START html panel display for REP users --- */
			echo
			"<html>
				<head>
					<title>Present App</title>
					<!----------------------------------------------------->
					<!--Javascript Libraries -->
					<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js\"></script>
					<script> src=\"assets/js/bootstrap.js\"</script>

					<!--CSS Libraries -->
					<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css\">
					<link rel=\"stylesheet\" type=\"text/css\" href=\"assets/css/bootstrap.css\">
					<link rel=\"stylesheet\" type=\"text/css\" href=\"assets/css/styles.css\">
					<!--Favicon -->
					<link rel=\"icon\" href=\"assets/images/favicon/favicon.ico\">

					<!----------------------------------------------------->
				</head>
			<body>

				<!----------- Top Bar ------------>
				<div class=\"top_bar\">
					<!----------- Logo Area (Left) ------------>
					<div class=\"present_logo\" alt=\"logo-img\">
						<!--logo + onclick link to main index-->
						<a href=\"landing.php\" id=\"logo_img\">
							<img src=\"assets/images/logos/present_black.png\">
						</a>
						<!--about-->
						<a href=\"about.php\">
							About
						</a>
						<!--Full Presentation Listing-->
						<a href=\"index_main.php\" id=\"pl\">
							Presentation Listing
						</a>
					</div>
				    <!----------- Navigation Bar (Right) ------------>
					<nav>
						<!--user indicator-->
						<div class=\"user_rep\">
							Representative
						</div>
						<!--logout button-->
						<a href=\"includes/other_handlers/logout.php\" class=\"nav_icon\" name=\"Logout\">
							<i class=\"fa fa-sign-out fa-lg\"></i> 
						</a>
					</nav>
					
				</div>
				<!----------------------------------------------------->
				<!--Opening tag of wrapper class--> 
				<div class= \"wrapper\">
					<div class=\"side_bar\" id=\"sb\">

						<!--profile image-->
						<center>
							<img style=\"width:95px; border-radius:9px;
							box-shadow: 0.5px 0.5px #1D1A1A; margin:15px 0;\" src=\"$profile_pic\">
						</center>
						<!--Show First and Last Name-->
						<h5 style=\"font-family: 'futuraBoldItalic';; text-align:center; font-size:18px;\">
							$rep_name 
						</h5>
						<!--Show Account Type-->
						<h5 style=\"font-family: 'arial';; text-align:center; font-size:16px;\">Representative</h5>
						<br>
						<!--Go to Presentation Button-->
						<center>
							<button class=\"sidebar_button_rep\" onclick=\"location.href='upload.php'\">My Presentation</button>
						</center>
						<br>
						<hr style=\"background-color:#fff;\">
						<br>
						<!--Show unit coordinator-->
						<h5 style=\"font-family: 'futuraBoldItalic';; text-align:center; font-size:16px;\">Unit Coordinator</h5>
						<a href=\"mailto: $uc_email\" style=\"color:#FFF;\">
							<h5 style=\"font-family: 'arial';; text-align:center; font-size:15px;\">$uc_name</h5>
						</a>
						<br>
						<!--Show presentation status-->
						<h5 style=\"font-family: 'futuraBoldItalic';; text-align:center; font-size:16px;\">Presentation Status</h5>
						<h5 style=\"font-family: 'arial';; text-align:center; font-size:15px;\">$present_status</h5>
						<br>
						<!--Show days to presentation-->
						<h5 style=\"font-family: 'futuraBoldItalic';; text-align:center; font-size:16px;\">Days to Presentation</h5>
						<h5 style=\"font-family: 'arial';; text-align:center; font-size:15px;\">$daysToPresent</h5>
						<br>
						<hr style=\"background-color:#fff;\">
					</div>
			";
			/* --- END html panel display for REP users --- */
		}
	}
	else
    // user is not logged in 
	{
		/* --- START html panel display for public users --- */
		echo 
		"<html>
				<head>
					<title>Present App</title>
					<!----------------------------------------------------->

					<!--Javascript Libraries -->
					<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js\"></script>
					<script> src=\"assets/js/bootstrap.js\"</script>
					<!--CSS Libraries -->
					<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css\">
					<link rel=\"stylesheet\" type=\"text/css\" href=\"assets/css/bootstrap.css\">
					<link rel=\"stylesheet\" type=\"text/css\" href=\"assets/css/styles.css\">
					<!--Favicon -->
					<link rel=\"icon\" href=\"assets/images/favicon/favicon.ico\">

					<!----------------------------------------------------->
				</head>
			<body>
				<!----------- Top Bar ------------>
				<div class=\"top_bar\">
					<!----------- Logo Area (Left) ------------>
					<div class=\"present_logo\" alt=\"logo-img\">
						<!--logo + onclick link to main index-->
						<a href=\"landing.php\" id=\"logo_img\">
							<img src=\"assets/images/logos/present_black.png\">
						</a>
						<!--about-->
						<a href=\"about.php\">
							About
						</a>
						<!--Full Presentation Listing-->
						<a href=\"index_main.php\" id=\"pl\">
							Presentation Listing
						</a>
					</div>
				    <!----------- Navigation Bar (Right) ------------>
					<nav>
						<!--login message-->
						<p class=\"login_message\">Unit Coordinator & Student Login</p>	
						<!--login button-->
						<button  class=\"login_button\" onclick=\"location.href='login_select.php'\" type=\"button\">
			         		Login
			     		</button>
					</nav>
				</div>
				<!----------------------------------------------------->
				<!--Opening tag of wrapper class--> 
				<div class= \"wrapper\">
					<!--------- Side Bar --------->
					<div style=\"background-color:#FFF;\"class=\"side_bar\" id=\"sb\">
						<!--------- Show Murdoch Logo --------->
						<center>
							<a href=\"https://www.murdoch.edu.au/\">
							<img style=\"width:95px; margin:15px 0;\" src=\"assets/images/logos/murdoch_logo.png\">
							</a>
						</center>
						<!--------- Show University Name --------->
						<h5 style=\"font-family:'futuraBoldItalic'; color:#CD0000; text-align:center; font-size:19px;\">Murdoch University</h5>
						<br>
						<hr style=\"background-color:#CCC;\">
						<!--------- Show University Picture --------->
						<center>
							<img style=\"width:185px; border-radius:9px; margin:15px 0;\" src=\"assets/images/backgrounds/murdoch_campus.jpg\">
						</center>
						<hr style=\"background-color:#CCC;\"><br>
						<!--------- Show University Description --------->
						<p style=\"font-size:13px; padding:3px; color:#1D1A1A; text-align:center;\">
						Murdoch University is the only public university in Perth ranked number 61 in the Times Higher Education Top 100 Most International Universities in the world, meaning we have a diverse staff and student community with top academics who have been selected from a global recruitment pool.
						</p>
					</div> 
		";
		/* --- END html info panel display for public users --- */
	}
?>
<!----------------------------------------------------->