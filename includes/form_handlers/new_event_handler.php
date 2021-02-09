<!--new_event_handler.php-->

<?php 

	$username="";
	$acctype="";

	$_SESSION['user_alert']=array();
	//create an array to store user alerts in the session variable

    if(isset($_SESSION['username']))
	{
		$username=$_SESSION['username'];
		$acctype=$_SESSION['acctype'];

		echo $username;
	}
	//fetch user data

	require(dirname(__DIR__).'\classes\Logger.php');
	//Require Logger class

	$logger = new Logger(); //start logger 
	//initialises a new logger object
	$_SESSION['event_unit_code']="";
	$_SESSION['event_unit_title']="";
	$_SESSION['event_teaching_period']="";
	$_SESSION['event_start_date']="";
	$_SESSION['event_end_date']="";
	$_SESSION['event_campus']="";
/*-------------------------new_event_handler(UC)-------------------------*/

	/*-----------------------------------------------------*/
	//Declare variables (for input validation)

	$unit_code = ""; 		//unit code
	$unit_title = ""; 		//unit title
	$teaching_period = ""; 	//teaching period
	$unit_desc= "";			//unit description
	$start_date = ""; 		//start date
	$end_date = ""; 		//end date
	$event_campus = ""; 	//campus
	$error_array = array();	//holds error messages in an array

	/*-----------------------------------------------------*/
	//Upon clicking the submit button get Input Values for validation 

	if(isset($_POST['new_event_button']))
	//new event form submitted
	{
		$logger->write("STARTED > new_event_handler.php for $acctype $username");
		//send activity log

		/*-----------------------------*/
		//* Get Unit Code

		$unit_code = strip_tags($_POST['event_unit_code']);
		//remove any html tags and store form values in the l.h. value
		$unit_code = str_replace (' ','',$unit_code);
		//removes any spaces in l.h. value
		$unit_code = strtoupper($unit_code);
		//capitalizes all letters
		$_SESSION['event_unit_code']=$unit_code;
		//stores unit code into session variable

		/*-----------------------------*/
		//* Get Unit Title

		$unit_title = strip_tags($_POST['event_unit_title']);
		//remove any html tags and store form values in the l.h. value
		$_SESSION['event_unit_title']=$unit_title;
		//stores unit title into session variable

		/*-----------------------------*/
		//* Get Unit Teaching Period

		$teaching_period = strip_tags($_POST['event_teaching_period']);
		//remove any html tags and store form values in the l.h. value
		$teaching_period = strtoupper($teaching_period);
		//capitalizes all letters
		$_SESSION['event_teaching_period']=$teaching_period;
		//stores teaching period into session variable

		/*-----------------------------*/
		//* Get Unit Description

		$unit_desc = strip_tags($_POST['event_unit_desc']);
		//remove any html tags and store form values in the l.h. value
		$unit_desc = str_replace(array('\r\n', '\r', '\n'), '<br />', $unit_desc);
		//replace any new line characters with line break
		$_SESSION['event_unit_desc']=$unit_desc ;
		//stores unit desc into session variable

		/*-----------------------------*/
		//* Get Event Start Date

		$start_date = date('Y-m-d', strtotime($_POST['event_start_date']));
		$_SESSION['event_start_date']=$start_date ;
		//stores start date into session variable

		/*-----------------------------*/
		//* Get Event End Date

		$end_date = date('Y-m-d', strtotime($_POST['event_end_date']));
		$_SESSION['event_end_date']=$end_date ;
		//stores start date into session variable

		/*-----------------------------*/
		//* Get Event Campus

		$event_campus = strip_tags($_POST['event_campus']);
		//remove any html tags and store form values in the l.h. value
		$_SESSION['event_campus']=$event_campus;
		//stores event campus into session variable


		/*-----------------------------*/
		//* date validation

		if($start_date > $end_date) /*--validate start and end date--*/
		{
			array_push($error_array,"Event start date cannot be later than the end date.<br>");
		}

		/*-----------------------------*/
		//* unit code validation

		if(strlen($unit_code)>20||strlen($unit_code)<2)
		{
			array_push($error_array,"Unit code must be between 2 and 20 characters.<br>");
			//push message onto error array
		}

		/*-----------------------------*/
		//* unit title validation

		if(strlen($unit_title)>50||strlen($unit_title)<2)
		{
			array_push($error_array,"Unit title must be between 2 and 50 characters.<br>");
			//push message onto error array
		}

		/*-----------------------------*/
		//* teaching period validation

		if(strlen($teaching_period)>50||strlen($teaching_period)<2)
		{
			array_push($error_array,"Teaching Period must be between 2 and 50 characters.<br>");
			//push message onto error array
		}

		/*-----------------------------*/
		//* campus validation

		if(strlen($event_campus)>50||strlen($event_campus)<2)
		{
			array_push($error_array,"Event campus must be between 2 and 50 characters.<br>");
			//push message onto error array
		}

		if(empty($error_array)) 
		//valid input-no errors found hence error_array is empty
		{
			/*-----------------------------*/
			//* Insert values into database

			$user_query = mysqli_query($con_mysqli, "SELECT * FROM Users WHERE u_username='$username'");
			//query user table for UC data
			$user_row = mysqli_fetch_array($user_query);
			//push results into an array
			$year=date("Y");
			//get current year
			$uc_id=$user_row['u_id'];
			//get uc user id
			$uc_name=$user_row['u_first_name']." ".$user_row['u_last_name'];
			//get uc name
			$uc_email=$user_row['u_email'];
			//get uc email
			$uc_tel=$user_row['u_telephone'];
			//get uc telephone
			$date = date ("Y-m-d");
			//get current date

			$query = mysqli_query($con_mysqli, "INSERT INTO Events VALUES
				(NULL,'$year','$unit_code','$unit_title','$unit_desc','$teaching_period','$event_campus','$uc_id','$uc_name','$uc_email','$uc_tel','$date','$start_date','$end_date','0','no')");
			//insert values into Events table id column is kept as NULL 

			if($query==true)
			//insertion into database successful
			{			
				//* Insert success message into error_array
				array_push($_SESSION['user_alert'],"<div class='form_alert'> ✓ Your event has been successfully added.</div>");

				$logger->write("SUCCESS > insertion into EVENTS table: ".$unit_code." ".$teaching_period);
				//send activity log

				//increment user's events
				$event_count=$user_row['u_num_events'];
				//fetch event count
				$event_count++;
				//increment count
				$update_query = mysqli_query($con_mysqli, "UPDATE Users SET u_num_events ='$event_count' WHERE u_id = '$uc_id'");
				//update count

				//* Clear Session Variables (once registration is successful


				//prevents previous registrants data from showing upon login refresh

				header("Location: index_uc.php");
				//redirect the user to index_uc.php (add event is successful)
				//header() is used to send a raw HTTP header
			}
			else
			{
				//* Insert error message into error_array
				array_push($error_array,"<div class='form_alert'> ! Event creation unsuccessful. Contact support.</div>");

				$logger->write("FAILURE > insertion into EVENTS table: ".$unit_code." ".$teaching_period);
				//send activity log
			}
		}
		else
		{
			//* Insert error message into error_array
			array_push($error_array,"<div class='form_alert'> ! Event creation unsuccessful. Invalid input.</div>");

			$logger->write("FAILURE > invalid input");
			//send activity log
		}
	}
	$logger->close();
	//close logger
?>
