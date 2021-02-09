<!--register_handler.php-->

<!-------------------------register_handler(Unit Coordinator)------------------------->

<?php

	require(dirname(__DIR__).'\email_handlers\PHPMailer\PHPMailerAutoload.php');
	//Require the PHPMailer php script
	require(dirname(__DIR__).'\email_handlers\credentials.php');
	//Require data from crediantial.php
	require(dirname(__DIR__).'\classes\Logger.php');
	//Require Logger class

	$logger = new Logger(); //start logger
	//initialises a new logger object

	/*-----------------------------------------------------*/
	//Declare variables (for input validation)

	$scode = ""; 		//security code
	$fname = ""; 		//first name
	$lname = ""; 		//last name
	$tel = "";			//telephone
	$em = ""; 			//email
	$em2 = ""; 			//email2 (confirm)
	$password = ""; 	//password
	$password2 = ""; 	//password2 (confirm)
	$date = ""; 		//sign up date
	$error_array = array();	//holds error messages in an array
	$pw_disp = "";

	/*-----------------------------------------------------*/
	//Upon clicking the register button get Input Values for validation 

	if (isset($_POST['register_uc_button'])) 
	//if register_button variable is not NULL
	{

		$logger->write("STARTED > register_handler.php for acctype: UC");
		//send activity log

		/*-----------------------------*/
		//* Get Security Code

		$scode = strip_tags($_POST['reg_security_code']);
		//remove any html tags and store form values in the l.h. value
		$scode = str_replace (' ','',$scode);
		//removes any spaces in l.h. value

		/*-----------------------------*/
		//* Get First Name

		$fname = strip_tags($_POST['reg_fname']);
		//remove any html tags and store form values in the l.h. value
		$fname = str_replace (' ','',$fname);
		//removes any spaces in l.h. value
		$fname = ucfirst(strtolower($fname));
		//capitalizes first letter / makes other letters lower case
		$_SESSION['reg_fname']=$fname;
		//stores first name into session variable

		/*-----------------------------*/
	    //* Get Last Name

		$lname = strip_tags($_POST['reg_lname']);
		//remove any html tags and store form values in the l.h. value
		$lname = str_replace (' ','',$lname);
		//removes any spaces in l.h. value
		$lname = ucfirst(strtolower($lname));
		//capitalizes first letter / makes other letters lower case
		$_SESSION['reg_lname']=$lname;
		//stores last name into session variable

		/*-----------------------------*/
		//* Get Telephone

		$tel = strip_tags($_POST['reg_tel']);
		//remove any html tags and store form values in the l.h. value
		$tel = str_replace (' ','',$tel);
		//removes any spaces in l.h. value
		$_SESSION['reg_tel']=$tel;
		//stores telephone into session variable

		/*-----------------------------*/
		//* Get Email

		$em = strip_tags($_POST['reg_email']);
		//remove any html tags and store form values in the l.h. value
		$em = str_replace (' ','',$em);
		//removes any spaces in l.h. value
		$em = strtolower($em);
		//capitalizes first letter / makes other letters lower case
		$_SESSION['reg_email']=$em;
		//stores email into session variable

		/*-----------------------------*/
	    //* Get Confirm Email

		$em2 = strip_tags($_POST['reg_email2']);
		//remove any html tags and store form values in the l.h. value
		$em2 = str_replace (' ','',$em2);
		//removes any spaces in l.h. value
		$em2 = strtolower($em2);
		//capitalizes first letter / makes other letters lower case
		$_SESSION['reg_email2']=$em2;
		//stores email2 into session variable

		/*-----------------------------*/
		//* Get Password

		$password = strip_tags($_POST['reg_password']);
		//remove any html tags and store form values in the l.h. value

		/*-----------------------------*/
	    //* Get Confirm Password

		$password2 = strip_tags($_POST['reg_password2']);
		//remove any html tags and store form values in the l.h. value

		/*-----------------------------*/
		//* Get Date Created

		$date = date ("Y-m-d"); 
		//Gets the current date and stores it in the l.h. value

		
		/*-----------------------------------------------------*/
		//Start of Batch Input Validation

		
		/*-----------------------------*/
		//* Security code validation

		if($scode != "1234") /*--(!) security code--*/
		{
			array_push($error_array,"<br> Security code is invalid.<br>");
		}

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

				$e_check = mysqli_query($con_mysqli, "SELECT u_email FROM Users WHERE u_email = '$em'");
				//check if email already exists in USERS table

				$num_rows = mysqli_num_rows($e_check);
				//count the number of rows returned

				if($num_rows>0)
				{
					
					array_push($error_array,"<br> Email already in use.<br>");
					//push message onto error array
				}
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

		/*-----------------------------*/
		//* password validation

		if($password!=$password2) //passwords match
		{
			array_push($error_array,"<br> Your passwords do not match.<br>");
			//push message onto error array
		}
		else
		{
			if(preg_match ('/[^A-Za-z0-9]/', $password)) //no special characters
			{
				array_push($error_array,"<br> Your password can only contain english characters or numbers.<br>");
				//push message onto error array
			}
		}

		if(strlen($password)>30 || strlen($password)<5) //password too long/short
		{
			array_push($error_array,"<br> Your password must be between 5 and 30 characters.<br>");
			//push message onto error array
		}

		//* End of Batch Input Validation

		/*-----------------------------------------------------*/
		//* System-generated user attributes

		if(empty($error_array)) 
		//registration successful
		//no errors found hence error_array is empty
		{
			
			/*-----------------------------*/
			//* Ecnrypt password and generate username

			$password=md5($password); 
			//encrypts password before sending to database

			$username = strtolower($fname . "_" . $lname);
			//generate username by concatenating first name and last name

			$check_username_query = mysqli_query($con_mysqli,"SELECT u_username FROM Users WHERE u_username='$username'");
			//check if username exists in database

			$i=0; 
			//counter var

			while(mysqli_num_rows($check_username_query)!=0)
			//if username exists, add number to username
			{
				$i++; 
				//increment
				$username = $username."_".$i;
				//add number behind user name
				$check_username_query = mysqli_query($con_mysqli, "SELECT u_username FROM Users WHERE u_username='$username'");
				//check if modified username exists in database
			}

			/*-----------------------------*/
			//* Assign Profile Pic

			$profile_pic = "assets/images/profile_pictures/uc_profile.png";
			
			//profile picture assignment
			//images are stored in the 'assets' folder


			/*-----------------------------*/
			//* Insert values into database

			$query = mysqli_query($con_mysqli, "INSERT INTO Users VALUES
				(NULL,'uc','$fname','$lname','$tel','$username','$em','$password','$date','$profile_pic','0','0','no')");
			//id column is kept as NULL / friend array begins with ','

			
			if($query==true)
			//insertion into database successful
			{
				$logger->write("SUCCESS > insertion into USERS table: ".$em);
				//send activity log

				/*-----------------------------*/
				//* Send a confirmation email to the attendee (uses PHP mailer API)


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
				$mail->addAddress($em, $fname); 
				//set recipients email and name
				$mail->isHTML(true);
				//set email as HTML email

				/*-----Compose Email-----*/

				$pw_disp=substr($password2,0,3).'*****';
				//create a partially displayed pasword string

				/* Start of html message body */

				$message=

				"<html>
					<body style=\"color: #000; font-size: 1rem; text-decoration: none; font-family: 'Arial', sans-serif; background-color: #F7F7F7;\">

						<div class=\"wrapper\" style=\"max-width: 46.875rem; margin: auto auto; padding: 0.938rem;\">

							<div id=\"content\" style=\"font-size: 1rem; padding: 1.563rem; background-color: #fff; border:none; border-radius: 0.563rem; box-shadow: 0.031rem 0.031rem #000; \">
								<div id=\"logo\">
									<center>
										<h1 style=\"margin: 0.625rem;\">

											<!--present logo-->
											<a href=\"http://localhost/present_app/landing.\" target=\"_blank\">
												<img style=\"max-height: 7.5rem;\" src=\"https://lh3.googleusercontent.com/zsbknjX7egLSyCS8jwMSJn-QUR6Ybs2GpP7FkBVslSiVm58QhGJ-0I5nSwhkueoyLLARDj7D9aS4uSkiaeVSJ6B1oqDX39S6Uyd4OZVI6YYOjAn9Jnhd1ZoP1NMk_m2jhgfYkbCc0Q=w2400\">
											</a>
										</h1>
									</center>
								</div>

								<h1 style=\"font-size: 1.5rem; color:#1D1A1A\";><center>Hi $fname, your account has been registered!</center></h1>

									<div id=\"text\" style=\"text-align:center; margin:0.313rem; padding:1.563rem;\">
										<!--event title-->
										<p style=\"color:#CD0000;\"> Unit Coordinator Account </p>
										<!--presention details-->
										<h3>Username:&nbsp$em</h3>
										<!--location-->
										<h3>Password:&nbsp$pw_disp</h3>
										<br>
										<!--link to preview-->
										<center style=\"margin-top:1.563rem;\">
											<a href=\"http://localhost/present_app/login_select.php\" target=\"_blank\" style=\"background-color: #CD0000; color: #FFF; text-decoration: none; font-size: 1rem; padding: 0.438rem 0.938rem; border-radius:1.25rem; border-color:#fff;\">
												Login to Present
											</a>
										</center>
									</div>
							</div>
						</div>
					</body>
				</html> ";

				/* End of html message body */

				$mail->Subject = 'Account Registered!';
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
					array_push($error_array,"<div class='form_alert'> ! Registration unsuccessful. Contact support.</div>");

					$logger->write("FAILURE > mail error: ".$em." ".$mail->ErrorInfo);
					//send activity log
				}
				else
				//mail delivered successfully
				{
					/*-----------------------------*/
					//* Insert 'signup success' message into error_array
					array_push($error_array,"<div class='form_alert'> ✓ Your registration is successful. A confirmation email has been sent to you.</div>");
					
					$logger->write("SUCCESS > mail delivered: ".$em);
					//send activity log
				}

				/*-----------------------------*/
				//* Clear Session Variables (once registration is successful

				$_SESSION['reg_fname']="";
				$_SESSION['reg_lname']="";
				$_SESSION['reg_tel']="";
				$_SESSION['reg_email']="";
				$_SESSION['reg_email2']="";
				//prevents previous registrants data from showing upon login refresh
				header("Refresh:5; url = 'login_select.php'");
			}
			else
			//insertion into database not successful
			{
				/*-----------------------------*/
				//* Insert 'registrations success' message into error_array

				array_push($error_array,"<div class='form_alert'> ! Registration unsuccessful. Contact support.</div>");

				$logger->write("FAILURE > insertion into USERS table: ".$em);
				//send activity log
			}
		}
		else
		//invalid input
		{
			$logger->write("FAILURE > invalid input / record already exists : ".$em);
			//send activity log
		}
	}

	$logger->close();
	//close logger
?> 