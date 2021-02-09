
<!--Presentation.php-->

<?php

/*----------'Presentation' Class Definition----------*/
class Presentation
{
	/*--------variables--------*/

	private $user; 
	//stores a username
	private $con;
	//stores a connection variable

	
	/*--------constructor--------*/
	public function __construct($con,$user)
	{
		$this->con = $con;
		//assign connection variable to $con
		$user_details_query = mysqli_query($this->con, "SELECT * FROM Users WHERE u_username='$user'");
		//fetch user details from database
		$this->user=mysqli_fetch_array($user_details_query);
		//push user details into an array and assign to $user
	}

	/*--------refresh_db()--------*/
	//Iterates through database and closes events that are past their end date

	public function refresh_db()
	{
		$date_now = date("Y-m-d"); 
		//get current date +1 day

		$event_query=mysqli_query($this->con, "SELECT * FROM Events");
		//fetch event data from db

		if(mysqli_num_rows($event_query)>0) //results exist
		{
			/*------Fetch Event Rows------*/
			while($event_row = mysqli_fetch_array($event_query))  
			//while $row contains data
			{ 
				$e_id=$event_row['e_id'];
				//fetch current event id

				if($date_now > $event_row['e_end_date'])
				//if $date_now is past event's end date
				{
					$update_query=mysqli_query($this->con, "UPDATE Events SET e_event_closed='yes' WHERE e_id=$e_id");
					//set event closed to 'yes'
				}
				else if($date_now < $event_row['e_end_date'])
				//if $date_now is not yet past event's end date
				{
					$update_query=mysqli_query($this->con, "UPDATE Events SET e_event_closed='no' WHERE e_id=$e_id");
					//set event closed to 'no'
				}
			}	
		}
	}

	/*--------load_all_presentations()--------*/
	//Iterates through database and loads all presentations to the screen

	public function load_all_presentations()
	{
		$str="";
		//string to store output
		$event_query=mysqli_query($this->con, "SELECT * FROM Events WHERE e_event_closed='no' AND e_num_presentations>'0' ORDER BY e_start_date ASC");
		//get all events that are not closed in ascending date order

		if(mysqli_num_rows($event_query)>0) 
		//if results exist
		{
			/*------Fetch Event Rows------*/
			while($event_row = mysqli_fetch_array($event_query))  //while $row contains data
			{ 
				$e_id = $event_row['e_id'];
				//fetch event id
				$e_unit_code = $event_row['e_unit_code'];
				//fetch unit code
				$e_unit_title = $event_row['e_unit_title'];
				//fetch unit title
				$e_unit_desc = $event_row['e_unit_description'];
				//fetch unit description
				$e_campus=$event_row['e_campus'];
				//fetch event campus
				$e_id=$event_row['e_id'];
				//fetch event id

				$present_query=mysqli_query($this->con, "SELECT * FROM Presentations WHERE p_event_id='$e_id' AND p_published='yes'ORDER BY p_date ASC");
				//get all presentations with above event id

				if(mysqli_num_rows($present_query)>0)
				//if the event contains published presentations / prevents showing events with unpublished presentations
				{
					/*-- Event Container (HTML) --*/
					$str.=
					"
					<div class='event_container'>

						<div class='event_header'>
							<a href=\"javascript:toggle('description_box$e_id');\">
								<h2>$e_unit_code $e_unit_title</h2>
							</a>
				    	</div> 
					    	<div class=\"event_description\" id=\"description_box$e_id\" style=\"display:none;
							padding-left:10%;padding-right:10%; padding-top:2%;padding-bottom:2%;margin-bottom:10px;
							min-height:10%;background-color:#1D1A1A;border-radius:9px;color:#FFFFFF;font-size:12px;opacity:0.98;\">

				    		<h1 style=\"font-family:'arialbd';font-size:18px; margin:0.75rem;\">
				    		About The Course
							</h1>
				    	
				    		<p style=\"font-family: 'arial'; font-size:14px; margin:0.75rem;\">
				    		$e_unit_desc 
				    		</p>
			    		</div>
				    ";
				}
				/*-- Event Container (HTML) --*/

				/*------Fetch Presentation Rows------*/
				while($present_row = mysqli_fetch_array($present_query))  //while $row contains data
				{
					$time_str = date("g:i a", strtotime($present_row['p_time'])); 
					//Comvert p_time to string eg 1:30pm
					$date_str = date('F j, Y',strtotime($present_row['p_date'])); 
					//Convert p_time to date eg January 30 2020
					$p_id = $present_row['p_id'];
					//Fetch presentation id
					$p_title = $present_row['p_title'];
					//Fetch presentation title

					
					/*-- Presentation Container (HTML) --*/
					$str.=
					"
					<div class='presentation_container'>
						<div class='presentation_info'>
							<a href='$p_id'>
								<h3>$p_title</h3>
							</a>

							<h4>$date_str, &nbsp|&nbsp $time_str, &nbsp|&nbsp $e_campus</h4>
						</div>
						<hr>
					</div>
					
					";
					/*-- Presentation Container (HTML) --*/

				}
				//$str.="</div>"; //*strange error here
				//close off event container
			}
		}
		echo $str;
		//return html code to calling program
	}

