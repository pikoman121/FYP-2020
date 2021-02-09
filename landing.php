<!--landing.php-->

<!----------------------------------------------------->
<!--http://localhost:8080/present_app/landing.php -->
<!--http://localhost:8080/phpmyadmin/--> 
<!----------------------------------------------------->

<?php 
include("info_panels.php");
//includes file to display header and sidebar
?>

<script type="text/javascript">
  var x = document.getElementById('sb');
  //fetch side bar
  x.style.display = "none";
  //hide sidebar
</script>


<div class="landing_page">

	<img src="assets/images/backgrounds/audience.jpg">

	<div class="landing_logo">
		<img src="assets/images/logos/present.png">
	</div>

	<div class="landing_message">
		<h1>It's time to be inspired.</h1>
	</div>

	<div class="landing_button">
		<button  class="enter_button" onclick="location.href='index_main.php'" type="button">
         	Enter
     	</button>
	</div>
	
    <div class="page_footer">
		<p>Copyright © 2020 Planet Of The Grapes Inc. (ICT302 TM2020 FT01) </p>
	</div>

</div>