<!--upload.php-->

<!----------------------------------------------------->
<!--http://localhost:8080/present_app/upload.php -->
<!--http://localhost:8080/phpmyadmin/--> 
<!----------------------------------------------------->

<?php 

include("info_panels.php");
//includes file to display header and sidebar

include("includes/classes/Presentation.php");
//Bella Part-1 (start)
include("includes/classes/Video.php");
include("includes/form_handlers/upload_handler.php");
include("includes/classes/Logger.php");
//Bella Part - 1 (end)

//include the Presentation class

//declare variables
$username="";
$acctype="";
$rep_id="";
$event_id="";
$present_id="";

//fetch user data

if(isset($_SESSION['username']))
//if session variable contains username (rep user logged in)
{
	$username=$_SESSION['username'];
	//fetch username
	$acctype=$_SESSION['acctype'];
	//fetch acctype
	$user_obj = new User($con_mysqli, $username);
	//create a new user object
	$rep_id=$user_obj->getID();
	//fetch the id of the user 
	$event_id=$user_obj->getEventID();
	//fetch the event id of this presentation
	$present_id=$user_obj->getPresentationID();
	//fetch the presentation id of this presentation (* we will used this id to insert into Presentations table)
    $error_array = array();//array of errors and alerts
	//to get p_published , p_title , p_details from database 
    $present_query=mysqli_query($con_mysqli, "SELECT * FROM presentations WHERE p_id='$present_id'");
	$present_row = mysqli_fetch_array($present_query);
	//to check presentation is published or not 
	if($present_row['p_published'] == "yes")
	{
		
		$_SESSION['title'] = $present_row['p_title'];//to get title 
	    $_SESSION['description'] = $present_row["p_details"];//to get description 
		$_SESSION['speaker_note'] = $present_row["p_speaker_info"];//to get speaker note
		$filePath = $present_row['p_video'];//to get video path 
		$filePathName = explode('/', $filePath);//to split the path 
		$fileName = $filePathName[3];//to get video file name
		$_SESSION['fileName'] = $fileName;//to store it in the session
		//if the presentation is published , the form stay hidden (JQuery)
		echo'

			<script>

			$(document).ready(function()
			{
				$(".event_form_container").hide();
			});

			</script>

			';		
	}
	
     if(isset($_POST["publish_presentation_button"])) //to avoid accessing the processing.php without actually submitting the form 
    {		

		    $logger = new Logger();//logger 
		    //video data object 
			$videoData = new VideoData( 
			                    $_FILES["present_video"], 
                                $_POST["present_title"],
                                $_POST["present_desc"],
								$_POST["present_id"],
								$_POST["present_speaker_note"]
							   );
							   
			$_SESSION['title'] = $_POST["present_title"];//to get title 
			$_SESSION['description'] = $_POST["present_desc"];//to get description 
			$_SESSION['speaker_note'] = $_POST["present_speaker_note"];//to get description           
             			
	$videoProcessor = new VideoProcessor($con_pdo);//to process the video and data //object 
    $wasSuccessful = $videoProcessor->upload($videoData,$logger);//to upload the video 
	 
    if($wasSuccessful == true)//if upload successful 
    {
		$logger->write("SUCESS > Presentation is successfully uploaded");
		$logger->write("Complete > Successfully completed ");
		$logger->close();
		$error_array = $videoProcessor->getErrorArray();//to get errors and alert messages 
		$fileName = $_FILES["present_video"]["name"];
		$_SESSION['fileName'] = $fileName;
		//when the form is submitted , the form stays hidden (JQuery)
		echo'

			<script>

			$(document).ready(function()
			{
				$(".event_form_container").hide();
			});

			</script>

			';
	}
	else //if upload is unsuccessful 
	{
		$logger->write("FAILURE > Presentation couldn't be uploaded");
		$logger->write("FAILURE > Uploading of presentation is terminated");
		$logger->close();
		$error_array = $videoProcessor->getErrorArray();//to get errors and alert messages 
		//if the submittion isn't successful the form stays 
		echo'

			<script>

			$(document).ready(function()
			{
				$(".event_form_container").show();
			});

			</script>

			';
	}
	
    }
	//Bella Part-2 (end)
}
?>

<!--------- Javascript function--------->
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

 //to display the loading-spinner
$(document).ready(function()
{
   $("#form").submit(function()//to check the form is submitted or not 
   {
     $("#loadingModel").modal("show"); //to display the loading-spinner
   });
});

</script>



<html>

<head>

	<title> Upload Presentation </title>

	<!----------------------------------------------------->
	<!-- CSS Style Sheet --> 

	<!-- JQuery CDN Library --> 
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>

	<!--Favicon -->
    <link rel="icon" href="assets/images/favicon/favicon.ico">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    
	<!----------------------------------------------------->

</head>

<body>
 <div class="modal fade" id="loadingModel" tabindex="-1" role="dialog" aria-labelledby="loadingModel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <p style='color:black;font:bold;font-size:24px'>Please Wait.This Might Take A While</p>
		<img src="assets/images/icons/loading-spinner.gif"  style="width:100%;"/>
      </div>
    </div>
  </div>
