<!--feedback_form.php-->

<!----------------------------------------------------->
<!--http://localhost:8080/present_app/feedback_form.php -->
<!--http://localhost:8080/phpmyadmin/--> 
<!----------------------------------------------------->

<!--------- Include Files Section --------->
<?php 

	include("info_panels.php");
	//includes file to display header and sidebar
	include("includes/classes/Assessment.php");
	//include the Presentation class
	require('includes/form_handlers/feedback_form_handler.php');
	//Require feedback form handler

	//require('/opt/lampp/htdocs/present_app/includes/form_handlers/test_handler.php');
	//Require feedback form handler

	//--------------------------------

	$p_id="";
	$a_id="";
	$ass_id="";

	
	if(isset($_GET['a_id']))
	{
		$a_id=$_GET['a_id'];
		//fetch attendee id

		$check_query=mysqli_query($con_mysqli, "SELECT * FROM Attendees WHERE a_id='$a_id' AND a_feedback='yes'");
		//check if attendee already submitted form 

		if(mysqli_num_rows($check_query)>0)
		{
			$_SESSION['user_alert']=array();
			//create an array to store user alerts in the session variable
			array_push($_SESSION['user_alert'],"<div class='form_alert'>You have already submitted feedback!</div>");
			//* push alert message
			header("Location: index_main.php");
			//redirect the user to main page
			exit();
			//close script
		}
	}

	if(isset($_GET['p_id']))
	{
		$p_id=$_GET['p_id'];
		//fetch presentation id
		$form_action_str="feedback_form.php?p_id=".$p_id."&a_id=".$a_id;
		//get form action string
		$present_query=mysqli_query($con_mysqli, "SELECT * FROM Presentations WHERE p_id='$p_id'"); //ok
		//fetch presentation 	
		$present_row=mysqli_fetch_array($present_query); //ok
		//push data into an array
		$ass_id= $present_row['p_assessment_id'];
		//fetch rep id
		$p_title = $present_row['p_title'];
		//fetch data and construct title string
		$p_time = date("g:i a", strtotime($present_row['p_time'])); 
		//Convert p_time to string eg 1:30pm
		$p_date = date('F j, Y',strtotime($present_row['p_date'])); 
		//Convert p_time to date eg January 30 2020

		//--------------------------------

		//fetch event information
		$e_id=$present_row['p_event_id'];
		//fetch event id
		$event_query=mysqli_query($con_mysqli, "SELECT * FROM Events WHERE e_id='$e_id'");
		//get event by e_id
		$event_row = mysqli_fetch_array($event_query);
		//push results into an array
		$e_campus= $event_row['e_campus'];
		//fetch data and construct event description string
	}


?>
<!--------- Javascript Functions --------->
<script type="text/javascript">
	
function toggle(section) 
{
  var x = document.getElementById(section);
  if (x.style.display === "none") 
  {
    x.style.display = "block";
  } 
  else 
  {
    x.style.display = "none";
  }
 
}

</script>

<div class="index_main">

	<img src="assets/images/backgrounds/cinema_chairs.jpg">

	<!--------- Page Title --------->
	<div class="page_title">	
		Participant Feedback
		<button  class="help_button" onclick="toggle('help_box')" type="button">
	         		?
	    </button>
	</div>

	<!--------- Help Box --------->
    <div class="help_box" id="help_box">
    	<h1>Feedback</h1><br>
    	<p>
    		If you're like many people, you've had skydive on your bucket list for as long as you can remember. It probably sits right up there at the top (somewhere around "ride an elephant through the jungle" and "throw an opening pitch at Wrigley Field.") You know how incredible it'll be, but you're also pretty sure that once will be enough.
    	</p>
    	<br>
		<center>
	    	<a href="mailto:planetofthegrapes.ft01@gmail.com?subject=Present Customer Support"title="Contact Support">
				Contact Support
			</a>
		</center>
    </div>

    <!--Display 'form alerts'-->


	<!--form contents-->
	
	<div class="listing_container">

		<!--------- Event Module --------->
		<div class="event_container">
			
			<div class="event_header">
				<center><h2>We value your feedback!</h2></center>				
		    </div>

			<!-- P-Module -->
			<div class="presentation_container">

				<div class= "event_form_container">

					<form id="feedback" action="<?php echo $form_action_str;?>" method="POST">

						<h4>You are providing feedback for the following presentation.</h4>
						<h3 style="font-size:24px;"><?php echo $p_title; ?></h3>
						<h3><?php echo $p_date.",  ".$p_time.",  ".$e_campus; ?></h3>
						<br><hr><br>
						
						<!-----------------question modules----------------->

						<?php

							$ass_obj = new Ass($con_mysqli);
							//create new assessment object
							$ass_obj->display_form($ass_id);
							//print assessment form
						?>
						
						<!-----------------question modules----------------->

						<h3>Click 'Submit' to save your feedback.</h3>
						<h4>Thanks for coming. We hope to see you again soon.</h4>

						<!-- *** stores p_id passes p_id to handler *** -->
						<input type="text" style="display:none;" name="p_id" value="<?php echo $p_id;?>">
						<!-- *** stores a_id passes a_id to handler *** -->
						<input type="text" style="display:none;" name="a_id" value="<?php echo $a_id;?>">
						<!-- *** stores ass_id passes ass_id to handler *** -->
						<input type="text" style="display:none;" name="ass_id" value="<?php echo $ass_id;?>">
						
						<!--Submit Button-->
						<input type="submit" style="height:6%;" name="submit_feedback_button" value="Submit Feedback">
						<br>
					</form>
				</div>
			</div>
			<!-- P-Module -->
		</div>
		<!--------- End of Event Module -------->
	</div>
</div>
