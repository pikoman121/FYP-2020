<!--tester.php-->

<!----------------------------------------------------->
<!--http://localhost:8080/present_app/tester.php -->
<!--http://localhost:8080/phpmyadmin/--> 
<!----------------------------------------------------->

<!DOCTYPE html>

<?php 

	include("info_panels.php");
	//includes file to display header and sidebar

	include("includes/classes/Presentation.php");
	//include the Presentation class

	include("includes/classes/Logger.php");
	//fetch Logger class

?>

<html>

<head>
	<title>presentation_test</title>
</head>

	<body style="color:white; ">


		<!--------- test code here --------->
		<div style="position:absolute; margin-left:20%; margin-top:10%; width:70%">

			<?php


						
			require('includes/form_handlers/edit_presentation_handler.php');
			//Require new_presentation_handler.php 

			?>




		</div>
		<!--------- test code here --------->

	</body>

</html>