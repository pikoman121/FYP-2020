<!-- THIS PAGE NEED UNIQUE ID -->
<!-- http://localhost/resetPasswordDemo/resetPassword.php?code= -->
<!-- code = unique ID FROM resetpassword table -->
<!-- http://localhost/phpmyadmin/ -->
<?php 

include('config\config.php');//to include database connection 
$error_array = array();
if(!isset($_GET["code"]))//to get Unique ID of the page //if the ID isn't provided or experired 
{
   exit("Link has expired");//the page will display Can't find page and won't execute the rest of code 
}

$code = $_GET["code"];//to assign Unique page ID to $code 

$getEmailQuery = mysqli_query($con_mysqli, "SELECT email FROM ResetPassword WHERE code ='$code'");//to get email address by matching unqiue id with id from resetpassword table // unique ID and email address are inserted into resetpassword table earlier 

if(mysqli_num_rows($getEmailQuery) ==  0 )//id is not found or removed from table //id will removed from table after user updated password 
{
   exit("Link has expired");//the page will display Can't find page and won't execute the rest of code 
}

if(isset($_POST["password"]))//to get password 
{
   $pw = strip_tags($_POST["password"]);
   $pw2 = strip_tags($_POST['password2']);//to assign password into $pw 
   if($pw!=$pw2) //passwords match
   {
      array_push($error_array,"<br> Your passwords do not match.<br>");
      //push message onto error array
   }
   else
   {
      if(preg_match ('/[^A-Za-z0-9]/', $pw)) //no special characters
      {
         array_push($error_array,"<br> Your password can only contain english characters or numbers.<br>");
         //push message onto error array
      }
      else{
         if(strlen($pw)>30 || strlen($pw)<5) //password too long/short
		   {
			   array_push($error_array,"<br> Your password must be between 5 and 30 characters.<br>");
			//push message onto error array
         }
         else{
            $pw2 = md5($pw2);//to encrypt the password 
            $row = mysqli_fetch_array($getEmailQuery);//to fetch query array 
            $email = $row["email"];//to assign email address to $email 
            $query = mysqli_query($con_mysqli, "UPDATE users SET u_password='$pw2' WHERE u_email = '$email' ");//to update the password in the database using email 
            if($query)//if the query executed successfully 
            {
            $query = mysqli_query($con_mysqli, "DELETE FROM resetpassword WHERE code = '$code'");//to remove id from resetpassword table 
            array_push($error_array,"<div class='form_alert'> Password Updated. You will be redirected to login page in 5 seconds</div>");//the page will display Password updated and won't execute the rest of code
            header( "refresh:5;url=login_uc.php" );
            }
            else //if the query isn't executed successfully 
            {
               array_push($error_array,"<div class='form_alert'> Something is wrong</div>");//the page will display Something went wrong and won't execute the rest of code 
            }
         }
      }
   }   
}


?>
<html>
   <head>

      <title> Password reset </title>

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
   <?php if(in_array("<div class='form_alert'> Password Updated. You will be redirected to login page in 5 seconds</div>",$error_array)) echo "<div class='form_alert'> Password Updated. You will be redirected to login page in 5 seconds</div>"; ?>


      <div class="login_box">

      <button  class="previous" onclick="location.href='login_select.php'" type="button">
                  ⬅︎
      </button>

            <div class="login_header">

               <img src="assets/images/logos/present_white.png">

               <br><br>

               Enter your new password.

               <br>

            </div>
               <div id="first">

                     <form method="POST">

                        <!--Login Form Fields-->

                        <!--Email Section -->
                        
                        <input type="password" name="password" placeholder="Enter your new password" >
                        <br/>
                        <input type="password" name="password2" placeholder="Enter your new password again" >
                        <br/>
                        <!--Error Messages -->
                        <?php if(in_array("<br> Your passwords do not match.<br>",$error_array))
                        //Display error messages in error array 
                        echo "<br> Your passwords do not match.";
                        else if(in_array("<br> Your password can only contain english characters or numbers.<br>",$error_array))
                        //Display error messages in error array 
                        echo "<br> Your password can only contain english characters or numbers.";
                        else if(in_array("<br> Your password must be between 5 and 30 characters.<br>",$error_array))
                        //Display error messages in error array 
                        echo "<br> Your password must be between 5 and 30 characters.";?>
				            <input type="submit" name="submit" value="Update Password">

                        
                     </form>
                     
               </div>
      </div>
   </div>        
   </body>
</html>
