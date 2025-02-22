<!-- Sign In Page -->
<?php
	include_once '../private/common/initialization.php';
	
	// If the user is signed in set hte title accordingly
	if (isset($_SESSION['username'])) {
		$page_title = 'You’re Signed In to Your Account';
		
	} else {
		$page_title = 'Sign In to Your Account';
	}
?>

<?php include_once 'common/top/top.php'; ?>

<?php	
	// if the user is signed in display:
	if (isset($_SESSION['username'])):
?>

	<div class="f-island">
		<section>
			<p>You’re already signed in.</p>
			<p>Did you want to <a href="create_data.php">create a note</a>?</p>
			<p>Or perhaps you want to <a href="sign_out.php">sign out</a>?</p>
		</section>
	</div>

<?php
	else:
?>
	<div class="user-terminal">
		<form id="user-sign-in-form">
			<!-- Title -->
			<p class="user-sign-title">Sign In</p>
			
			<!-- Email -->
			<input id="username" class="user-text-box" name="username" type="email" aria-label="Type your email address." placeholder="Email" required/>
			
			<span id="username-error" class="error error-message" style="width: 100%;"></span>
			
			<!-- Password -->
			<input id="password" class="user-text-box" name="password" type="password" minlength="8" aria-label="Type your password." placeholder="Password" required>
			<br>
			<input id="password-toggle" type="checkbox">
			<label for="password-toggle" class="label-horizontal">Show password</label>

			<span id="password-error" class="error error-message" style="width: 100%;"></span>
			
			<!-- Submit Button -->
			<button id="sign-me-in-button" class="btn user-sign-button" type="submit">Sign Me In</button>
			
			<!-- Errors -->
			<span id="form-error" class="error error-message form-error-message"></span>
			<span id="form-message" class="form-message"></span>

			<!-- Hidden Inputs -->
			<input type="hidden" id="user-verb" name="user-verb" value="sign-in-user">
			<input type="hidden" id="token" name="token" value="<?php echo $_SESSION['token']; ?>">
		</form>

		<div><a href="request_new_password.php">Forgot your password?</a></div>
	</div>

<?php
	endif;
?>

<?php include_once 'common/bottom/bottom.php'; ?>