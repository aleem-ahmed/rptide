<!-- Sign Up Page -->
<?php
	include_once '../private/common/initialization.php';

	// If the user is signed in set hte title accordingly
	if (isset($_SESSION['username'])) {
		$page_title = 'You’re Already Signed Up';

	} else {
		$page_title = 'Sign Up';
	}
?>

<?php include_once 'common/top/top.php'; ?>

<?php	
	// if the user is signed in display:
	if (isset($_SESSION['username'])):
?>

	<div class="f-island">
		<section>
			<p>You already have an account.</p>
			<p>Did you want to <a href="create_data.php">create a note</a>?</p>
			<p>Or perhaps you want to <a href="sign_out.php">sign out</a>?</p>
		</section>
	</div>

<?php
	else:
?>

	<div class="user-terminal">
		<!-- Title -->
		<p class="user-sign-title">Create Account</p>

		<!-- Form -->
		<form id="user-sign-up-form">
			<div class="form-wrapper">
				<!-- Email -->
				<div class="control-wrapper">
					<label class="user-sign-label" for="username">Email</label>
					<input id="username" class="user-text-box" name="username" type="email" aria-label="Type your email address." autocomplete="no" required/>
					<span id="username-error" class="error error-message"></span>
				</div>

				<!-- First Name -->
				<div class="control-wrapper">
					<label class="user-sign-label" for="f-name">First Name</label>
					<input id="f-name" class="user-text-box" name="f-name" type="text" aria-label="Type your first name." required/>
					<span id="f-name-error" class="error error-message"></span>
				</div>

				<!-- Middle Name -->
				<div class="control-wrapper">
					<label class="user-sign-label" for="m-name">Middle Name</label>
					<input id="m-name" class="user-text-box" name="m-name" type="text" aria-label="Type your middle name." placeholder="Optional"/>
					<span id="m-name-error" class="error error-message"></span>
				</div>

				<!-- Last Name -->
				<div class="control-wrapper">
					<label class="user-sign-label" for="l-name">Last Name</label>
					<input id="l-name" class="user-text-box" name="l-name" type="text" aria-label="Type your last name." required/>
					<span id="l-name-error" class="error error-message"></span>
				</div>

				<!-- DOB -->
				<div class="control-wrapper">
					<label class="user-sign-label" for="dob">Date of Birth</label>
					<input id="dob" class="user-text-box" name="dob" type="date" aria-label="Enter you date of birth." required/>
					<span id="dob-error" class="error error-message"></span>
				</div>
				
				<!-- Create Password -->
				<div class="control-wrapper">
					<label class="user-sign-label" for="password">Create Password</label>
					<input id="password" class="user-text-box" name="password" type="password" minlength="8" aria-label="Type your password." autocomplete="no" required/>
					<span id="password-error" class="error error-message"></span>
					<br>

					<!-- Show Password -->
					<input id="password-toggle" type="checkbox"><label for="password-toggle" class="label-horizontal">Show password</label>
				</div>
				
				<!-- Submit Button -->
				<button id="sign-me-up-button" class="btn btn-form user-sign-button" type="submit">Sign Me Up</button>

				<span id="form-error" class="error error-message form-error-message"></span>
				<span id="form-message" class="form-message"></span>
				
				<input type="hidden" id="user-verb" name="user-verb" value="sign-up-user">
				<input type="hidden" id="token" name="token" value="<?php echo $_SESSION['token']; ?>">
			</div>
		</form>
	</div>

<?php
	endif;
?>

<?php include_once 'common/bottom/bottom.php'; ?>