	/*--------load_presentations_uc()--------*/
	//Iterates through database and loads all presentations related to a particular UC

	public function load_presentations_uc()
	{
		$this->load_new_events_top();
		//loads events with no presentations at the top

		//https://stackoverflow.com/questions/19323010/execute-php-function-with-onclick
		$str="";
		//string to store output
		$uc_id = $this->user['u_id'];
		//fetch user id

		$event_query=mysqli_query($this->con, "SELECT * FROM Events WHERE e_num_presentations>'0' AND e_uc_id='$uc_id' ORDER BY e_start_date ASC");
		//get all events that are not closed and contain this UC id in ascending date order	

		if(mysqli_num_rows($event_query)>0) 
		//if results exist
		{
			/*------Fetch Event Rows------*/
			while($event_row = mysqli_fetch_array($event_query))  //while $row contains data
			{ 
				$e_id = $event_row['e_id'];
				//fetch event id
				$e_unit_code = $event_row['e_unit_code'];
				//fetch unit code
				$e_unit_title = $event_row['e_unit_title'];
				//fetch unit title
				$e_unit_desc = $event_row['e_unit_description'];
				//fetch unit description
				$e_teaching_period = $event_row['e_teaching_period'];
				//fetch teaching period

				$str.=
				//*-- START Event Container (HTML) --*/
				"
				<div class=\"event_container\">
					<div class=\"event_header\" title=\"Event Details\">
						<a href=\"javascript:toggle('description_box$e_id');\">
							<h2>$e_unit_code $e_unit_title</h2>
						</a>			
						<button  class=\"em_button\" onclick=\"location.href='new_presentation_form.php?e_id=$e_id'\" title=\"Add Presentation\">
			         		+ Add Presentation
			    		</button>

						<button  class=\"em_button\" onclick=\"location.href='edit_event_form.php?e_id=$e_id'\" title=\"Edit Event\">
			         		Edit Event
			    		</button>
				    </div>
			    	<div class=\"event_description\" id=\"description_box$e_id\" style=\"display:none;
						padding-left:10%;padding-right:10%; padding-top:2%;padding-bottom:2%;margin-bottom:10px;
						min-height:10%;background-color:#1D1A1A;border-radius:9px;color:#FFFFFF;font-size:12px;opacity:0.98;\">

				    	<h1 style=\"font-family:'arialbd';font-size:18px; margin:0.75rem;\">About The Course</h1>
				    	<p style=\"font-family: 'arial'; font-size:14px; margin:0.75rem;\">$e_unit_desc</p>
			    	</div>
				";
				/*-- END Event Container (HTML) --*/

				$e_campus=$event_row['e_campus'];
				//fetch event campus
				$present_query=mysqli_query($this->con, "SELECT * FROM Presentations WHERE p_event_id='$e_id' ORDER BY p_date ASC");
				//get all presentations with above event id

				/*------Fetch Presentation Rows------*/
				while($present_row = mysqli_fetch_array($present_query))  //while $row contains data
				{
					$time_str = date("g:i a", strtotime($present_row['p_time'])); 
					//Comvert p_time to string eg 1:30pm
					$date_str = date('F j, Y',strtotime($present_row['p_date'])); 
					//Convert p_time to date eg January 30 2020
					$p_id = $present_row['p_id'];
					//Fetch presentation id
					$p_title = $present_row['p_title'];
					//Fetch presentation title
					$p_mailer_sent = $present_row['p_mailer_sent'];
					//Fetch mailer_sent info
					$p_published = $present_row['p_published'];
					//Fetch publication info
					$p_date_published = $present_row['p_date_published'];
					//Fetch date of publication info
					$p_rep_email = $present_row['p_rep_email'];
					//Fetch rep email
					$p_num_signedup = $present_row['p_num_signedup'];
					//Fetch num of participants signed up
					$p_num_responses = $present_row['p_num_responses'];
					//Fetch num of participants signed up


					if($p_published=="yes")
				    // presentation published
					{
						if($p_mailer_sent=="yes")
						// feedback request mailer sent
						{
							$str.=
							/*-- START Presentation Container - published / mailer sent (HTML) --*/
							"
								<div class=\"presentation_container\">
									<div class=\"presentation_info\" title=\"Presentation Details\">
										<a href=\"$p_id\">
											<h3>$p_title 📨</h3>
										</a>
										<h4>$date_str, &nbsp|&nbsp $time_str, &nbsp|&nbsp $e_campus</h4>
									</div>
									<hr>
									<div class=\"presentation_stats\" style=\"background-color:#DFF4D9;\">
										<h4 style=\"color:green;\" title=\"Publication Status\">Published $p_date_published</h4>
										<a href=\"mailto: $p_rep_email?subject=$e_unit_code $e_teaching_period Presentation\"title=\"Email Representative\">
											<img src=\"assets/images/icons/speaker.png\"> Contact Rep
										</a>
										<a href=\"edit_presentation_form.php?p_id=$p_id\" title=\"Edit Presentation\">
											<img src=\"assets/images/icons/settings.png\">Edit Presentation
										</a>
										<a href=\"export_feedback.php?p_id=$p_id\" style=\"color:blue;\" title=\"Feedback\">
											<img src=\"assets/images/icons/broadcast.png\">View Responses
										</a>
										<div style=\"float:right; margin-top:4px; margin-right: 15px;\" title=\"Responses\">
											<img src=\"assets/images/icons/assessment.png\"> $p_num_responses
										</div>
										<div style=\"float:right; margin-top:4px; margin-right: 15px;\" title=\"Particpants\">
											<img src=\"assets/images/icons/audience.png\"> $p_num_signedup
										</div>

									</div>

								</div>
							";
							/*-- START Presentation Container - published / mailer sent (HTML) --*/
						}
						else if ($p_mailer_sent=="no")
						// feedback request mailer not sent
						{
							$str.=
							/*-- START Presentation Container - published / no mailer (HTML) --*/
							"
							<div class=\"presentation_container\">
								<div class=\"presentation_info\" title=\"Presentation Details\">
									<a href=\"$p_id\">
										<h3>$p_title</h3>
									</a>
									<h4>$date_str, &nbsp|&nbsp $time_str, &nbsp|&nbsp $e_campus</h4>
								</div>
								<hr>
								<div class= \"presentation_stats\" style=\"background-color:#DFF4D9;\">
									<h4 style=\"color:green;\" title=\"Publication Status\">Published $p_date_published</h4>
									<a href=\"mailto: $p_rep_email?subject=$e_unit_code $e_teaching_period Presentation\"title=\"Email Representative\">
										<img src=\"assets/images/icons/speaker.png\">Contact Rep
									</a>
									<a href=\"edit_presentation_form.php?p_id=$p_id\" title=\"Edit Presentation\">
										<img src=\"assets/images/icons/settings.png\">Edit Presentation
									</a>
									<a href=\"#\" onclick=\"toggle('pending');\" title=\"Feedback\">
										<img src=\"assets/images/icons/broadcast.png\">Pending
									</a>
									<div style=\"float:right; margin-top:4px; margin-right: 15px;\" title=\"Particpants\">
										<img src=\"assets/images/icons/audience.png\"> $p_num_signedup
									</div>
								</div>
							</div>

							";
							/*-- END Presentation Container - published / no mailer (HTML) --*/
						}
					}
					else if ($p_published=="no")
					// presentation not published
					{
						$str.=
						/*-- START Presentation Container - not published (HTML) --*/
						"	
						<div class=\"presentation_container\">
							<div class=\"presentation_info\" title=\"Presentation Details\">
								<a href=\"$p_id\">
									<h3>$p_title</h3>
								</a>
								<h4>$date_str, &nbsp|&nbsp $time_str, &nbsp|&nbsp $e_campus</h4>
							</div>
						    <hr>
							<div class= \"presentation_stats\" style=\"background-color:#FFE5D6;\">
								<h4 style=\"color:red;\" title=\"Publication Status\">Upload Pending &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</h4>
								<a href=\"mailto: $p_rep_email?subject=$e_unit_code $e_teaching_period Presentation\"title=\"Email Representative\">
									<img src=\"assets/images/icons/speaker.png\">Contact Rep
								</a>
								<a href=\"edit_presentation_form.php?p_id=$p_id\" title=\"Edit Presentation\">
									<img src=\"assets/images/icons/settings.png\">Edit Presentation
								</a>
							</div>
						</div>

						";
						/*-- END Presentation Container - not published (HTML) --*/
					}

				}//END while

				$str.="</div>";
				//close off event container

			}//END while

		}//END if

	 	echo $str;
		//return html code to calling program
	}

