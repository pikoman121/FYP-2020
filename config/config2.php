<!----------------------------------------------------->
<!--Establishes connection to MySQL database using PHP -->

<?php

//Note: Add this instruction to any file you are using to connect to the database

ob_start();
//Turns on output buffering

//A session is a way to store information (in variables) to be used across multiple pages.
//creates a session / lets you to store values inside a $_SESSION variable

$timezone = date_default_timezone_set("Asia/Singapore");
//sets the time zone

$con_mysqli = mysqli_connect("localhost","root","","present_app_db");
//creates a connection variable and connects to MySQL server
//Connects to the 'present_app_db' database

if(mysqli_connect_errno())
//if error number returned
{
	echo "Failed to connect: " . mysqli_connect_errno();
}
//print error message if connection was unsuccessful


/****** For Video Uploader *******/
try
{
    $con_pdo = new PDO("mysql:dbname=present_app_db;host=localhost","root","");
    $con_pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
}
catch(PDOException $e)
{
	echo "Communication failed" . $e->getMessage();
}
/****** For Video Uploader *******/

?>

<!--Establishes connection to MySQL database using PHP -->
<!----------------------------------------------------->