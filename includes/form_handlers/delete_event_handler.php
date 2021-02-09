<!--delete_event_handler.php-->

<?php 

	//require('/opt/lampp/htdocs/present_app/includes/classes/Logger.php');
	//Require Logger class (already declared in edit event php)

	$_SESSION['user_alert']=array();
	//create an array to store user alerts in the session variable
	$logger = new Logger();  
	//initialises a new logger object

	if(isset($_POST['delete_event_button']))
	//new event form submitted
	{ 
		$logger->write("STARTED > delete_event_handler.php for $acctype $username");
		//send activity log

	    /*-----------------------------*/
		//* Get Event id

		$event_id = $_POST['eid'];
		/*-----------------------------*/
		//* Delete event (no roll-back)

		$event_query=mysqli_query($con_mysqli, "DELETE FROM Events WHERE e_id ='$event_id'"); 
		//* This is a simple delete - once done will not restore values

		if($event_query == true)
		//event deletion successful
		{
			/*-----------------------------*/
			//* Delete associated presentations (no roll-back)

			$present_query =  mysqli_query($con_mysqli, "SELECT * FROM Presentations WHERE p_event_id='$event_id'");
	
			/*------Fetch associated presentation Rows------*/
			while($present_row = mysqli_fetch_array($present_query)) 
			{
				$present_id = $present_row['p_id']; 
				//fetch presentation id
				$rep_id = $present_row['p_rep_id']; 
				//fetch rep id
				$status = "pending";
				$filePath_query=mysqli_query($con_mysqli, "SELECT * FROM presentations WHERE p_id='$present_id'");
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
				//Bella (end)
				/*-----Delete from Presentations table-----*/

				$present_query=mysqli_query($con_mysqli, "DELETE FROM Presentations WHERE p_id ='$present_id'"); 
				//* This is a simple delete - once done will not restore values

				/*-----Delete from users table-----*/

				$user_query=mysqli_query($con_mysqli, "DELETE FROM Users WHERE u_id ='$rep_id'"); 
				//* This is a simple delete - once done will not restore values


				if($present_query ==true && $user_query==true)
				//successful delete from presentations and users table
				{
					$logger->write("SUCCESS > Delete from Presentations table p_id: $present_id");
					//send activity log
					$logger->write("SUCCESS > Delete from Users table u_id: $rep_id");
					//send activity log
				}
				else
				//unsuccessful delete from either presentations or users table or both (check logger)
				{
					$logger->write("FAILURE > Delete from Presentations table p_id: $present_id");
					//send activity log
					$logger->write("FAILURE > Delete from Users table u_id: $rep_id");
					//send activity log
				}

				$present_query =  mysqli_query($con_mysqli, "SELECT * FROM Presentations WHERE p_event_id='$event_id'");//***
				//run query aagain to check if more presentations exist
			} 


			$logger->write("SUCCESS > Delete from Events table e_id: $event_id");
			//send activity log
			array_push($_SESSION['user_alert'],"<div class='form_alert'> ✓ Event Deleted</div>");
			//* Insert 'signup success' session into error_array

			/*-----------------------------*/
			//* Redirect user to uc_index

			header("Location: index_uc.php");
			//redirect the user to index_uc.php (login is successful)
			//header() is used to send a raw HTTP header

			exit();
			//shuts down execution of the current script
		}
		else
		//event deletion failed
		{
			$logger->write("FAILURE > Delete from Events table e_id: $event_id");
			//send activity log
			array_push($error_array,"<div class='form_alert'> ! Event deletion unsuccessful. Contact support.</div>");
			//* Insert error message into error_array
		}
	}

	$logger->close();
	//close logger
?>