	public function load_new_events_top()
	{
		//https://stackoverflow.com/questions/19323010/execute-php-function-with-onclick
		$str="";
		//string to store output
		$uc_id = $this->user['u_id'];
		//fetch user id

		$event_query=mysqli_query($this->con, "SELECT * FROM Events WHERE e_event_closed='no' AND e_num_presentations='0' AND e_uc_id='$uc_id' ORDER BY e_start_date ASC");
		//get all events that are not closed and contain this UC id in ascending date order	

		if(mysqli_num_rows($event_query)>0) 
		//if results exist
		{
			/*------Fetch Event Rows------*/
			while($event_row = mysqli_fetch_array($event_query))  //while $row contains data
			{ 
				$e_id = $event_row['e_id'];
				//fetch event id
				$e_unit_code = $event_row['e_unit_code'];
				//fetch unit code
				$e_unit_title = $event_row['e_unit_title'];
				//fetch unit title
				$e_unit_desc = $event_row['e_unit_description'];
				//fetch unit description
				$e_teaching_period = $event_row['e_teaching_period'];
				//fetch teaching period

				$str.=
				//*-- START Event Container (HTML) --*/
				"
				<div class=\"event_container\">
					<div class=\"event_header\" style=\"background-color:#EE0000;\" title=\"Event Details\">
						<a href=\"javascript:toggle('description_box$e_id');\">
							<h2>$e_unit_code $e_unit_title  &nbsp(New)&nbsp</h2>
						</a>			
						<button  class=\"em_button\" onclick=\"location.href='new_presentation_form.php?e_id=$e_id'\" title=\"Add Presentation\">
			         		+ Add Presentation
			    		</button>

						<button  class=\"em_button\" onclick=\"location.href='edit_event_form.php?e_id=$e_id'\" title=\"Edit Event\">
			         		Edit Event
			    		</button>
				    </div>
			    	<div class=\"event_description\" id=\"description_box$e_id\" style=\"display:none;
						padding-left:10%;padding-right:10%; padding-top:2%;padding-bottom:2%;margin-bottom:10px;
						min-height:10%;background-color:#1D1A1A;border-radius:9px;color:#FFFFFF;font-size:12px;opacity:0.98;\">

				    	<h1 style=\"font-family:'arialbd';font-size:18px; margin:0.75rem;\">About The Course</h1>
				    	<p style=\"font-family: 'arial'; font-size:14px; margin:0.75rem;\">$e_unit_desc</p>
			    	</div>
			    </div>
				";
				/*-- END Event Container (HTML) --*/
			}
		}//END if
		echo $str;
		//return html code to calling program
	}

