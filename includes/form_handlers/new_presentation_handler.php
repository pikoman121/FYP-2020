<!--new_presentation_handler.php-->

<?php 

	$username="";
	$acctype="";

	$_SESSION['user_alert']=array();
	//create an array to store user alerts in the session variable

    if(isset($_SESSION['username']))
	{
		$username=$_SESSION['username'];
		$acctype=$_SESSION['acctype'];
	}
	//fetch user data

	require(dirname(__DIR__).'\email_handlers\PHPMailer\PHPMailerAutoload.php');
	//Require the PHPMailer php script
	require(dirname(__DIR__).'\email_handlers\credentials.php');
	//Require data from crediantial.php
	require(dirname(__DIR__).'\classes\Logger.php');

	$logger = new Logger(); //start logger 
	//initialises a new logger object
	$_SESSION['present_date']="";
	$_SESSION['present_time']="";
	$_SESSION['present_room']="";
	$_SESSION['rep_fname']="";
	$_SESSION['rep_lname']="";
	$_SESSION['rep_email']="";

/*-------------------------new_event_handler(UC)-------------------------*/

	/*-----------------------------------------------------*/
	//Declare variables (for input validation)

	$present_date = ""; 		//present date
	$present_time = ""; 		//present time
	$present_room = ""; 		//present room
	$present_title= "";			//present title
	$rep_username="";			//rep username
	$rep_password="";			//rep password
	$rep_password_open="";		//rep password (not encrypted)
	$rep_profile_pic="";		//rep profile
	$repfname="";				//rep first name
	$replname="";				//rep last name
	$rep_fullname="";			//rep full name
	$rep_email="";				//rep email
	$rep_id="";					//rep id
	$ass_id="";					//assessment id
	$event_id="";			    //eid id
	$unique_id="";				//unique id
	$date = date("Y-m-d"); //get current date 
	$event_header="";
	$event_uc="";
	$event_uc_email="";

	$error_array = array();	//holds error messages in an array

	/*-----------------------------------------------------*/
	//Upon clicking the submit button get Input Values for validation 

	if(isset($_POST['new_present_button']))
	//new event form submitted
	{
		$logger->write("STARTED > new_presentation_handler.php for $acctype $username");
		//send activity log

		/*-----------------------------*/
		//* Get presentation date

		$present_date = date('Y-m-d', strtotime($_POST['present_date']));
		//fetch presentation dat
		$_SESSION['present_date']=$present_date;
		//stores presentation date into session variable

		/*-----------------------------*/
		//* Get presentation time

		$present_time = date("H:i:s", strtotime($_POST['present_time']));
		//fetch presentation time
		$_SESSION['present_time']=$present_time;
		//stores presentation time into session variable

		/*-----------------------------*/
		//* Get Unit Teaching Period

		$present_room = strip_tags($_POST['present_room']);
		//remove any html tags and store form values in the l.h. value
		$present_room = strtoupper($present_room);
		//capitalizes all letters
		$_SESSION['present_room']= $present_room;
		//stores presentation room into session variable

		/*-----------------------------*/
		//* Get Rep First Name

		$repfname = strip_tags($_POST['rep_fname']);
		//remove any html tags and store form values in the l.h. value
		$repfname = str_replace (' ','',$repfname);
		//removes any spaces in l.h. value
		$repfname = ucfirst(strtolower($repfname));
		//capitalizes first letter / makes other letters lower case
		$_SESSION['rep_fname']=$repfname;
		//stores first name into session variable

		/*-----------------------------*/
		//* Get Rep Last Name

		$replname = strip_tags($_POST['rep_lname']);
		//remove any html tags and store form values in the l.h. value
		$replname = str_replace (' ','',$replname);
		//removes any spaces in l.h. value
		$replname = ucfirst(strtolower($replname));
		//capitalizes first letter / makes other letters lower case
		$_SESSION['rep_lname']=$replname;
		//stores last name into session variable

		/*-----------------------------*/
		//* Get Email

		$rep_email = strip_tags($_POST['rep_email']);
		//remove any html tags and store form values in the l.h. value
		$rep_email = str_replace (' ','',$rep_email);
		//removes any spaces in l.h. value
		$rep_email = strtolower($rep_email);

		$_SESSION['rep_email']=$rep_email;
		//stores email into session variable

		//***
		$restrict_email=false;
		//boolean var
		$check_email_query = mysqli_query($con_mysqli,"SELECT u_email FROM Users WHERE u_email='$rep_email' AND u_acc_type='uc'");
		//check if this email already belongs to a UC
	
		if(mysqli_num_rows($check_email_query)>0)
		//email already exists
		{
			$restrict_email=true;
			$logger->write("Failure > Email: Email is not same as UC");
		}
		//***

		/*-----------------------------*/
		//* Get Assessment Type

		$ass_id = $_POST['assessment_type'];
		//get assessment id (see Assessment.php)

		/*-----------------------------*/
		//* Get event id

		$event_id = $_POST['event_id'];
		//get assessment id (see Assessment.php)

		/*-----------------------------*/
		//* email validation

		if(!filter_var($rep_email, FILTER_VALIDATE_EMAIL))
		//checks if email is in valid format (.com, .co.uk , etc.)
		{
			array_push($error_array,"Invalid email format.<br>");
			//push message onto error array
		}
		//***
		if($restrict_email==true)
		//checks if email exists in a UC acc
		{
			array_push($error_array,"This email is restricted. Enter a different email.<br>");
			//push message onto error array
		}
		//***

		/*-----------------------------*/
		//* date validation

		if($date > $present_date)
		{
			array_push($error_array,"Presentation date cannot be earlier than current date.<br>");
			//push message onto error array
		}

		/*-----------------------------*/
		//* location validation

		if(strlen($present_room)>50||strlen($present_room)<2)
		{
			array_push($error_array,"Location must be between 2 and 50 characters.<br>");
			//push message onto error array
		}

		/*-----------------------------*/
		//* first name validation

		if(strlen($repfname)>25||strlen($repfname)<2)
		{
			array_push($error_array,"First name must be between 2 and 25 characters.<br>");
			//push message onto error array
		}

		/*-----------------------------*/
		//* last name validation

		if(strlen($replname)>25||strlen($replname)<2)
		{
			array_push($error_array,"Last name must be between 2 and 25 characters.<br>");
			//push message onto error array
		}

		if(empty($error_array)) 
		//valid input-no errors found hence error_array is empty
		{
			//*[1] Create a new representative user account 

			/*-----Fetch Event Data-----*/

			$event_query=mysqli_query($con_mysqli, "SELECT * FROM Events WHERE e_id = $event_id");
			//query event database
			$event_row = mysqli_fetch_array($event_query);
			//push results into array

			$event_header=$event_row['e_unit_code']." ".$event_row['e_unit_title'];
			//get event title
			$event_uc=$event_row['e_uc_name'];
			//get event uc name
			$event_uc_email=$event_row['e_uc_email'];
			//get event uc email
			$present_count=$event_row['e_num_presentations'];
			//get num of presentations

			/*-----------------------------*/
			//* generate a new username and presentation title

			$i=1;
			//counter variable

			$rep_username = $event_row['e_unit_code']."_".$event_row['e_teaching_period']."_p";
			//get a temporary username (uses $placeholder in new_presentation_form.php)

			$rep_username = str_replace (' ','',$rep_username);
			//removes any spaces in l.h. value

			$check_username_query = mysqli_query($con_mysqli,"SELECT u_username FROM Users WHERE u_username='$rep_username$i'");
			//check if username exists in database

			while(mysqli_num_rows($check_username_query)!=0)
			//if username exists, add number to username
			{
				$i++; 
				//increment
				$check_username_query = mysqli_query($con_mysqli, "SELECT u_username FROm Users WHERE u_username='$rep_username$i'");
				//check if modified username exists in database
			}

			$rep_username=$rep_username.$i;
			//create a new temporary username

			$present_title=$event_row['e_unit_code']." ".$event_row['e_teaching_period']." Presentation ".$i;
			//assign a temporary presentation title

			/*-----------------------------*/
			//* generate a new password

			$rep_password= strtolower($repfname."thepig".rand(1,99));
			//create a password from $temp_username
			$rep_password_open=$rep_password;
			//store password without encryption

			$rep_password=md5($rep_password); 
			//encrypts password before sending to database

			/*-----------------------------*/
			//*set profile pic

			$rep_profile_pic = "assets/images/profile_pictures/representative_profile.png";

			/*-----------------------------*/
			//* Insert values into database

			$user_query = mysqli_query($con_mysqli, "INSERT INTO Users VALUES
				(NULL,'rep','$repfname','$replname','-','$rep_username','$rep_email','$rep_password','$date','$rep_profile_pic','0','0','no')");
			//id column is kept as NULL


			if($user_query==true)
			//if account creation was successful
			{
				$logger->write("SUCCESS > insertion into Users table: ".$rep_username." ".$rep_email);
				//send activity log

				//*[2] insert new presentation record into presentatin table

				/*-----------------------------*/
				//* Get representative id (from newly created user)

				$rep_id = mysqli_insert_id($con_mysqli);
				//fetch representative id

				$rep_fullname = $repfname." ".$replname;

				/*-----------------------------*/
				//* Generate a unique id for this presentation (from newly created user)

				$unique_id = base_convert(microtime(false), 10, 36);

				/*-----------------------------*/
				//* Insert values into Presentations table ***

				$pending="pending";

				$present_query = mysqli_query($con_mysqli, "INSERT INTO Presentations VALUES
				(NULL,'$present_title','$pending','$pending','$unique_id','$present_date','$present_time','$present_room','no',NULL,'no','0','0','$rep_id','$rep_fullname','$rep_email','$event_id','$ass_id','$pending')");//Bella (Part-1)

				/*-----------------------------*/
				//* Increment num presentations in Evens table ***

				$present_count++;
				//increment number of presentations
				$update_query = mysqli_query($con_mysqli, "UPDATE Events SET e_num_presentations ='$present_count' WHERE e_id = '$event_id'");
				//update number of presentations in events table for $event_id

				if($present_query==true && $update_query==true)
				//successfully inserted into presentation table and events table updated
				{
					/*-----------------------------*/
					//* increment num presentations in the events tablet

					$logger->write("SUCCESS > insertion into Presentations table: ".$present_title);
					//send activity log

					/*-----------------------------*/
					//*[3] Send notification email to the representative

					$mail = new PHPMailer();
					//create a new mailer object
					$mail->isSMTP();
					//set mail protocol to simple mail transfer protocol (SMTP)
					$mail->Host = 'smtp.gmail.com';
					//set host to google SMTP
					$mail->SMTPAuth = true;
					//enable SMTP authentication
					$mail->Username = EMAIL; //see credential.php
					//set email username
					$mail->Password = PW; //see credential.php
					//set email password
					$mail->SMTPSecure = 'ssl';
					// Enable TLS encryption (ssl also accepted)
					$mail->Port = 465;                                    
					// Connect to TCP port 465
					$mail->setFrom(EMAIL, 'PRESENT'); 
					//construct email form
					$mail->addAddress($rep_email, $repfname); 
					//set recipients email and name
					$mail->isHTML(true);
					//set email as HTML email
					
					/*-----Compose Email-----*/
					
					/* Start of html message body */

					$message=

					"<html>
						<body style=\"color: #000; font-size: 1rem; text-decoration: none; font-family: 'Arial', sans-serif; background-color: #F7F7F7;\">

							<div class=\"wrapper\" style=\"max-width: 46.875rem; margin: auto auto; padding: 0.938rem;\">

								<div id=\"content\" style=\"font-size: 1rem; padding: 1.563rem; background-color: #fff; border:none; border-radius: 0.563rem; box-shadow: 0.031rem 0.031rem #000;\">
									<div id=\"logo\">
										<center>
											<h1 style=\"margin: 0.625rem;\">

												<!--present logo-->
												<a href=\"http://localhost/present_app/landing.php\" target=\"_blank\">
													<img style=\"max-height: 7.5rem;\" src=\"https://lh3.googleusercontent.com/zsbknjX7egLSyCS8jwMSJn-QUR6Ybs2GpP7FkBVslSiVm58QhGJ-0I5nSwhkueoyLLARDj7D9aS4uSkiaeVSJ6B1oqDX39S6Uyd4OZVI6YYOjAn9Jnhd1ZoP1NMk_m2jhgfYkbCc0Q=w2400\">
												</a>
											</h1>
										</center>
									</div>

										<div id=\"text\" style=\"text-align:center; margin:0.313rem; padding:1.563rem;\">
											<!--Greeting-->
											<h1 style=\"font-size: 1.5rem; color:#1D1A1A\";><center>Hi $repfname, you have a new presentation!</center></h1>
											<!--event title-->
											<h3 style=\"color:#CD0000;\"> ICT315 Pandemics and Pandemonium </h3>
											<!--presention title-->
											<h3 style=\"padding:0.313rem;\">$present_title</h3>
											<hr>
											<p style=\"font-size: 1.5rem; color:#1D1A1A\";><center>Login with the following username and password to <br>begin uploading your presentation.</center></p>

											<!--username-->
											<h4><center >Username: $rep_username </center></h4>
											<!--password-->
											<h4><center >Password: $rep_password_open </center> </h4>
											<!--in-charge / contact-->
											<h4>Unit Coordinator: <a href=\" mailto: $event_uc_email?subject=Re: $event_header - $present_title\">$event_uc</a></h4>
											<!--link to preview-->
											<hr>
											<center style=\"margin-top:1.563rem;\">
												<a href=\"http://localhost/present_app/login_select.php\" target=\"_blank\" style=\"background-color: #CD0000; color: #FFF; text-decoration: none; font-size: 1rem; padding: 0.438rem 0.938rem; border-radius:1.25rem; border-color:#fff;\">
													Login to Present
												</a>
											</center>
										</div>
								</div>
							</div>
						</body>
					</html> 
					"; 
					/* End of html message body */

					$mail->Subject = 'Your Have a New Presentation!';
					//set subject of email
					$mail->headers  = 'MIME-Version: 1.0' . "\r\n";
					//set email header
					$mail->headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					//set email header
					$mail->Body = $message; 

					/*-----Send Email-----*/

					if(!$mail->send()) //send email
				    //mail not delivered successfully
					{
						//* Insert 'signup failure' message into error_array
						array_push($error_array,"<div class='form_alert'> ! Presentation creation unsuccessful. Contact Support.</div>");

						$logger->write("FAILURE > mail error: ".$rep_email." ".$mail->ErrorInfo);
						//send activity log
					}
					else
					//mail delivered successfully
					{
						/*-----------------------------*/
						//* Insert 'signup success' message into error_array
						array_push($_SESSION['user_alert'],"<div class='form_alert'> ✓ New presentation added! A notification email has been sent to the representative.</div>");
						
						$logger->write("SUCCESS > mail delivered: ".$rep_email);
						//send activity log
					}

					/*-----------------------------*/
					//* Clear Session Variables (once successful)



					/*-----------------------------*/
					//* Redirect user to uc_index
					header("Location: index_uc.php");
					//redirect the user to index_uc.php (login is successful)
					//header() is used to send a raw HTTP header

					exit();
					//shuts down execution of the current script

				}
				else
				//failed insertion into presentation table
				{
					//* Insert error message into error_array
					array_push($error_array,"<div class='form_alert'> ! Presentation creation unsuccessful. Contact Support.</div>");

					$logger->write("FAILURE > insertion into Presentations table: ".$present_title);
					//send activity log

					$user_query = mysqli_query($con_mysqli, "DELETE FROM Users WHERE u_id = '$rep_id' ");
					//delete previously created user account

					if($user_query==true)
					//user account deleted
					{
						$logger->write("SUCCESS > deletion from Users table: ".$rep_username." ".$rep_email);
						//send activity log
					}
					else
					//user account not deleted
					{
						$logger->write("FAILURE > deletion from Users table: ".$rep_username." ".$rep_email);
						//send activity log
					}
				}
			}
			else
			//failed creation of user account
			{
				//* Insert error message into error_array
				array_push($error_array,"<div class='form_alert'> ! Presentation creation unsuccessful. Contact Support.</div>");

				$logger->write("FAILURE > insertion into Users table: ".$rep_username." ".$rep_email);
				//send activity log
			}
		}	
		else
		//invalid input detected
		{
			//* Insert error message into error_array
			array_push($error_array,"<div class='form_alert'> ! Presentation creation unsuccessful. Invalid input.</div>");

			$logger->write("FAILURE > invalid input");
			//send activity log
		}
		
	$logger->close();
	//close logger
	}

?>