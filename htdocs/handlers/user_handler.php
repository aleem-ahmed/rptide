<?php
	// Initialize the app
	include_once '../../private/common/initialization.php';

	// Include the User class
	include_once '../../private/classes/user_class.php';
	
	// Initialize the results
	$server_results['status'] = 'success';
	$server_results['control'] = '';
	$server_results['message'] = '';
	
	// [USER-VERB] Make sure a user verb was passed
	if (!isset($_POST['user-verb'])) {
		$server_results['status'] = 'error';
		$server_results['control'] = 'form';
		$server_results['message'] = 'Error: No user verb specified!';
	}

	// [TOKEN] Make sure a token value was passed
	else if (!isset($_POST['token'])) {
		$server_results['status'] = 'error';
		$server_results['control'] = 'form';
		$server_results['message'] = 'Error: Invalid user session!';
	}

	// Make sure the token is legit
	else if ($_SESSION['token'] !== $_POST['token']) {
		$server_results['status'] = 'error';
		$server_results['control'] = 'form';
		$server_results['message'] = 'Timeout Error! Please refresh the page and try again.';
	
	} else {
		// Create a new User object
		$user = new User($mysqli);
		
		// [USER-VERB] Pass the user-verb to the appropriate method
		switch ($_POST['user-verb']) {
			
			// Sign up a new user
			case 'sign-up-user':
				$server_results = json_decode($user->createUser());
				break;

			// Sign in an existing user
			case 'sign-in-user':
				$server_results = json_decode($user->signInUser());
				break;

			// Send a request to reset a user's password
			case 'send-password-reset':
				$server_results = json_decode($user->sendPasswordReset());
				break;

			// Reset a user's password
			case 'reset-password':
				$server_results = json_decode($user->resetPassword());
				break;

			// Delete a user
			case 'delete-user':
				$server_results = json_decode($user->deleteUser());
				break;

			default:
				$server_results['status'] = 'error';
				$server_results['control'] = 'token';
				$server_results['message'] = 'Error: Unknown user verb!';
		}
	}

	// Create and then output the JSON data
	$JSON_data = json_encode($server_results, JSON_HEX_APOS | JSON_HEX_QUOT);
	echo $JSON_data;
?>