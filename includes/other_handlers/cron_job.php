<?php 
    
  /**
  
  Flow of the program 
  
  1.Get current date (today date)
  2.select all the dates from Date table 
  3.compare current date with all the dates from database
  4.if it matched , insert the matched date to another table named insertDate
  5.repeat this for every date in Date table 
  
  Note:
  
  1.Date table contain multiple current dates and it is not sequencel for testing purpose and for testing purpose, numberOfMatchedDate column is included in insertDate table to keep track of matched dates in the database.
  2. insertedDate column is included in insertDate table to keep track of the time data is inserted in the table for testing purposes. 
  
  **/
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;
  date_default_timezone_set("Asia/Singapore");//to get Singapore standard time 
  try
  {
    $con = new PDO("mysql:dbname=present_app_db;host=localhost","root","");//connection 
    $con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
  }
  catch(PDOException $e)
  {
	  echo "Communication failed" . $e->getMessage();
  }
  //to  get connection with database 
  require dirname(__DIR__).'\email_handlers\PHPMailer2\src\Exception.php';
  require dirname(__DIR__).'\email_handlers\PHPMailer2\src\PHPMailer.php';
  require dirname(__DIR__).'\email_handlers\PHPMailer2\src\SMTP.php';
  //require 'PHPMailer/src/Exception.php';
  //require 'PHPMailer/src/PHPMailer.php';
  //require 'PHPMailer/src/SMTP.php';
  require(dirname(__DIR__).'\classes\Logger.php');
  $logger = new Logger();
  $currentDate = date("y-m-d"); 
  //to fetch current date 
  $current_date = strtotime($currentDate); 
  //to change into another format for comparison 

  $numberOfMatchedDate = 0;
  //Keep track of matched dates in table 
  //Date table contain repeated dates and dates aren't sequencial 

  $query = $con->prepare("SELECT * FROM presentations, attendees WHERE p_id = a_presentation_id");
  //Get all the dates from Date Database 
  //Query a table that is form from the merge of attendees table and presentation table via the presentation id 
  $query->execute();
  //to execute query 
  $logger->write("STARTED > Cron_job for assessment matrix delivery");
  $p_id ="";
  if($query->rowCount() > 0 ){ 
    foreach ($query as $row) //to loop through all the rows individually 
    {
      $newDate = $row["p_date"];             		      
      //date from database 
      $currentDate = strtotime($newDate);
      //to change into another format for comparison 
        
      if(($currentDate)>($current_date)) //greater than today date 
      {
        //do nothing 
      }
      else if(($currentDate) < ($current_date)) //less than today date 
      {
        //do nothing 
      }
      else 
      { 
        $p_id = $row["p_id"];
        $a_id = $row["a_id"];
        $fname = $row['a_first_name'];
        //Attendee first name
        $p_title = $row['p_title'];
        $p_details = $row['p_details'];
        // Presentation Title
        $p_link = "http://localhost/present_app/feedback_form.php?p_id=$p_id&a_id=$a_id";
        //Link to assessment matrix.          
        $message=
        "
        <html>
          <body style=\"color: #000; font-size: 1rem; text-decoration: none; font-family: 'Arial', sans-serif; background-color: #F7F7F7;\">
        
            <div class=\"wrapper\" style=\"max-width: 46.875rem; margin: auto auto; padding: 0.938rem;\">
        
              <div id=\"content\" style=\"font-size: 1rem; padding: 1.563rem; background-color: #fff; border:none; border-radius: 0.563rem; box-shadow: 0.031rem 0.031rem #000; \">
                <div id=\"logo\">
                  <center>
                    <h1 style=\"margin: 0.625rem;\">
                      <!--present logo-->
                      <a href=\"http://localhost:8080/present_app/landing.php\" target=\"_blank\">
                        <img style=\"max-height: 7.5rem;\" src=\"https://lh3.googleusercontent.com/zsbknjX7egLSyCS8jwMSJn-QUR6Ybs2GpP7FkBVslSiVm58QhGJ-0I5nSwhkueoyLLARDj7D9aS4uSkiaeVSJ6B1oqDX39S6Uyd4OZVI6YYOjAn9Jnhd1ZoP1NMk_m2jhgfYkbCc0Q=w2400\">
                      </a>
                    </h1>
                  </center>
                </div>
        
                <h1 style=\"font-size: 1.5rem; color:#1D1A1A\";><center>Hi John, we hope you enjoyed...</center></h1>
        
                  <div id=\"text\" style=\"text-align:center; margin:0.3rem; padding:1.563rem;\">
                    <!--event title-->
                    <p style=\"color:#CD0000;\"> $p_title </p>
                    <!--presention title-->
                    <h1 style=\"padding:0.313rem;\"> $p_details </h1>
                    <hr>
                    <!--message-->
                    <p>Let us know what you thought about the presentation.</p>
                    <!--message-->
                    <h2>Click below to give feedback!</h2>
                    <!--link to feedback-->
                    <center style=\"margin-top:1.563rem;\">
                      <a href=\"$p_link\" target=\"_blank\" style=\"background-color: #CD0000; color: #FFF; text-decoration: none; font-size: 1rem; padding: 0.438rem 0.938rem; border-radius:1.25rem; border-color:#fff;\">
                        Give Feedback
                      </a>
                    </center><br>
                  </div>
              </div>
            </div>
          </body>
        </html>
         
        ";
        
        $logger->write("SUCCESS > Found: Event ".$p_id);
        $email = $row["a_email"];
        $logger->write("SUCCESS > Found: Email ".$email);
        try {
          $mail = new PHPMailer(true);
          $mail->isSMTP();                                            
            // Send using SMTP
          $mail->Host       = 'smtp.gmail.com';                    
            // Set the SMTP server to send through
          $mail->SMTPAuth   = true;                                   
            // Enable SMTP authentication
          $mail->Username   = 'planetOfTheGrapes.ft01@gmail.com';                     
            // SMTP username
          $mail->Password   = 'borisjohnson';                               
            // SMTP password
          $mail->SMTPSecure = 'tls';         
            // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
          $mail->Port       = 587;                                    
            // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
          $mail->setFrom('planetOfTheGrapes.ft01@gmail.com', 'bin');
          $mail->addAddress($row['a_email'],$row['a_first_name']);     // Add a recipient
            //$mail->addAddress('ng_weibin@hotmail.com');  
              
            // Content
          $mail->isHTML(true);                                  
            // Set email format to HTML
          $mail->Subject = $row["a_first_name"];
            //Subject
          $mail->Body = $message;
          $mail->send();
          $logger->write("SUCCESS > Email Sent to: ".$email);
          $update_query = $con->prepare("UPDATE presentations SET p_mailer_sent='yes' WHERE p_id = '$p_id' ");
          $update_query->execute();
        } catch (Exception $e) {
          echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
          $logger->write("ERROR > Email NOT SENT: {$mail->ErrorInfo}".$email);
        }         
      }
    }
  }
  $logger->write("COMPLETED > All Assessment matrix have been sent");
?>