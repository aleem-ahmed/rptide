/*
 * ================ *
 * Initializer Code *
 * ================ *
 */
$(document).ready(function() {
	// Get the current filename and run the code for that file
	var currentURL = window.location.pathname;
	var currentFile = currentURL.substr(currentURL.lastIndexOf('/') + 1);
	
	switch (currentFile) {
		// Display the signed-in user's Activity Log
		case 'notes.php':
			readActivities();
			break;
			
		// Set up the Create Data form
		case 'create_data.php':
			initializeCreateDataForm();
			break;

		// Set up the Edit Data form
		case 'update_data.php':
			initializeUpdateDataForm();
			break;

		// Display the signed-in user's Activity Log	
		case 'your_account.php':
			readActivities();
			break;
	}
});