	public function load_new_uc_page()
	{
		$str=
		"
				<div class=\"msg_box\">
					<center>
						<h4>You don't have any events!</h4>
						<h4>Click 'New Event' to begin!</h4>
						<br><br><br>
						 <button  class=\"begin_button_uc\" onclick=\"location.href='new_event_form.php'\" type=\"button\">
				         		+ New Event 
				    	</button>
				    </center>
				</div>
		";

		echo $str;
	}

	public function load_new_rep_page($presentation)
	{

		$str=
		"
			<div class=\"msg_box\">
				<center>
					<h4>You have 1 new presentation!</h4>
					<h4>Click 'upload' to add details!</h4>
					<br>
					<h2>$presentation</h2>
					<br><br>
					 <button  class=\"begin_button_rep\" onclick=\"location.href='upload_presentation_form.php'\" type=\"button\">
			         		Upload
			    	</button>
			    </center>
			</div>
		";

		echo $str;

	}

	public function showPresentationInfo($e_id, $rep_id)
	{
		
		$event_query=mysqli_query($this->con, "SELECT * FROM Events WHERE e_id='$e_id'");
		//get event data	

		/*------Fetch Event Rows------*/
		$event_row = mysqli_fetch_array($event_query);  

		$e_id = $event_row['e_id'];
		//fetch event id
		$e_unit_code = $event_row['e_unit_code'];
		//fetch unit code
		$e_unit_title = $event_row['e_unit_title'];
		//fetch unit title
		$e_unit_desc = $event_row['e_unit_description'];
		//fetch unit description
		$e_teaching_period = $event_row['e_teaching_period'];
		//fetch teaching period
		$e_campus = $event_row['e_campus'];
		//fetch teaching period

		$str=

		"<div class=\"event_container\">
			<div class=\"event_header\" title=\"Event Details\">
				<a href=\"javascript:toggle('description_box$e_id');\">
					<h2>$e_unit_code $e_unit_title</h2>
				</a>			
		    </div>
	    	<div class=\"event_description\" id=\"description_box$e_id\" style=\"display:none;
				padding-left:10%;padding-right:10%; padding-top:2%;padding-bottom:2%;margin-bottom:10px;
				min-height:10%;background-color:#1D1A1A;border-radius:9px;color:#FFFFFF;font-size:12px;opacity:0.98;\">

		    	<h1 style=\"font-family:'arialbd';font-size:18px; margin:0.75rem;\">About The Course</h1>
		    	<p style=\"font-family: 'arial'; font-size:14px; margin:0.75rem;\">$e_unit_desc</p>
	    	</div>
		";


		$present_query=mysqli_query($this->con, "SELECT * FROM Presentations WHERE p_rep_id='$rep_id'");
		//get all presentations with above rep id

		/*------Fetch Presentation Rows------*/
		$present_row = mysqli_fetch_array($present_query);

		$time_str = date("g:i a", strtotime($present_row['p_time'])); 
		//Comvert p_time to string eg 1:30pm
		$date_str = date('F j, Y',strtotime($present_row['p_date'])); 
		//Convert p_time to date eg January 30 2020
		$p_id = $present_row['p_id'];
		//Fetch presentation id
		$p_title = $present_row['p_title'];
		//Fetch presentation title
		$p_mailer_sent = $present_row['p_mailer_sent'];
		//Fetch mailer_sent info
		$p_published = $present_row['p_published'];
		//Fetch publication info
		$p_date_published = $present_row['p_date_published'];
		//Fetch date of publication info
		$p_rep_email = $present_row['p_rep_email'];
		//Fetch rep email
		$p_num_signedup = $present_row['p_num_signedup'];
		//Fetch num of participants signed up
		$p_num_responses = $present_row['p_num_responses'];
		//Fetch num of participants signed up

		if($p_published=="no")
		{
			$str.=
			/*-- START Presentation Container - not published (HTML) --*/
			"	
				<div class=\"presentation_container\">
					<div class=\"presentation_info\" title=\"Presentation Details\">
						<a href=\"$p_id\">
							<h3>$p_title</h3>
						</a>
						<h4>$date_str, &nbsp|&nbsp $time_str, &nbsp|&nbsp $e_campus</h4>
					</div>
					<hr>
					<div class= \"presentation_stats\" style=\"background-color:#FFE5D6;\">
						<h4 style=\"color:red;\" title=\"Publication Status\">Upload Pending &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</h4>
					</div>
				</div>
			</div>
			";
		}
		else if($p_published=="yes")
		{
			$str.=
			/*-- START Presentation Container - published --*/
			"
			<div class=\"presentation_container\">
				<div class=\"presentation_info\" title=\"Presentation Details\">
					<a href=\"$p_id\">
						<h3>$p_title</h3>
					</a>
					<h4>$date_str, &nbsp|&nbsp $time_str, &nbsp|&nbsp $e_campus</h4>
				</div>
				<hr>
				<div class= \"presentation_stats\" style=\"background-color:#DFF4D9;\">
					<h4 style=\"color:green;\" title=\"Publication Status\">Published $p_date_published</h4>
					<a href=\"edit_presentation_form.php?p_id=$p_id\" title=\"Edit Presentation\">
						<img src=\"assets/images/icons/settings.png\">Edit Presentation
					</a>
					<div style=\"float:right; margin-top:4px; margin-right: 15px;\" title=\"Particpants\">
						<img src=\"assets/images/icons/audience.png\"> $p_num_signedup
					</div>
				</div>
			</div>
			";
		}

		echo $str;
	}

