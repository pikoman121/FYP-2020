<!--delete_presentation_handler.php-->

<?php 

	//require('/opt/lampp/htdocs/present_app/includes/classes/Logger.php');
	//Require Logger class (already declared in edit_presentation_handler.php)

	$_SESSION['user_alert']=array();
	//create an array to store user alerts in the session variable
	$logger = new Logger();  
	//initialises a new logger object


	if(isset($_POST['delete_present_button']))
	//new event form submitted
	{	
		/*-----------------------------*/
		//* Get presentation id

		$p_id = $_POST['pid'];
		//get presentation id 

		/*-----------------------------*/
		//* Fetch Presentation Data

		$present_query=mysqli_query($con_mysqli, "SELECT * FROM Presentations WHERE p_id='$p_id'"); 
		//fetch presentation 	
		$present_row=mysqli_fetch_array($present_query); 
		//push data into an array
		$rep_id=$present_row['p_rep_id'];
		//fetch rep id
	    $event_id=$present_row['p_event_id'];
		//fetch event id

		/*-----Fetch Event Data-----*/

		$event_query=mysqli_query($con_mysqli, "SELECT * FROM Events WHERE e_id = $event_id");
		//query event database
		$event_row = mysqli_fetch_array($event_query);
		//push results into array
		$present_count=$event_row['e_num_presentations'];
		//get num of presentations

		/*-----Update presentations table-----*/

		$status = "pending";
		$filePath_query=mysqli_query($con_mysqli, "SELECT * FROM presentations WHERE p_id='$p_id'");
	    $filePath_row = mysqli_fetch_array($filePath_query);
		$filePath = $filePath_row['p_video'];
		
	    if($filePath!= $status)
		{
      	if(!unlink($filePath))
		{
			$logger->write("FAILED > Couldn't delete the presentation video from Presentations table p_id: $p_id ");
		}
		else 
		{
			$logger->write("SUCCESS > Delete the presentation video from Presentations table p_id: $p_id ");
		}
		
	    }


		$present_query=mysqli_query($con_mysqli, "DELETE FROM Presentations WHERE p_id ='$p_id'"); 
		//* This is a simple delete - once done will not restore values

		/*-----Update users table-----*/

		$user_query=mysqli_query($con_mysqli, "DELETE FROM Users WHERE u_id ='$rep_id'");
		// * This is a simple delete - once done will not restore values


		if($present_query==true && $user_query==true)
		//presentations table successfully updated
		{
			/*-----------------------------*/
			//* Increment num presentations in Evens table ***

			$present_count--;
			//increment number of presentations
			$update_query = mysqli_query($con_mysqli, "UPDATE Events SET e_num_presentations ='$present_count' WHERE e_id = '$event_id'");
			//update number of presentations in events table for $event_id

			array_push($_SESSION['user_alert'],"<div class='form_alert'> ✓ Presentation Deleted</div>");
			//* Insert 'signup success' session into error_array
			$logger->write("SUCCESS > Delete from Presentations table p_id: $p_id (simple)");
			//send activity log
			$logger->write("SUCCESS > Delete from Users table u_id: $rep_id");
			//send activity log

			/*-----------------------------*/
			//* Redirect user to uc_index

			header("Location: index_uc.php");
			//redirect the user to index_uc.php (login is successful)
			//header() is used to send a raw HTTP header

			exit();
			//shuts down execution of the current script
		}
		else
		{
			array_push($error_array,"<div class='form_alert'> ! Presentation deletion unsuccessful. Contact Support.</div>");
			//* Insert error message into error_array
			$logger->write("FAILURE > Delete from Presentations table p_id: $p_id (simple)");
			//send activity log
		    $logger->write("FAILURE > Delete from Users table u_id: $rep_id");
			//send activity log
		}
	}
	$logger->close();
	//close logger
?>