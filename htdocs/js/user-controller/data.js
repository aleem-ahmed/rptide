/*
 * ================ *
 * Global variables *
 * ================ *
 */
// Stores the user's full (that is, non-filtered) activity log
var activityLog;

/*
 * ========================================== *
 * [CREATE DATA] Event Handlers and Functions *
 * ========================================== *
 */
/* [Click-Handler] ('#data-create-button') */
$('#data-create-button').click(function() {
	// Open the Log an Activity page
	window.location = "/create_data.php";
});

/* [Function] Initialize Create Data Form */
function initializeCreateDataForm() {
	// Hide the Delete button
	$('#data-delete-button').hide();
	
	// Set the data verb to "create"
	$('#data-verb').val('create');
}

/* [Click-Event-Handler] ('#data-cancel-button') */
$('#data-cancel-button').click(function(e) {
	// Prevent the button default
	e.preventDefault();
	
	// Go back to the home page
	window.location = '/your_account.php';
});

/* [CLICK -> SUBMIT-EVENT-HANDLER] ('#data-form') */
$('#data-form').submit(function(e) {
	// Prevent the default submission
	e.preventDefault();
	
	// Disable the Save button to prevent double submissions
	$('#data-save-button').prop('disabled', true);

	// Convert the data to POST format
	var formData = $(this).serializeArray();

	// Submit the data to the handler
	$.post('/handlers/data_handler.php', formData, function(data) {
		// Convert the JSON string to a JavaScript object
		var result = JSON.parse(data);

		if (result.status === 'error') {
			// Display the error
			$('#form-error').html(result.message).css('display', 'inline-block');

			// Enable the Save button
			$('#data-save-button').prop('disabled', false);
			
		} else {
			// Display the success message
			$('#form-message').html(result.message).css('display', 'inline-block');

			// Return to the home page after 3 seconds
			window.setTimeout("window.location='/your_account.php'", 2500); 
		}
	});

	console.log(formData);
});

/*
 * ======================================== *
 * [READ DATA] Event Handlers and Functions *
 * ======================================== *
 */
/* [Function] Read activities on database and return it */
function readActivities() {
	// Get the form data and convert it to a POST-able format
	formData = $('#data-read-form').serializeArray();

	// Submit the data to the handler
	$.post('/handlers/data_handler.php', formData, function(data) {
		// Convert the JSON string to a JavaScript object
		var result = JSON.parse(data);
				
		// If there was an error, result.status will be defined
		if (typeof result.status !== 'undefined') {
			// If so, display the error
			$('#read-error').html(result.message).css('display', 'inline-block');

		} else {
			// Otherwise, we can go ahead and display the data
			activityLog = result;
			displayActivityLog(activityLog); 
		}
	});
}

/*
 * ========================================== *
 * [UPDATE DATA] Event Handlers and Functions *
 * ========================================== *
 */
/* [Click-Handler] ('#activity-log') For the Activity Log's links. Since we created the links in code, we can't use them as jQuery selectors, so we use the closest DOM ancestor, which is the <section id="activity-log"> tag. */
$('#activity-log').click(function(e) {
	e.preventDefault();
	
	// Was a link clicked?
	if (e.target.tagName === 'A') {
		// If so, go to the linked page
		window.location = e.target;
	}

	// Otherwise, make sure we're dealing with an edit button
	else if (e.target.className === 'data-edit-button') {
		// Get the activity's ID
		var activityID = Number(e.target.id.split('-')[1]);
	
		//Load the Update form and send the activity ID in the query string
		window.location = '/update_data.php?activity-id=' + activityID;
	}
});

/* [Function] Initialize Update Data Form */
function initializeUpdateDataForm() {
	// Get the activity ID from the URL query string and save it to the form
	var activityID = Number(window.location.search.split('=')[1]);

	$('#activity-id').val(activityID);
	
	// Get the data for this item (JSON)
	var formData = [
		{"name": "log-id", "value": $('#log-id').val()},
		{"name": "activity-id", "value": $('#activity-id').val()},
		{"name": "data-verb", "value": "read-data-item"},
		{"name": "token", "value": $('#token').val()}
	];
   
	// Submit the data to the handler
	$.post('/handlers/data_handler.php', formData, function(data) {
		// Convert the JSON string to a JavaScript object
		var result = JSON.parse(data);

		// If there was an error, result.status will be defined
		if (typeof result.status !== 'undefined') {
			// If so, display the error
			$('#form-error').html(result.message).css('display', 'inline-block');

		} else {
			// Show the Delete button
			$('#data-delete-button').show();
		
			// Set the data verb to "update"
			$('#data-verb').val('update');
			
			/* STORE THE ACTIVITY VALUE */
			// We know that "result" is a single-item array, so just take the first item
			activity = result[0];
			var noteTicker = activity.note_ticker;
			var noteTitle = activity.note_title;
			var noteContent = activity.note_content;
			
			// Use the activity values to populate the edit form
			$('#activity-id').val(activityID);
			$('#note-ticker').val(noteTicker);
			$('#note-title').val(noteTitle);
			$('#note-content').val(noteContent);
		}
	});
}

