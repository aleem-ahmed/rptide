<!-- Your Account Page -->
<?php
	include_once '../private/common/initialization.php';
	$page_title = 'Your RpTide Account';
?>

<?php include_once 'common/top/top.php'; ?>

<div class="f-island">
	
<?php
	// If user is signed in
	if (isset($_SESSION['username'])):
?>

	<p>
		<strong>Your RpTide username: </strong><?php echo $_SESSION['username'] ?>
	</p>
	<hr style="margin-bottom: 3rem;">
	
	<!-- NOTES -->
	<strong>Notes</strong>
	<br>
	
	<!-- The Activity Log appears here -->
	<section id="activity-log" class="activity-log"></section>

	<!-- This hidden form contains the values we need to read the data: log-id, data-verb, and token -->
	<form id="data-read-form" class="hidden">
		<input type="hidden" id="log-id" name="log-id" value="<?php echo $_SESSION['log_id']; ?>">
		<input type="hidden" id="data-verb" name="data-verb" value="read-all-data">
		<input type="hidden" id="token" name="token" value="<?php echo $_SESSION['token']; ?>">
	</form>

	<!-- The toolbar contains "data-create-button" -->
	<div class="activity-log-toolbar" style="margin-top: 1em;" role="toolbar">
		<button id="data-create-button" class="btn" role="button">Add New</button>
	</div>
	
	<!-- If there's an error reading the data, the error message appears inside this span -->
	<span id="read-error" class="error error-message"></span>

	<!-- USER CONTROLS -->
	<hr style="margin-top: 3rem;">
	<p><a href="/request_new_password.php">Change Your Password</a></p>
	<p><a href="/sign_out.php">Sign Out</a></p>
	<p><a href="/delete_account.php">Delete Your Account</a></p>

<?php
	else:
?>

	<!-- Display the sign-in page -->
	<meta http-equiv="refresh" content="0;sign_in.php">

<?php
	endif;
?>

</div>

<?php include_once 'common/bottom/bottom.php'; ?>