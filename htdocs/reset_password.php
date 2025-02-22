<?php
	include_once '../private/common/initialization.php';
	$page_title = 'Reset Your Password';
?>

<?php
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
	// Make sure the email address was passed
	else if (!isset($_GET['username'])) {
		$server_results['status'] = 'error';
		$server_results['control'] = 'form';
		$server_results['message'] = 'Error: Invalid email address.';
	
	} else {
		// Get the query string parameters
		$ver_code = $_GET['vercode'];
		$username = $_GET['username'];
		
		// Sanitize them
		$ver_code = filter_var($ver_code, FILTER_SANITIZE_STRING);
		$username = filter_var($username, FILTER_SANITIZE_EMAIL);
	}
?>

<?php include_once 'common/top/top.php'; ?>

<div class="f-island">

<?php
	// If there is an error display:
	if ($server_results['status'] === 'error'):
?>

	<div class="result-message"><?php echo $server_results['message'] ?></div>
		
<?php
	else:
?>

	<p>You’re resetting the password for <?php echo $username ?>.</p>
	<p>If this is not your RpTide email address, please <a href="request_new_password.php">send a new password reset request</a>.</p>

	<form id="user-reset-password-form">
		<div class="form-wrapper">
			<div class="control-wrapper">
				<label for="password">Password</label>
				<div>
					<input id="password" class="form-control" name="password" type="password" minlength="8" aria-label="Type your password." required>
					<br>
					<input id="password-toggle" type="checkbox"><label for="password-toggle" class="label-horizontal">Show password</label>
				</div>
				<span id="password-error" class="error error-message"></span>
			</div>
			
			<button id="reset-password-button" class="btn btn-form" type="submit">Reset Password</button>
			
			<span id="form-error" class="error error-message form-error-message"></span>
			<span id="form-message" class="form-message"></span>
			
			<input type="hidden" id="username" name="username" value="<?php echo $username ?>">
			<input type="hidden" id="vercode" name="vercode" value="<?php echo $ver_code ?>">
			<input type="hidden" id="user-verb" name="user-verb" value="reset-password">
			<input type="hidden" id="token" name="token" value="<?php echo $_SESSION['token']; ?>">
		</div>
	</form>

<?php
	endif;
?>

</div>

<?php include_once 'common/bottom/bottom.php'; ?>