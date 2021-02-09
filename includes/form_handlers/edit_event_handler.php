<!--edit_event_handler.php-->

<?php 

	require(dirname(__DIR__).'\classes\Logger.php');
	//Require Logger class
	$_SESSION['user_alert']=array();
	//create an array to store user alerts in the session variable
	$logger = new Logger();  
	//initialises a new logger object
	unset($_SESSION['event_unit_code']);
	unset($_SESSION['event_unit_title']);
	unset($_SESSION['event_teaching_period']);
	unset($_SESSION['event_unit_desc']);
	unset($_SESSION['event_start_date']);
	unset($_SESSION['event_end_date']);
	unset($_SESSION['event_campus']);
/*-----------------------------------------------------*/
	//Declare variables (for input validation)

	$unit_code = ""; 		//unit code
	$unit_title = ""; 		//unit title
	$teaching_period = ""; 	//teaching period
	$unit_desc= "";			//unit description
	$start_date = ""; 		//start date
	$end_date = ""; 		//end date
	$event_campus = ""; 	//campus
	$event_id ="";
	$error_array = array();	//holds error messages in an array

/*-----------------------------------------------------*/


	if(isset($_POST['edit_event_button']))
	//new event form submitted
	{ 
		$logger->write("STARTED > edit_event_handler.php for $acctype $username");
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
		//* Get Event id

		$event_id = $_POST['eid'];


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
			//[Simple Update] updates current event information (no roll-back)

			/*-----Update Events table-----*/

			$event_query=mysqli_query($con_mysqli, "UPDATE Events SET 
			e_unit_code = '$unit_code',
			e_unit_title = '$unit_title',
			e_teaching_period = '$teaching_period',
			e_unit_description = '$unit_desc',
			e_start_date = '$start_date',
			e_end_date = '$end_date',
			e_campus = '$event_campus'
			WHERE e_id='$event_id'"); 

			if($event_query==true)
			//eventstable successfully updated
			{
				array_push($_SESSION['user_alert'],"<div class='form_alert'> ✓ Event Updated!</div>");
				//* Insert 'signup success' session into error_array
				$logger->write("SUCCESS > Update Events table e_id: $event_id (simple)");
				//send activity log

				/*-----------------------------*/
				//* unset Session Variables (once successful)



				/*-----------------------------*/
				//* Redirect user to uc_index

				header("Location: index_uc.php");
				//redirect the user to index_uc.php (login is successful)
				//header() is used to send a raw HTTP header

				exit();
				//shuts down execution of the current script
			}
			else
			//presentation  update unsuccessful
			{
				array_push($error_array,"<div class='form_alert'> ! Event update unsuccessful. Contact Support.</div>");
				//* Insert error message into error_array
				$logger->write("FAILURE > Update of Events table e_id: $event_id (simple)");
				//send activity log
			}
		}
		else
		{
			array_push($error_array,"<div class='form_alert'> ! Event update unsuccessful. Invalid input.</div>");
			//* Insert error message into error_array
			$logger->write("FAILURE > invalid input");
			//send activity log
		}
	}

	$logger->close();
	//close logger
?>