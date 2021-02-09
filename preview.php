<!--preview.php-->

<!----------------------------------------------------->
<!--http://localhost:8080/present_app/preview.php -->
<!--http://localhost:8080/phpmyadmin/--> 
<!----------------------------------------------------->

<?php 

	include("info_panels.php");
	//includes file to display header and sidebar

	require ('includes/form_handlers/signup_handler.php');
	//fetches signup_handler.php (contains $error_array)

	/*---Get Presentation Data from DB (php)---*/

	//declare all variables
	$p_id="";
	$present_row = "";
	$p_title = "";
	$p_details = "";
	$p_time = ""; 
	$p_date = ""; 
	$p_video = "";
	$p_link = "";
	$e_id="";
	$event_query="";
	$event_row = "";
	$e_header="";
	$e_description= "";
	$e_campus= "";
	$p_speaker_note = "";//Bella (Part - 1)

	//Retrieves presentation id stored in the GET variable
	if(isset($_GET['p_id']))
	//p_id found in GET variable
	{
		$p_id=$_GET['p_id'];
		//fetch p_id 

		$present_query=mysqli_query($con_mysqli, "SELECT * FROM Presentations WHERE p_id='$p_id'");
		//get presentation by p_id

		if(mysqli_num_rows($present_query)==1) //p_id is always unique
		//if results exist 
		{
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
			//fetch speaker note 
			$p_speaker_note = $present_row['p_speaker_info'];//Bella (Part - 2)
			$p_speaker_note = str_replace(array("\r\n","\r","\n"), "<br/>", $p_speaker_note); //Bella (Part - 2.2)
			

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
		}
	}

?>

<!--------- Toggle Function (js) --------->
<script type="text/javascript">
	
    function toggle(section) 
    {
	  var x = document.getElementById(section);
	  if (x.style.display === "none") 
	  {
	    x.style.display = "block";
	  } 

	  else {
	    x.style.display = "none";
	 }
	}

</script>

