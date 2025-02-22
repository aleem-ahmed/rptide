<?php
	/* === GUIDE ===
	 * 1) Create a connection to the database
	 * 2) Include the functions needed to handle the data
	 * 3) Check all the inputs sent from the form
	 * 4) Check what the data-verb is
	 * 5) Run function accordingly 
	*/

	// Initialize the app
	include_once '../../private/common/initialization.php';
	
	// Include the Data class
	include_once '../../private/classes/data_class.php';
	
	// Initialize the results
	$server_results['status'] = 'success';
	$server_results['message'] = '';

	// Make sure a log ID was passed
	if (!isset($_POST['log-id'])) {
		$server_results['status'] = 'error';
		$server_results['message'] = 'Error: No log ID specified!';
	}

	// [DATA-VERB] Make sure a data verb was passed
	else if (!isset($_POST['data-verb'])) {
		$server_results['status'] = 'error';
		$server_results['message'] = 'Error: No data verb specified!';
	}
	
	// [TOKEN] Make sure a token value was passed
	else if (!isset($_POST['token'])) {
		$server_results['status'] = 'error';
		$server_results['message'] = 'Error: Invalid action!';
	}

	// Make sure the token is legit
	else if ($_SESSION['token'] !== $_POST['token']) {
		$server_results['status'] = 'error';
		$server_results['message'] = 'Timeout Error!<p>Please refresh the page and try again.';
	}

	// If we get this far, all is well, so go for it
	else {
		// Create a new Data object
		$data = new Data($mysqli);
		
		// [DATA-VERB] Pass the data-verb to the appropriate method
		switch ($_POST['data-verb']) {
			
			// Create a new data item
			case 'create':
				$server_results = json_decode($data->createData());
				break;

			// Read all the data items
			case 'read-all-data':
				$server_results = json_decode($data->readAllData());
				break;

			// Read one data item
			case 'read-data-item':
				$server_results = json_decode($data->readDataItem());
				break;

			// Update a data item
			case 'update':
				$server_results = json_decode($data->updateData());
				break;

			// Delete a data item
			case 'delete':
				$server_results = json_decode($data->deleteData());
				break;

			default:
				$server_results['status'] = 'error';
				$server_results['message'] = 'Error: Unknown data verb!';
		}
	}
	
	// Create and then output the JSON data
	$JSON_data = json_encode($server_results, JSON_HEX_APOS | JSON_HEX_QUOT);
	echo $JSON_data;
?>