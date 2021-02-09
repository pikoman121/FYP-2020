<!--access_handler.php-->

<?
/*--checks if a privileged user is logged in--*/

if(!isset($_SESSION['acctype']) && !isset($_SESSION['username']))
//user is not logged in , halt access to privileged pages
{
	header("Location: login_select.php");
	//redirect the user to login portal
	exit();
	//close script
}

?>