<!--------- Main Container --------->
<div class="index_main">

	<img src="assets/images/backgrounds/cinema_chairs.jpg">

	<!--------- Page Title --------->
	<div class="page_title">	

		Presentation Previewer

		<!---- help button ---->
		<button  class="help_button" onclick="toggle('preview_page')" type="button">
         		?
     	</button>

		<!---- back button ---->
     	<button  class="back_button" onclick="location.href='index_main.php'" type="button">
         		Back    	
        </button>

	</div>

	<!--------- Help Box --------->
    <div class="help_box" id="preview_page">

    	<h1>Preview Page</h1><br>
 
    	<p>
    		If you're like many people, you've had skydive on your bucket list for as long as you can remember. It probably sits right up there at the top (somewhere around "ride an elephant through the jungle" and "throw an opening pitch at Wrigley Field.") You know how incredible it'll be, but you're also pretty sure that once will be enough.
    	</p><br>
		<center>
	    	<a href="mailto:planetofthegrapes.ft01@gmail.com?subject=Present Customer Support"title="Contact Support">
				Contact Support
			</a>
		</center>
    </div>

	<!--Display Alerts if any (at top of screen) -->

	<?php if(in_array("<div class='form_alert'> You are already signed up!</div>", $error_array))
	echo "<div class='form_alert'> You are already signed up!</div>"?>

	<?php if(in_array("<div class='form_alert'> Registration unsuccessful. Contact support.</div>", $error_array))
	echo "<div class='form_alert'> Registration unsuccessful. Contact support.</div>"?>

	<?php if(in_array("<div class='form_alert'> Your registration is successful. A confirmation email has been sent to you.</div>", $error_array))
	echo "<div class='form_alert'> Your registration is successful. A confirmation email has been sent to you.</div>" ?>

	<!--Display Alerts if any (at top of screen) -->


	<div class="preview_container">
		
		<div class="event_container">
			
			<div class="event_header">

				<a href="javascript:toggle('description_box');">
					<h2> <?php echo $e_header;?> </h2>
				</a>

		    </div>

		    <div class="event_description" id="description_box">

		    	<h1>About The Course</h1>
		    	
		    	<p> <?php echo $e_description;?> </p>

		    </div>

			<div class="presentation_container">
				
				<div class="presentation_info">
					
					<h3> <?php echo $p_title;?> </h3>
			
					<h4> <?php echo $p_date."&nbsp|&nbsp".$p_time."&nbsp|&nbsp".$e_campus?> </h4>

				</div>

			</div>

			<div class="details_container">


				<div class="details_video">

					<video width="580" height="320" controls>
					  <source src="<?php echo $p_video;?>" type="video/mp4">
					Your browser does not support the video tag.
					</video>

				</div>

				<hr>

				<div class="details_buttons">
					
					<button  class="button" onclick="location.href='index_main.php'" 
					type="button">
		         		< Back
		     		</button>

		     		<button class="button" id="register" onclick="toggle('signup_box')"
					type="button">
		         		Register
		     		</button>

		     		<button  class="button" onclick="toggle('link_box')"
					type="button">
		         		Share
		     		</button>

		     		<div id="link_box">
		     			<br>
		     			Link:
		     			<br><br>
		     			<input type="text" value="<?php echo $p_link;?>">
		     		</div>

		     		<div id="signup_box">
		     			
		     			<form action="<?php echo $p_link; ?>" method="POST">

			     			<br>
			     			Sign Up:
			     			<br>
			     			<br>
			     		
			     			<input type="text" name="signup_fname" placeholder="First Name" value="<?php
							//PHP code to restore previously entered data  (in event of error)
							if(isset($_SESSION['signup_fname'])){
							echo $_SESSION['signup_fname'];}?>"required >
							<!--Display Error Message (incorrect input) -->
							<?php if(in_array("<br> Your first name must be between 2 and 25 characters.<br>", $error_array))
							echo "<br> Your first name must be between 2 and 25 characters.<br>" ?>
			     			<br>

			     			<input type="text" name="signup_lname" placeholder="Last Name" value="<?php
							//PHP code to restore previously entered data  (in event of error)
							if(isset($_SESSION['signup_lname'])){
							echo $_SESSION['signup_lname'];}?>"required >
							<!--Display Error Message (incorrect input) -->
							<?php if(in_array("<br> Your last name must be between 2 and 25 characters.<br>", $error_array))
							echo "<br> Your last name must be between 2 and 25 characters.<br>" ?>

			     			<br>

			     			<input type="text" name="signup_affiliation" placeholder="Affilliation" value="<?php
							//PHP code to restore previously entered data  (in event of error)
							if(isset($_SESSION['signup_affiliation'])){
							echo $_SESSION['signup_affiliation'];}?>"required >
			     			<br>

			     			<input type="text" name="signup_position" placeholder="Position" value="<?php
							//PHP code to restore previously entered data  (in event of error)
							if(isset($_SESSION['signup_position'])){
							echo $_SESSION['signup_position'];}?>"required >
			     			<br>

			     			<input type="email" name="signup_email" placeholder="Email"value="<?php
							//PHP code to restore previously entered data  (in event of error)
							if(isset($_SESSION['signup_email'])){
							echo $_SESSION['signup_email'];}?>"required >
							<!--Display Error Message (incorrect input) -->
							<?php if(in_array("<br> Invalid email format.<br>", $error_array))
							echo "<br> Invalid email format.<br>" ?>
			     			<br>

			     			<input type="text" style="visibility:hidden;" name="signup_pid" value="<?php echo $p_id;?>" required>
			     			<br>

			     			<input type="submit" name="signup_button" value="Sign Up">
			     			<br>
		     			</form>
		     		</div>

				</div>

				<hr>

				<div class="details_description">
					
					<h1> The Presentation </h1>

					<p> <?php echo $p_details;?> </p>

				</div>

				<hr>

				<div class="details_speakers">

					<h1>
					The Speakers
					</h1>

					<p>
	                   <p> <?php echo $p_speaker_note;?> </p>  <!--Bella Part - 3-->
					</p>

				</div>

				<hr>

			</div>

		</div>

	</div>


</div>