/*
 * ========================================== *
 * [DELETE DATA] Event Handlers and Functions *
 * ========================================== *
 */
/* [Click-Handler] ('#data-delete-button') */
$('#data-delete-button').click(function(e) {
	// Take the focus off the button
	$(this).blur();
	
	// Open the jQuery UI dialog
	$('#confirm-delete').dialog('open');
	
	// Prevent the default action
	e.preventDefault();
});

/* [JQUERY-UI-CONFIGURATION] Delete This Activity dialog */
$("#confirm-delete").dialog({
	autoOpen: false,
	closeOnEscape: true,
	modal: true,
	width: 400,
	buttons: [
		{
			text: 'Cancel',
			click: function() {
				$(this).dialog('close');
			}
		},
		{
			text: 'Delete',
			click: function() {
				/* Close the dialog */
				$(this).dialog('close');

				/* Disable all the buttons */
				$('#data-form button').prop('disabled', true);
				
				/* Set the data verb to "delete" */
				$('#data-verb').val('delete');
					
				/*
				 * Get the form data and convert it to a POST-able format
				 * We only need the log ID, activity ID, data verb, and token from the form,
				 * so we'll build the array by hand instead of using serializeArray() 
				 */
				formData = [
					{"name": "log-id", "value": $('#log-id').val()},
					{"name": "activity-id", "value": $('#activity-id').val()},
					{"name": "data-verb", "value": $('#data-verb').val()},
					{"name": "token", "value": $('#token').val()}
				];
				
				// Submit the data to the handler
				$.post('/handlers/data_handler.php', formData, function(data) {
					// Convert the JSON string to a JavaScript object
					var result = JSON.parse(data);
					
					if (result.status === 'error') {
						// Display the error
						$('#form-error').html(result.message).css('display', 'inline-block');
			
						// Enable all the buttons
						$('#data-form button').prop('disabled', false);
			
					} else {
						// Display the success message
						$('#form-message').html(result.message).css('display', 'inline-block');
			
						// Return to the home page after 1 second
						window.setTimeout("window.location='/your_account.php'", 1000);            
					}
				});
			}
		}
	]
});

/*
 * ========================================== *
 * [COMMON] Page Event Handlers and Functions *
 * ========================================== *
 */
/* [Function] Display the Activity Log */
function displayActivityLog(log) {
	// Header
	$('.activity-log').html('<div id="activity-log-header" class="activity activity-log-header" style="background: #9bdc94;">');
	$('#activity-log-header').append('<div class="activity-item" style="max-width: 9em;">Ticker</div>');
	$('#activity-log-header').append('<div class="activity-item">Note Titles</div>');
	$('#activity-log-header').append('<div class="activity-item">Edit</div>');
	$('.activity-log').append('</div>');
	
	// Item Specification
	$.each(log, function(index, activity) {
		$('.activity-log').append('<div id="activity' + activity.activity_id + '" class="activity">');
		$('#activity' + activity.activity_id).append('<div class="activity-item" style="max-width: 9em;">' + activity.note_ticker + '</div>');
		$('#activity' + activity.activity_id).append('<div class="activity-item"><input id="activity-' + activity.activity_id + '" class="data-edit-button" type="submit" value="'+activity.note_title +'"></div>');
		
		/* Old (Inactive) */
		// $('#activity' + activity.activity_id).append('<div class="activity-item"> + activity.note_title + '</div>');

		$('#activity' + activity.activity_id).append('<div class="activity-item"><input id="activity-' + activity.activity_id + '" class="data-edit-button" type="image" src="images/footpower/pencil.png" alt="Pencil icon; click to edit this activity"></div>');
		$('.activity-log').append('</div>');
	});
}

// Note to self: "(e)" is short for event