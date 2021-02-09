<?php

require(dirname(__DIR__).'\classes\Logger.php');
//Require Logger class

session_start();
session_destroy();
//terminates session

$logger = new Logger(); //start logger
	//initialises a new logger object
$logger->write("SUCCESS > user logged out");
//send activity log
$logger->close();
//close logger

header ("Location: ../../login_select.php");
//redirect to register page
?>