	//Bella 
    public function showPresentationInfoFeedback($e_id, $rep_id)
	{
		
		$event_query=mysqli_query($this->con, "SELECT * FROM Events WHERE e_id='$e_id'");
		//get event data	

		/*------Fetch Event Rows------*/
		$event_row = mysqli_fetch_array($event_query);  

		$e_id = $event_row['e_id'];
		//fetch event id
		$e_unit_code = $event_row['e_unit_code'];
		//fetch unit code
		$e_unit_title = $event_row['e_unit_title'];
		//fetch unit title
		$e_unit_desc = $event_row['e_unit_description'];
		//fetch unit description
		$e_teaching_period = $event_row['e_teaching_period'];
		//fetch teaching period
		$e_campus = $event_row['e_campus'];
		//fetch teaching period

		$str=

		"<div class=\"event_container\">
			<div class=\"event_header\" title=\"Event Details\">
				<a href=\"javascript:toggle('description_box$e_id');\">
					<h2>$e_unit_code $e_unit_title</h2>
				</a>			
		    </div>
	    	<div class=\"event_description\" id=\"description_box$e_id\" style=\"display:none;
				padding-left:10%;padding-right:10%; padding-top:2%;padding-bottom:2%;margin-bottom:10px;
				min-height:10%;background-color:#1D1A1A;border-radius:9px;color:#FFFFFF;font-size:12px;opacity:0.98;\">

		    	<h1 style=\"font-family:'arialbd';font-size:18px; margin:0.75rem;\">About The Course</h1>
		    	<p style=\"font-family: 'arial'; font-size:14px; margin:0.75rem;\">$e_unit_desc</p>
	    	</div>
		";


		$present_query=mysqli_query($this->con, "SELECT * FROM Presentations WHERE p_rep_id='$rep_id'");
		//get all presentations with above rep id

		/*------Fetch Presentation Rows------*/
		$present_row = mysqli_fetch_array($present_query);

		$time_str = date("g:i a", strtotime($present_row['p_time'])); 
		//Comvert p_time to string eg 1:30pm
		$date_str = date('F j, Y',strtotime($present_row['p_date'])); 
		//Convert p_time to date eg January 30 2020
		$p_id = $present_row['p_id'];
		//Fetch presentation id
		$p_title = $present_row['p_title'];
		//Fetch presentation title
		$p_mailer_sent = $present_row['p_mailer_sent'];
		//Fetch mailer_sent info
		$p_published = $present_row['p_published'];
		//Fetch publication info
		$p_date_published = $present_row['p_date_published'];
		//Fetch date of publication info
		$p_rep_email = $present_row['p_rep_email'];
		//Fetch rep email
		$p_num_signedup = $present_row['p_num_signedup'];
		//Fetch num of participants signed up
		$p_num_responses = $present_row['p_num_responses'];
		//Fetch num of participants signed up

		if($p_published=="no")
		{
			$str.=
			/*-- START Presentation Container - not published (HTML) --*/
			"	
				<div class=\"presentation_container\">
					<div class=\"presentation_info\" title=\"Presentation Details\">
						<a href=\"$p_id\">
							<h3>$p_title</h3>
						</a>
						<h4>$date_str, &nbsp|&nbsp $time_str, &nbsp|&nbsp $e_campus</h4>
					</div>
					<hr>
					<div class= \"presentation_stats\" style=\"background-color:#FFE5D6;\">
						<h4 style=\"color:red;\" title=\"Publication Status\">Upload Pending &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</h4>
					</div>
				</div>
			</div>
			";
		}
		else if($p_published=="yes")
		{
			$str.=
			/*-- START Presentation Container - published --*/
			"
			<div class=\"presentation_container\">
				<div class=\"presentation_info\" title=\"Presentation Details\">
					<a href=\"$p_id\">
						<h3>$p_title</h3>
					</a>
					<h4>$date_str, &nbsp|&nbsp $time_str, &nbsp|&nbsp $e_campus</h4>
				</div>
				<hr>
				<div class= \"presentation_stats\" style=\"background-color:#DFF4D9;\">
					<h4 style=\"color:green;\" title=\"Publication Status\">Published $p_date_published</h4>
					<a href=\"javascript:toggle('event_form_container');\" title=\"Edit Presentation\">
						<img src=\"assets/images/icons/settings.png\">Edit Presentation
					</a>
					<div style=\"float:right; margin-top:4px; margin-right: 15px;\" title=\"Particpants\">
						<img src=\"assets/images/icons/audience.png\"> $p_num_signedup
					</div>
				</div>
			</div>
			";
		}

		echo $str;
	}

	
}
/*----------'Presentation' Class Definition----------*/

?>