</div>
<div class="index_main">

	<img src="assets/images/backgrounds/cinema_chairs.jpg">

	<!--------- Page Title --------->
	<div class="page_title">	

		My presentation	

		<button  class="help_button" onclick="toggle('help_box')" type="button">
	         		?
	    </button>
	</div>

	<!--------- Help Box --------->
    <div class="help_box" id="help_box">

    	<h1>Upload Page</h1><br>
 
    	<p>
    		If you're like many people, you've had skydive on your bucket list for as long as you can remember. It probably sits right up there at the top (somewhere around "ride an elephant through the jungle" and "throw an opening pitch at Wrigley Field.") You know how incredible it'll be, but you're also pretty sure that once will be enough.
    	</p><br>
    </div>
<!--To display alert messages-->
<?php if(in_array("Presentation is published.<br>",$error_array)) echo "<div class='form_alert'> Presentation is published</div><br>"; ?>
<?php if(in_array("Presentation cannot be uploaded.<br>",$error_array)) echo "<div class='form_alert'> Presentation cannot be uploaded</div>"; ?>
<?php if(in_array("Presentation cannot be updated.<br>",$error_array)) echo "<div class='form_alert'> Presentation cannot be updated</div>"; ?>
<?php if(in_array("Presentation is updated.<br>",$error_array)) echo "<div class='form_alert'> Presentation is updated</div>"; ?>

	<div class="listing_container">

		<!--------- Event Module --------->
		<div class="event_container">

	    	<?php 
		    	/*---Displays the header bar conatining presentation information---*/
		    	$present_obj = new Presentation($con_mysqli, $username);
				//create a new presentation object
				$present_obj->showPresentationInfoFeedback($event_id,$rep_id);
				//display presentation information
	    	?>

			<!-- P-Module -->
			<div class="presentation_container">
						
				<div class= "event_form_container" id="event_form_container">

					<form action="upload.php" method="POST" enctype='multipart/form-data' id="form" >

						<!--Message -->
						<h3>Presentation Details</h3>
						<h4>Get viewers interested about your presentation!</h4>
						<br>

						<!--Title Input -->
						<label>Give your Presentation a Title</label>
						<input type="text"  name="present_title" placeholder="Describe your title" value="<?php if(isset($_SESSION['title'])){echo $_SESSION['title'];}?>"><!--Bella Part-4-->
						<br/>
					    <?php if(in_array("Title must be between 2-100 characters.<br>",$error_array)) echo "<p style='color:#F00000;font-size:14px'>Title must be between 2-100 characters</p><br>"; ?>
						
						<!--Description Input -->
						<label>Describe your Presentation</label><br>						<!--<textarea type='text' class="description_box" name='present_desc' form="new_event_form" value=""required ></textarea>-->
                         <textarea type='text' name='present_desc' placeholder='Write a brief description of your topic...'  rows='3' class='description_box' ><?php if(isset($_SESSION['description'])){echo $_SESSION['description'];}?></textarea><br/><!--Bella Part-5-->
						<br>
						<?php if(in_array("Description must be between 2-100 characters.<br>",$error_array)) echo "<p style='color:#F00000;font-size:14px'>Description must be between 2-100 characters</p><br>"; ?>
						<!--Speaker Input -->
						<label>Provide Speaker Information</label><br>						<!--<textarea type='text' class="description_box" name='present_desc' form="new_event_form" value=""required ></textarea>-->
                         <textarea type='text' name='present_speaker_note' placeholder='Speaker information here…'  rows='3' class='description_box'  ><?php if(isset($_SESSION['speaker_note'])){echo $_SESSION['speaker_note'];}?></textarea><br/><!--Bella Part-5-->
						<br/>
                        <?php if(in_array("Speaker note must be between 2-100 characters.<br>",$error_array)) echo "<p style='color:#F00000;font-size:14px'>Speaker note must be between 2-100 characters</p><br>"; ?>						
						<hr><br/>

						<h3>Promote your Presentation</h3>
						<h4>Upload a short promotional video! (Up to 100MB) </h4>
						<br>

						<!--Video input -->
						<input type="file" name="present_video" class="video_upload_box"  placeholder="" value=""><?php if(isset($_SESSION['fileName'])){echo "<p style='color:black;font-size:14px'>The presentation video is already uploaded</p><br>";}?><!--Bella Part-6 -->
						
						<!--Error Messages -->
						<?php if(in_array("The video's size is invalid.<br>",$error_array)) echo "<p style='color:#F00000;font-size:14px'>This file size is invalid / too large</p><br>"; ?>
                        <?php if(in_array("The video's type is invalid.<br>",$error_array)) echo "<p style='color:#F00000;font-size:14px'>This file type is invalid</p><br>"; ?>
                        <?php if(in_array("Video must be provided.<br>",$error_array)) echo "<p style='color:#F00000;font-size:14px'>A file must be provided</p><br>"; ?>
						<br><hr><br>

						<h3>Click on 'Save & Publish' to put you presentation on the web!</h3>
						<h4>Your presentation will be available for public viewing on PRESENT.</h4>
						<br><br>

						<!--hidden field that stores presentation id-->
						<!--On submit this field can still be accessed to retrieve the p_id to update values in Presentation table-->
						<input type="text"  style="display:none;" name="present_id" value="<?php echo $present_id;?>">

						<!--publish button-->
						<input type="submit"  name="publish_presentation_button"   value="Save & Publish" >

					</form>
				</div>

			</div>
			<!-- P-Module -->
		</div>
		<!--------- End of Event Module -------->

	</div>

</div>



</body>

</html>
