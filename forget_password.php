<!-- http://localhost/resetPasswordDemo/requestReset.php -->
<!-- http://localhost/phpmyadmin/ -->
<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function


require 'includes\other_handlers\requestReset.php';



?>
<!--  ^ PHP code ^ -->
<!--  

1. GENERATE UNIQUE ID TO THE PAGE WHICH CONTAIN UPDATE PASSWORD PAGE 
2. INSERT UNIQUE ID AND EMAIL ADDRESS TO resetpassword table TEMPORARILY 
3. GENERATE FULL URL WITH GENERATED UNIQUE PAGE ID
4. PEPARE EMAIL WITH THE FULL URL 
5. SEND EMAIL TO THE REQUESTED EMAIL ADDESS

-->
<html>

<head>

	<title> Login/Register </title>

	<!----------------------------------------------------->
	<!-- CSS Style Sheet --> 
	<link rel="stylesheet" type="text/css" href= "assets/css/register_styles.css">

	<!-- JQuery CDN Library --> 
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>


	<!--Favicon -->
    <link rel="icon" href="assets/images/favicon/favicon.ico">

	<!----------------------------------------------------->

<body>
<div class="wrapper">
    <?php if(in_array("<div class='form_alert'> Reset email has been successfully sent to your email. </div>",$error_array)) echo "<div class='form_alert'> Reset email has been successfully sent to your email. </div>"; ?>
    <div class="login_box">

    <button  class="previous" onclick="location.href='login_select.php'" type="button">
                ⬅︎
    </button>

    <div class="login_header">

        <img src="assets/images/logos/present_white.png">

        <br><br>

        Enter your email

        <br>

    </div>

        <!--------------------------Login Form--------------------------->

        <div id="first">

            <form method="POST">

                <!--Login Form Fields-->

                <!--Email Section -->
                <input type="text" name="email" placeholder="Email" autocomplete="off">
                <br>
                <!--Submit Button -->
                <input type="submit" name = "submit" value="reset email">
                
            </form>
            
        </div>
    </div>    
</div>     
</body>

</html>

<!--  ^ html code ^ -->
<!--

1.TO GET USER'S EMAIL 
2.SUBMIT THE EMAIL 

-->
