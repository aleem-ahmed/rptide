/*
 * ====================================== *
 * [SIGN-UP] Event Handlers and Functions *
 * ====================================== *
 */
/* [Click-Hander] ('#show-sign-up-page-button') */
$('#show-sign-up-page-button').click(function() {
	// Open the Sign Up page
	window.location = '/sign_up.php';
});

/* [Click -> Submit-Event-Handler] ('#user-sign-up-form') */
$('#user-sign-up-form').submit(function(e) {
	// Prevent the default submit
	e.preventDefault();
	
	// Disable the Sign Me Up button to prevent double submissions
	$('#sign-me-up-button').prop('disabled', true);

	// Clear and hide all the message spans ($ = "ends with")
	$('span[id$="error"').html('').css('display', 'none');
	$('#form-message').html('').css('display', 'none');

	// Get the form data and convert it to a POST-able format
	formData = $(this).serializeArray();
	
	// Submit the data to the handler
	$.post('/handlers/user_handler.php', formData, function(data) {
		// Convert the JSON string to a JavaScript object
		var result = JSON.parse(data);
		
		if (result.status === 'error') {
			// Display the error
			$('#' + result.control + '-error').html(result.message).css('display', 'inline-block');

			// Enable the Sign Me Up button
			$('#sign-me-up-button').prop('disabled', false);
		
		} else {
			$('#form-message').html(result.message).css('display', 'inline-block');
		}
	});
});

/* [Click-Handler] ('#password-toggle') */
$('#password-toggle').click(function() {
	// Is the check box checked?
	if ($(this).prop('checked') === true) {
		// If so, change the <input> type to "text"
		$('#password').attr('type', 'text');
		$('label[for=password-toggle').text('Hide password');

	} else {
		// If not, change the <input> type to 'password'
		$('#password').attr('type', 'password');
		$('label[for=password-toggle').text('Show password');
	}
});

/*
 * ====================================== *
 * [SIGN-IN] Event Handlers and Functions *
 * ====================================== *
 */
/* [Click-Handler] ('#show-sign-in-page-button') */
$('#show-sign-in-page-button').click(function() {
	// Open the Sign In page
	window.location = '/sign_in.php';
});

/* [Click -> Submit-Event-Handler] ('#user-sign-in-form') */
$('#user-sign-in-form').submit(function(e) {
	// Prevent the default submit
	e.preventDefault();
	
	// Disable the Sign Me In button to prevent double submissions
	$('#sign-me-in-button').prop('disabled', true);

	// Clear and hide all the message spans ($ = "ends with")
	$('span[id$="error"').html('').css('display', 'none');
	$('#form-message').html('').css('display', 'none');

	// Get the form data and convert it to a POST-able format
	formData = $(this).serializeArray();
	
	// Submit the data to the handler
	$.post('/handlers/user_handler.php', formData, function(data) {
		// Convert the JSON string to a JavaScript object
		var result = JSON.parse(data);
		
		if (result.status === 'error') {
			// Display the error
			$('#' + result.control + '-error').html(result.message).css('display', 'inline-block');

			// Enable the Sign Me In button
			$('#sign-me-in-button').prop('disabled', false);
		
		} else {
			// The user is now signed in, so display the home page
			window.location = '/your_account.php';
		}
	});
});

/*
 * ======================================= *
 * [SIGN-OUT] Event Handlers and Functions *
 * ======================================= *
 */
/* [Click-Handler] ('#user-sign-out-button') */
$('#user-sign-out-button').click(function() {
	// Open the Sign Out page
	window.location = '/sign_out.php';
});

/*
 * ============================================= *
 * [RESET-PASSWORD] Event Handlers and Functions *
 * ============================================= *
 */
/* [Click -> Submit-Event-Handler] ('#user-send-password-reset-form') */
$('#user-send-password-reset-form').submit(function(e) {	
	// Prevent the default submit
	e.preventDefault();
	
	// Clear and hide all the message spans ($ = "ends with")
	$('span[id$="error"').html('').css('display', 'none');
	$('#form-message').html('').css('display', 'none');

	// Get the form data and convert it to a POST-able format
	formData = $(this).serializeArray();
	
	// Submit the data to the handler
	$.post('/handlers/user_handler.php', formData, function(data) {
		// Convert the JSON string to a JavaScript object
		var result = JSON.parse(data);
		if (result.status === 'error') {
	
			// Display the error
			$('#' + result.control + '-error').html(result.message).css('display', 'inline-block');

		} else {
			// Display the success message
			$('#form-message').html(result.message).css('display', 'inline-block');
		}
	});
});

/* [Click -> Submit-Event-Handler] ('#user-reset-password-form') */
$('#user-reset-password-form').submit(function(e) {
	//Prevent the default submit
	e.preventDefault();
	
	// Clear and hide all the message spans ($ = "ends with")
	$('span[id$="error"').html('').css('display', 'none');
	$('#form-message').html('').css('display', 'none');

	// Get the form data and convert it to a POST-able format
	formData = $(this).serializeArray();
	
	// Submit the data to the handler
	$.post('/handlers/user_handler.php', formData, function(data) {
		// Convert the JSON string to a JavaScript object
		var result = JSON.parse(data);
		
		if (result.status === 'error') {
			// Display the error
			$('#' + result.control + '-error').html(result.message).css('display', 'inline-block');

		} else {
			window.location = '/reset_successful.php';
		}
	});
	
	// Take the focus off the button
	$('#reset-password-button').blur();
});

/*
 * =========================================== *
 * [YOUR-ACCOUNT] Event Handlers and Functions *
 * =========================================== *
 */
/* [Click-Handler] ('#show-user-account-button') */
$('#show-user-account-button').click(function() {
	// Open the Your Account page
	window.location = '/your_account.php';
});

/*
 * ============================================= *
 * [DELETE-ACCOUNT] Event Handlers and Functions *
 * ============================================= *
 */
/* [Click -> Submit-Event-Handler] ('#user-delete-form') */
$('#user-delete-form').submit(function(e) {
	// Prevent the default submit
	e.preventDefault();
	
	// Clear and hide all the message spans ($ = "ends with")
	$('span[id$="error"').html('').css('display', 'none');
	$('#form-message').html('').css('display', 'none');

	// Get the form data and convert it to a POST-able format
	formData = $(this).serializeArray();
	
	// Submit the data to the handler
	$.post('/handlers/user_handler.php', formData, function(data) {
		// Convert the JSON string to a JavaScript object
		var result = JSON.parse(data);
		
		if (result.status === 'error') {
			// Display the error
			$('#' + result.control + '-error').html(result.message).css('display', 'inline-block');

		} else {
			window.location = '/deletion_successful.php';
		}
	});
	
	// Take the focus off the button
	$('#delete-user-button').blur();
});