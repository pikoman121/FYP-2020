<!-- http://localhost/resetPasswordDemo/requestReset.php -->
<!-- http://localhost/phpmyadmin/ -->
<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require dirname(__DIR__).'\email_handlers\PHPMailer2\src\Exception.php';
require dirname(__DIR__).'\email_handlers\PHPMailer2\src\PHPMailer.php';
require dirname(__DIR__).'\email_handlers\PHPMailer2\src\SMTP.php';
require dirname(__DIR__).'\..\config\config.php';


$error_array = array();

if(isset($_POST["email"])){
	
    // Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);
    $emailTo = $_POST["email"];
    $code = uniqid(true);
    $query = mysqli_query($con_mysqli,"INSERT INTO resetpassword(code,email) VALUES('$code','$emailTo')");
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'planetOfTheGrapes.ft01@gmail.com';                     // SMTP username
    $mail->Password   = 'borisjohnson';                               // SMTP password
    $mail->SMTPSecure = 'tls';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail->setFrom('planetOfTheGrapes.ft01@gmail.com', 'PresentApp');
    $mail->addAddress("$emailTo");     // Add a recipient


    // Content
    $url = "http://". $_SERVER["HTTP_HOST"].dirname($_SERVER["PHP_SELF"])."/forget_password_reset.php?code=$code";
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Your password reset link ';
    $mail->Body    = "<h1>Your requested email link </h1><br/>Click <a href='$url'> this link</a>to do so";
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    if($mail->send()) {
        echo "Reset email has been successfully sent to your email. You will be redirected back to the login page";
        header( "refresh:3;url=dirname(__DIR__).\..\login_uc.php" );
            //I
    } else {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    exit();	
}


?>
