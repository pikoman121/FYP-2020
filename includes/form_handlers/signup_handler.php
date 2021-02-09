<!--signup_handler.php-->

<!-------------------------signup_handler(Public)------------------------->

<?php
	
	require(dirname(__DIR__).'\email_handlers\PHPMailer\PHPMailerAutoload.php');
	require(dirname(__DIR__).'\email_handlers\credentials.php');
	//Require data from crediantial.php
	require(dirname(__DIR__).'\classes\Logger.php');
	//Require Logger class

	/*-----------------------------------------------------*/
	//start logger

	$logger = new Logger();
	//initialises a new logger object

	/*-----------------------------------------------------*/
	//Declare variables (for input validation)

	$p_id = "";
	$fname = ""; 		//first name
	$lname = ""; 		//last name
	$affiliation = ""; 	//affilitation
	$position = ""; 	//position
	$email = ""; 		//email (required)
	$date ="";			//signup date
	$error_array = array();	//holds error messages in an array

	/*-----------------------------------------------------*/
	//Upon clicking the register button get Input Values for validation 

	if (isset($_POST['signup_button'])) 
	//if register_button variable is not NULL
	{
		/*-----------------------------*/
		//* Get presentation id

		$p_id=$_POST['signup_pid'];
		//fetch presentation id

		$logger->write("STARTED > signup_handler.php for p_id: ".$p_id);
		//send activity log

		/*-----------------------------*/
		//* Get First Name

		$fname = strip_tags($_POST['signup_fname']);
		//remove any html tags and store form values in the l.h. value
		$fname = str_replace (' ','',$fname);
		//removes any spaces in l.h. value
		$fname = ucfirst(strtolower($fname));
		//capitalizes first letter / makes other letters lower case
		$_SESSION['signup_fname']=$fname;
		//stores first name into session variable

		/*-----------------------------*/
	    //* Get Last Name

		$lname = strip_tags($_POST['signup_lname']);
		//remove any html tags and store form values in the l.h. value
		$lname = str_replace (' ','',$lname);
		//removes any spaces in l.h. value
		$lname = ucfirst(strtolower($lname));
		//capitalizes first letter / makes other letters lower case
		$_SESSION['signup_lname']=$lname;
		//stores last name into session variable

		/*-----------------------------*/
		//* Get Affiliation

		$affiliation = strip_tags($_POST['signup_affiliation']);
		//remove any html tags and store form values in the l.h. value
		$_SESSION['signup_affiliation']=$affiliation;
		//stores affiliation into session variable

		/*-----------------------------*/
		//* Get Position

		$position = strip_tags($_POST['signup_position']);
		//remove any html tags and store form values in the l.h. value
		$_SESSION['signup_position']=$position;
		//stores position into session variable


		/*-----------------------------*/
	    //* Get Email

		$email = strip_tags($_POST['signup_email']);
		//remove any html tags and store form values in the l.h. value
		$email = str_replace (' ','',$email);
		//removes any spaces in l.h. value
		$email = strtolower($email);
		//makes letters lower case
		$_SESSION['signup_email']=$email;
		//stores email into session variable

		/*-----------------------------*/
		//* Get Signup Date 

		$date = date ("Y-m-d"); 
		//Gets the current date and stores it in the l.h. value

		
		/*-----------------------------------------------------*/
		//Start of Batch Input Validation


		/*-----------------------------*/
		//* Email Validation

		if(filter_var($email, FILTER_VALIDATE_EMAIL))
		//checks if email is in valid format (.com, .co.uk , etc.)
		{

			$email = filter_var($email, FILTER_VALIDATE_EMAIL);
			//assign validated email to l.h. value

			/* check if signup data already exists */
			$e_check = mysqli_query($con_mysqli, "SELECT a_email FROM Attendees WHERE a_email = '$email' AND a_presentation_id='$p_id'");
			//check if email already exists in ATTENDEES table


			$num_rows = mysqli_num_rows($e_check);
			//count the number of rows returned

			//* check for duplicate record
			if($num_rows>0)
			{
				
				array_push($error_array,"<div class='form_alert'> You are already signed up!</div>");
				//push message onto error array

				$_SESSION['signup_fname']="";
				$_SESSION['signup_lname']="";
				$_SESSION['signup_affiliation']="";
				$_SESSION['signup_position']="";
				$_SESSION['signup_email']="";
			}
			//IF rows more than 0, print error message
		}
		else
		{
			array_push($error_array,"<br> Invalid email format.<br>");
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

		//* End of Batch Input Validation

		/*-----------------------------------------------------*/
		//* insert data into database

		if(empty($error_array)) 
		//signup successful - no errors found hence error_array is empty
		{
			/*-----------------------------*/
			//* Insert values into database

			$query = mysqli_query($con_mysqli, "INSERT INTO Attendees VALUES
				(NULL,'$fname','$lname','$affiliation','$position','$email','$date','$p_id','no')");
			//insert data into database - id column is kept as NULL 
			
			if($query==true)
			//if insertion into database was successful, send email
			{
				$logger->write("SUCCESS > insertion into ATTENDEES table: ".$email);
				//send activity log

				/*-----------------------------*/
				//* Update Presentation Stats

				$get_num = mysqli_query($con_mysqli, "SELECT p_num_signedup FROM Presentations WHERE p_id='$p_id'");
				//query data base for attendee count
				$row = mysqli_fetch_array($get_num);
				//push results into an array and assign to l.h. variable
				$num_signedup= $row['p_num_signedup'];
				//assign likes to l.h. variable
				$num_signedup ++;
				//increment value
				$query = mysqli_query($con_mysqli, "UPDATE Presentations SET p_num_signedup = '$num_signedup' WHERE p_id='$p_id'");
				//update no. of attendees for the presentation

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
				$mail->addAddress($email, $fname); 
				//set recipients email and name
				$mail->isHTML(true);
				//set email as HTML email

				/*-----Compose Email-----*/


				//declare all variables
	
				$present_row = "";
				$p_title = "";
				$p_details = "";
				$p_time = ""; 
				$p_date = ""; 
				$p_video = "";
				$p_link = "";
				$p_room="";
				$e_id="";
				$event_query="";
				$event_row = "";
				$e_header="";
				$e_description= "";
				$e_campus= "";
				$e_uc_name="";
				$e_uc_telephone="";

				/* Fetch data for message body */

				$present_query=mysqli_query($con_mysqli, "SELECT * FROM Presentations WHERE p_id='$p_id'");
				//get presentation by p_id

				//fetch presentation information
				$present_row = mysqli_fetch_array($present_query);
				//push results into an array
				$p_title = $present_row['p_title'];
				//fetch data and construct title string
				$p_details = $present_row['p_details'];
				//fetch data and construct details string
				$p_time = date("g:i a", strtotime($present_row['p_time'])); 
				//Convert p_time to string eg 1:30pm
				$p_date = date('F j, Y',strtotime($present_row['p_date'])); 
				//Convert p_time to date eg January 30 2020
				$p_video = $present_row['p_video'];
				//fetch data and construct video path
				$p_link = "http://localhost/present_app/".$present_row['p_id'];
				//fetch data and construct video path
				$p_room = $present_row['p_room'];
				//fetch presentation room

				//fetch event information
				$e_id=$present_row['p_event_id'];
				//fetch event id
				$event_query=mysqli_query($con_mysqli, "SELECT * FROM Events WHERE e_id='$e_id'");
				//get event by e_id
				$event_row = mysqli_fetch_array($event_query);
				//push results into an array
				$e_header=$event_row['e_unit_code']." ".$event_row['e_unit_title'];
				//fetch data and construct event header string
				$e_description= $event_row['e_unit_description'];
				//fetch data and construct event description string
				$e_campus= $event_row['e_campus'];
				//fetch data and construct event description string
				$e_uc_name=$event_row['e_uc_name'];
				//fetch uc name
				$e_uc_telephone=$event_row['e_uc_telephone'];
				//fetch uc telephone
				

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

								<h1 style=\"font-size: 1.5rem; color:#1D1A1A\";><center>Hi $fname, your registration is confirmed!</center></h1>

									<div id=\"text\" style=\"text-align:center; margin:0.313rem; padding:1.563rem;\">
										<!--event title-->
										<p style=\"color:#CD0000;\"> $e_header </p>
										<!--presention title-->
										<h1 style=\"padding:0.313rem;\"> $p_title </h1>
										<hr>
										<!--presention details-->
										<p>$p_date &nbsp|&nbsp $p_time &nbsp|&nbsp $e_campus</p>
										<!--location-->
										<h2>$p_room</h2>
										<!--in-charge / contact-->
										<p>Organiser:&nbsp$e_uc_name &nbsp|&nbsp Tel:&nbsp$e_uc_telephone</p>
										<!--message-->
										<p><center>See you there, mate!</center></p>
										<!--link to preview-->
										<center style=\"margin-top:1.563rem;\">
											<a href=\"$p_link\" target=\"_blank\" style=\"background-color: #CD0000; color: #FFF; text-decoration: none; font-size: 1rem; padding: 0.438rem 0.938rem; border-radius:1.25rem; border-color:#fff;\">
												Preview
											</a>
										</center>
									</div>
							</div>
						</div>
					</body>
				</html> "; 

				/* End of html message body */


				$mail->Subject = 'Your Registration is Confirmed!';
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
					array_push($error_array,"<div class='form_alert'> Registration unsuccessful. Contact support.</div>");

					$logger->write("FAILURE > mail error: ".$email." ".$mail->ErrorInfo);
					//send activity log
				}
				else
				//mail delivered successfully
				{
					/*-----------------------------*/
					//* Insert 'signup success' message into error_array
					array_push($error_array,"<div class='form_alert'> Your registration is successful. A confirmation email has been sent to you.</div>");
					
					$logger->write("SUCCESS > mail delivered: ".$email);
					//send activity log
				}

				/*-----------------------------*/
				//* Clear Session Variables (once registration is successful)

				$_SESSION['signup_fname']="";
				$_SESSION['signup_lname']="";
				$_SESSION['signup_affiliation']="";
				$_SESSION['signup_position']="";
				$_SESSION['signup_email']="";

				//prevents previous registrants data from showing up on next page
			}
			else
			{
				//* Insert 'signup failure' message into error_array
				array_push($error_array,"<div class='form_alert'> Registration unsuccessful. Contact support.</div>");

				$logger->write("FAILURE > insertion into ATTENDEES table: ".$email);
				//send activity log
			}
		}
		else
		{
			$logger->write("FAILURE > invalid input / record already exists : ".$email);
			//send activity log
		}
	}

	$logger->close();
	//close logger

?> 