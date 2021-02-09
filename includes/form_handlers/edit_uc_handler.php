<!--edit_event_handler.php-->

<?php 
require(dirname(__DIR__).'\classes\Logger.php');
	//Require Logger class
$_SESSION['user_alert']=array();
	//create an array to store user alerts in the session variable
$logger = new Logger();  
	//initialises a new logger object

/*-----------------------------------------------------*/
	//Declare variables (for input validation)
	$fname = ""; 		//first name
	$lname = ""; 		//last name
	$tel = "";			//telephone
	$em = ""; 			//email
	$em2 = ""; 			//email2 (confirm)
	$user_id ="";
	$user_email ="";
	$error_array = array();	//holds error messages in an array
	

/*-----------------------------------------------------*/


	if(isset($_POST['reset_uc_button']))
	//new event form submitted
	{ 
		$logger->write("STARTED > edit_uc_handler.php for $user_id $username");
		//send activity log

		/*-----------------------------*/
		//* Get First Name

		$fname = strip_tags($_POST['edit_fname']);
		//remove any html tags and store form values in the l.h. value
		$fname = str_replace (' ','',$fname);
		//removes any spaces in l.h. value
		$fname = ucfirst(strtolower($fname));
		//capitalizes first letter / makes other letters lower case
		$_SESSION['edit_fname']=$fname;
		//stores first name into session variable

		/*-----------------------------*/
	    //* Get Last Name

		$lname = strip_tags($_POST['edit_lname']);
		//remove any html tags and store form values in the l.h. value
		$lname = str_replace (' ','',$lname);
		//removes any spaces in l.h. value
		$lname = ucfirst(strtolower($lname));
		//capitalizes first letter / makes other letters lower case
		$_SESSION['edit_lname']=$lname;
		//stores last name into session variable

		/*-----------------------------*/
		//* Get Telephone

		$tel = strip_tags($_POST['edit_tel']);
		//remove any html tags and store form values in the l.h. value
		$tel = str_replace (' ','',$tel);
		//removes any spaces in l.h. value
		$_SESSION['edit_tel']=$tel;
		//stores telephone into session variable

		/*-----------------------------*/
		//* Get Email

		$em = strip_tags($_POST['edit_email']);
		//remove any html tags and store form values in the l.h. value
		$em = str_replace (' ','',$em);
		//removes any spaces in l.h. value
		$em = strtolower($em);
		//capitalizes first letter / makes other letters lower case
		$_SESSION['edit_email']=$em;
		//stores email into session variable

		/*-----------------------------*/
	    //* Get Confirm Email

		$em2 = strip_tags($_POST['edit_email2']);
		//remove any html tags and store form values in the l.h. value
		$em2 = str_replace (' ','',$em2);
		//removes any spaces in l.h. value
		$em2 = strtolower($em2);
		//capitalizes first letter / makes other letters lower case
		$_SESSION['edit_email2']=$em2;
		//stores email2 into session variable

		$user_id =$_POST['uid'];
		echo $user_id;
		/*-----------------------------*/
		//* Email Validation

		if($em==$em2)
		//checks if both emails match
		{
			if(filter_var($em, FILTER_VALIDATE_EMAIL))
			//checks if email is in valid format (.com, .co.uk , etc.)
			{

				$em = filter_var($em, FILTER_VALIDATE_EMAIL);
				//assign validated email to l.h. value

				/* check if email already exists */
				$user_email = $_POST['umail'];
				$e_check = mysqli_query($con_mysqli, "SELECT u_email FROM Users WHERE u_email = '$em'");
				//check if email already exists in USERS table
				if($user_email != $em){
					$logger->write("CHECKING > Different Email, $user_email & $em");
					$num_rows = mysqli_num_rows($e_check);
					if($num_rows>0)
					{
						array_push($error_array,"<br> Email already in use.<br>");
					//push message onto error array
					}
				}
				else{
					$logger->write("SUCCESS > Same Email, $user_email & $em");
				}
				
				//count the number of rows returned

				
				//IF rows more than 0, print error message
			}
			else
			{
				array_push($error_array,"<br> Invalid email format.<br>");
				//push message onto error array
			}
		} 
		else
		{
			array_push($error_array,"<br> Emails don't match.<br>");
			//push message onto error array
		}

		/*-----------------------------*/
		//* fname validation

		if(strlen($fname)>25||strlen($fname)<2)
		{
			array_push($error_array,"<br> Your first name must be between 2 and 25 characters.<br>");
			//push message onto error array
		}

		//* lname validation

		if(strlen($lname)>25||strlen($lname)<2)
		{
			array_push($error_array,"<br> Your last name must be between 2 and 25 characters.<br>");
			//push message onto error array
		}

		/*-----------------------------*/
		//* telephone validation

		if(preg_match ('/[^0-9]/', $tel))
		{
			array_push($error_array,"<br> Your telephone number can only contain numbers.<br>");
			//push message onto error array
		}


		if(empty($error_array)) 
		//valid input-no errors found hence error_array is empty
		{
			//[Simple Update] updates current event information (no roll-back)
			$user_name = strtolower($fname . "_" . $lname);
			//generate username by concatenating first name and last name

			$check_username_query = mysqli_query($con_mysqli,"SELECT u_username FROM Users WHERE u_username='$user_name'");
			//check if username exists in database

			$i=0; 
			//counter var

			while(mysqli_num_rows($check_username_query)!=0)
			//if username exists, add number to username
			{
				$i++; 
				//increment
				$user_name = $user_name."_".$i;
				//add number behind user name
				$check_username_query = mysqli_query($con_mysqli, "SELECT u_username FROM Users WHERE u_username='$user_name'");
				//check if modified username exists in database
			}

			/*-----Update users table-----*/

			$query=mysqli_query($con_mysqli, "UPDATE Users SET u_first_name = '$fname', u_last_name = '$lname', u_telephone = '$tel', u_username = '$user_name', u_email = '$em' WHERE u_id='$user_id'"); 
			//die(mysqli_error($con_mysqli));

			if($query==true)
			//userstable successfully updated
			{
				array_push($_SESSION['user_alert'],"<div class='form_alert'> ✓ User Updated!</div>");
				//* Insert 'signup success' session into error_array
				$logger->write("SUCCESS > Update Users table e_id: $user_id (simple)");
				//send activity log

				/*-----------------------------*/
				//* unset Session Variables (once successful)

				unset($_SESSION['edit_fname']);
				unset($_SESSION['edit_lname']);
				unset($_SESSION['edit_tel']);
				unset($_SESSION['edit_email']);
				unset($_SESSION['edit_email2']);
				unset($_SESSION['username']);

				/*-----------------------------*/
				//* Redirect user to uc_index

				header("Location: dirname(__DIR__).\..\login_uc.php");
				//redirect the user to index_uc.php (login is successful)
				//header() is used to send a raw HTTP header

				exit();
				//shuts down execution of the current script
			}
			else
			//presentation  update unsuccessful
			{
				array_push($error_array,"<div class='form_alert'> ! User update unsuccessful. Contact Support.</div>");
				echo 
				$logger->write("FAILURE > Update of User table u_id: $user_id, $em, $fname, $user_name, 'mysql_error()' (simple)");
				//echo("Error description: " . $mysqli -> error);
				//send activity log
			}
		}
		else
		{
			array_push($error_array,"<div class='form_alert'> ! User update unsuccessful. Invalid input.</div>");
			//* Insert error message into error_array
			
			$logger->write("FAILURE > invalid input");
			//send activity log
		}
	}

	$logger->close();
	//close logger
?>