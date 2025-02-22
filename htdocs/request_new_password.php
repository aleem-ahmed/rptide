<?php
	include_once '../private/common/initialization.php';
	$page_title = 'Request a New Password';
?>

<?php include_once 'common/top/top.php'; ?>

<div class="f-island">
	<p>Enter your account email address below, and we’ll send you a link with instructions for resetting your password.</p>

	<form id="user-send-password-reset-form">
		<div class="form-wrapper">
			<div class="control-wrapper">
				<label for="email">Email</label>
				<input id="username" class="form-control" name="username" type="email" aria-label="Type your email address." required />
				<span id="username-error" class="error error-message"></span>
			</div>
			
			<button id="send-password-reset-button" class="btn btn-form" type="submit">Send It</button>
			
			<span id="form-error" class="error error-message form-error-message"></span>
			<span id="form-message" class="form-message"></span>
			
			<input type="hidden" id="user-verb" name="user-verb" value="send-password-reset">
			<input type="hidden" id="token" name="token" value="<?php echo $_SESSION['token']; ?>">
		</div>
	</form>
</div>

<?php include_once 'common/bottom/bottom.php'; ?>