<!-- Verify User Page -->
<?php
	// [REQUIRE] Personal //
	include_once '../private/common/initialization.php';
	include_once '../private/classes/user_class.php';


	// If the user is already signed in set the title accordingly
	if (isset($_SESSION['username'])) { $page_title = 'You’re Already Verified'; }
	else {
		$page_title = 'Welcome to RpTide';
		
		// Initialize the results
		$server_results['status'] = 'success';
		$server_results['control'] = '';
		$server_results['message'] = '';
		
		// Make sure a verification code was passed
		if (!isset($_GET['vercode'])) {
			$server_results['status'] = 'error';
			$server_results['control'] = 'form';
			$server_results['message'] = 'Error: Invalid URL. Sorry it didn\'t work out.';
		}

		// Make sure the username was passed
		else if (!isset($_GET['username'])) {
			$server_results['status'] = 'error';
			$server_results['control'] = 'form';
			$server_results['message'] = 'Error: Invalid user.';
		}
		else {
			// Create a new User object
			$user = new User($mysqli);
			
			// Verify the new account
			$server_results = json_decode($user->verifyUser(), TRUE);
		}
	}
?>

<!-- [HTML] -->
<?php include_once 'common/top/top.php'; ?>

<div class="f-island">
	
<?php	
	// If the user is has already beein verified display:
	if ($page_title === 'You’re Already Verified'):
?>

	<section>
		<p>You already have an account, so nothing to see here.</p>
		<p>Did you want to <a href="create_data.php">create a note?</a></p>
		<p>Or do you want to <a href="sign_out.php">sign out</a>?</p>
	</section>

<?php 
	else:
?>

	<div class="result-message"><?php echo $server_results['message'] ?></div>

<?php 
	endif;
?>

</div>

<?php include_once 'common/bottom/bottom.php'; ?>