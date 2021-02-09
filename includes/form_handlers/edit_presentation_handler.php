<!--edit_presentation_handler.php-->

<?php 

	require(dirname(__DIR__).'\email_handlers\PHPMailer\PHPMailerAutoload.php');
	//Require the PHPMailer php script
	require(dirname(__DIR__).'\email_handlers\credentials.php');
	//Require data from crediantial.php
	require(dirname(__DIR__).'\classes\Logger.php');
	//Require Logger class
	$_SESSION['user_alert']=array();
	//create an array to store user alerts in the session variable
	$logger = new Logger();  
	//initialises a new logger object
	unset($_SESSION['present_date']);
	unset($_SESSION['present_time']);
	unset($_SESSION['present_room']);
	unset($_SESSION['rep_fname']);
	unset($_SESSION['rep_lname']);
	unset($_SESSION['rep_email']);
/*-------------------------edit_presentation_handler(UC)-------------------------*/

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
	$p_id="";			    	//pid id
	$unique_id="";				//unique id
	$date = date("Y-m-d"); 		//get current date 
	$event_id="";
	$event_header="";
	$event_uc="";
	$event_uc_email="";
	$curr_email="";

	/*-----------------------------------------------------*/
	$error_array = array();	//holds error messages in an array


	/*-----------------------------------------------------*/
	//Upon clicking the save button get Input Values for validation 

	if(isset($_POST['edit_present_button']))
	//new event form submitted
	{
		$logger->write("STARTED > edit_presentation_handler.php for $acctype $username");
		//send activity log

		/*-----------------------------*/
		//* Get presentation date

		$present_date = date('Y-m-d', strtotime($_POST['present_date']));
		//fetch presentation dat
		$_SESSION['present_date']=$present_date;
		//stores unit code into session variable

		/*-----------------------------*/
		//* Get presentation time

		$present_time = date("H:i:s", strtotime($_POST['present_time']));
		//fetch presentation time
		$_SESSION['present_time']=$present_time;
		//stores present time into session variable

		/*-----------------------------*/
		//* Get Unit Teaching Period

		$present_room = strip_tags($_POST['present_room']);
		//remove any html tags and store form values in the l.h. value
		$_SESSION['present_room']=$present_room;
		//stores present room into session variable

		/*-----------------------------*/
		//* Get Rep First Name

		$repfname = strip_tags($_POST['rep_fname']);
		//remove any html tags and store form values in the l.h. value
		$repfname = str_replace (' ','',$repfname);
		//removes any spaces in l.h. value
		$repfname = ucfirst(strtolower($repfname));
		//capitalizes first letter / makes other letters lower case
		$_SESSION['rep_fname']=$repfname;
		//stores present room into session variable


		/*-----------------------------*/
		//* Get Rep Last Name

		$replname = strip_tags($_POST['rep_lname']);
		//remove any html tags and store form values in the l.h. value
		$replname = str_replace (' ','',$replname);
		//removes any spaces in l.h. value
		$replname = ucfirst(strtolower($replname));
		//capitalizes first letter / makes other letters lower case
		$_SESSION['rep_lname']=$replname;
		//stores present room into session variable

		$rep_fullname = $repfname." ".$replname;
		//create a full name string

		/*-----------------------------*/
		//* Get Email

		$rep_email = strip_tags($_POST['rep_email']);
		//remove any html tags and store form values in the l.h. value
		$rep_email = str_replace (' ','',$rep_email);
		//removes any spaces in l.h. value
		$rep_email = strtolower($rep_email);
		//capitalizes first letter / makes other letters lower case
		$_SESSION['rep_email']=$rep_email;
		//stores present room into session variable

		//***
		$restrict_email=false;
		//boolean var
		$check_email_query = mysqli_query($con_mysqli,"SELECT u_email FROM Users WHERE u_email='$rep_email' AND u_acc_type='uc'");
		//check if this email already belongs to a UC

		if(mysqli_num_rows($check_email_query)>0)
		//email already exists
		{
			$restrict_email=true;
			//restrict email
		}
		//***
				
		/*-----------------------------*/
		//* Get Assessment Type

		$ass_id = $_POST['assessment_type'];
		//get assessment id (see Assessment.php)

		/*-----------------------------*/
		//* Get presentation id

		$p_id = $_POST['pid'];
		//get presentation id 

		/*-----------------------------*/
		//* Fetch existing presentation data for update verification

		$present_query=mysqli_query($con_mysqli, "SELECT * FROM Presentations WHERE p_id='$p_id'"); //ok
		//fetch presentation 	
		$present_row=mysqli_fetch_array($present_query); 
		//push data into an array
		$curr_email=$present_row['p_rep_email'];
		//fetch email of current rep (to check if email changed)
		$event_id=$present_row['p_event_id'];
		//fetch event id
		$rep_id=$present_row['p_rep_id'];
		//fetch rep id

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
			if($curr_email==$rep_email)
			//email entered is the same as current email (user acc is the same)
			{
				//[Simple Update] update current presentation information without switching user accounts (no roll-back)

				/*-----Update presentations table-----*/

				$present_query=mysqli_query($con_mysqli, "UPDATE Presentations SET 
				p_date = '$present_date',
				p_time = '$present_time',
				p_room = '$present_room',
				p_rep_name = '$rep_fullname',
				p_assessment_id='$ass_id'
				WHERE p_id='$p_id'"); 

				/*-----Update users table-----*/

				$user_query=mysqli_query($con_mysqli, "UPDATE Users SET 
				u_first_name = '$repfname',
				u_last_name = '$replname'
				WHERE u_id='$rep_id'"); 

				if($present_query==true && $user_query==true)
				//presentations table successfully updated
				{
					array_push($_SESSION['user_alert'],"<div class='form_alert'> ✓ Presentation Updated!</div>");
					//* Insert 'signup success' session into error_array
					$logger->write("SUCCESS > Update Presentations table p_id: $p_id (no mailer)");
					//send activity log
					$logger->write("SUCCESS > Update Users table u_id: $rep_id");
					//send activity log

					/*-----------------------------*/
					//* unset Session Variables (once successful)

					unset($_SESSION['present_date']);
					unset($_SESSION['present_time']);
					unset($_SESSION['present_room']);
					unset($_SESSION['rep_fname']);
					unset($_SESSION['rep_lname']);
					unset($_SESSION['rep_email']);

					/*-----------------------------*/
					//* Redirect user to uc_index

					header("Location: index_uc.php");
					//redirect the user to index_uc.php (login is successful)
					//header() is used to send a raw HTTP header

					exit();
					//shuts down execution of the current script
				}
				else
				//presentation update unsuccessful
				{
					array_push($error_array,"<div class='form_alert'> ! Presentation update unsuccessful. Contact Support.</div>");
					//* Insert error message into error_array
					$logger->write("FAILURE > Update of Presentations table p_id: $p_id (no mailer)");
					//send activity log
					$logger->write("FAILURE > Update Users table u_id: $rep_id");
		    		//send activity log
				}
			}
			else
			{
			 	//[complex Update] update current presentation information and change user accounts (no roll-back)

				/*-----Update presentations table-----*/

				$present_query=mysqli_query($con_mysqli, "UPDATE Presentations SET 
				p_date = '$present_date',
				p_time = '$present_time',
				p_room = '$present_room',
				p_rep_name = '$rep_fullname',
				p_rep_email= '$rep_email',
				p_assessment_id='$ass_id'
				WHERE p_id='$p_id'"); 

				/*-----------------------------*/
				//* fetch username 

				$user_query=mysqli_query($con_mysqli, "SELECT * FROM Users WHERE u_id='$rep_id'"); //ok
				//fetch presentation 	
				$user_row=mysqli_fetch_array($user_query); 
				//push data into an array
				$rep_username=$user_row['u_username'];
				//fetch username

				/*-----------------------------*/
				//* generate a new password

				$rep_password= strtolower($repfname."thepig".rand(1,99));
				//create a password from $temp_username
				$rep_password_open=$rep_password;
				//store password without encryption
				$rep_password=md5($rep_password); 
				//encrypts password before sending to database

				/*-----Update users table-----*/

				$user_query=mysqli_query($con_mysqli, "UPDATE Users SET 
				u_first_name = '$repfname',
				u_last_name = '$replname',
				u_email ='$rep_email',
				u_password='$rep_password'
				WHERE u_id='$rep_id'"); 

				if($present_query==true && $user_query==true)
				//presentations table successfully updated
				{
					$logger->write("SUCCESS > Update Presentations table p_id: $p_id (+mailer)");
					//send activity log
					$logger->write("SUCCESS > Update Users table u_id: $rep_id");
					//send activity log

					/*-----Fetch Event Data for Email-----*/

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

					/*-----------------------------*/
					//Send notification to new user email

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
						array_push($error_array,"<div class='form_alert'> ! Presentation update unsuccessful. Contact Support.</div>");
						//* Insert 'signup failure' message into error_array
						$logger->write("FAILURE > mail error: ".$rep_email." ".$mail->ErrorInfo);
						//send activity log
					}
					else
					//mail delivered successfully
					{
						array_push($_SESSION['user_alert'],"<div class='form_alert'> ✓ Presentation Updated! A notification email has been sent to the representative.</div>");
						//* Insert 'signup success' message into error_array
						$logger->write("SUCCESS > mail delivered: ".$rep_email);
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
				}
				else
				//presentation update unsuccessful
				{
					array_push($error_array,"<div class='form_alert'> ! Presentation update unsuccessful. Contact Support.</div>");
					//* Insert error message into error_array
					$logger->write("FAILURE > Update of Presentations table p_id: $p_id (+mailer)");
					//send activity log
					$logger->write("FAILURE > Update Users table u_id: $rep_id");
		    		//send activity log
				}
			}
		}
		else
		//invalid input detected
		{
			array_push($error_array,"<div class='form_alert'> ! Presentation update unsuccessful. Invalid input.</div>");
			//* Insert error message into error_array
			$logger->write("FAILURE > invalid input");
			//send activity log
		}
	}
		
	$logger->close();
	//close logger
?>