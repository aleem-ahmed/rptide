<!-- Sign Out Page -->
<?php
	session_start();
	
	// Free up all the session variables
	session_unset();
?>

<!-- Display the sign-in page -->
<meta http-equiv="refresh" content="0;sign_in.php">