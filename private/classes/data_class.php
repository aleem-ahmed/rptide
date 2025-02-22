<?php
/* Data object definition */
class Data {
	/* This private property stores the MySQLi database object. A reference to the current MySQLi object is passed to the constructor when a data item object is created from the class. (This is jsut a copy of the mysqli object) */
	private $_mysqli;

	/* When an object is created from this class, use the following constructor to check that the passed MySQLi object exists. If it does not exist, create it. */
	public function __construct($mysqli=NULL) {
		// Does the $mysqli object exist?
		if (is_object($mysqli)) {
			// If so, store it in the class property
			$this->_mysqli = $mysqli;

		} else {
			// Otherwise, create the MySQLi object
			$this->_mysqli = new MySQLi(HOST, USER, PASSWORD, DATABASE);

			// Check for an error
			if ($this->_mysqli->connect_error) {
				echo 'Connection Failed! Error #' . $this->_mysqli->connect_errno . ': '. $this->_mysqli->connect_error;
				exit(0);
			}
		}
	}

	/*
	 *==============*
	 * User Methods *
	 *==============*
	 */
	/* [CREATE] Adds an Item to the Database. First Check All Inputs -> Store */
	public function createData() {
		// Store the default status
		$server_results['status'] = 'success';
		$server_results['control'] = 'form';

		// Check if log-id field is empty
		if (!isset($_POST['log-id'])) {
			$server_results['status'] = 'error';
			$server_results['message'] = 'Error: Missing log ID';

		} else {
			// Take the content -> Sanitize
			$log_id = $_POST['log-id'];
			$log_id = filter_var($log_id, FILTER_SANITIZE_NUMBER_FLOAT);
			
			if (!$log_id) {
				$server_results['status'] = 'error';
				$server_results['message'] = 'Error: Invalid log ID';

			} else {
				// Check if note-ticker field is empty (required)
				if (empty($_POST['note-ticker'])) {
					$server_results['status'] = 'error';
					$server_results['message'] = 'Error: Missing note ticker'; 
			
				} else {
					// Take the content -> Sanitize -> Capitalize and put into variable
					$note_ticker = $_POST['note-ticker'];
					$note_ticker = filter_var($note_ticker, FILTER_SANITIZE_STRING);
					$note_ticker = strtoupper($note_ticker);

					// Check if note-title field is empty (required)
					if (empty($_POST['note-title'])) {
						$server_results['status'] = 'error';
						$server_results['message'] = 'Error: Missing note title'; 
			
					} else {
						// Take the content and put into variable -> Sanitize
						$note_title = $_POST['note-title'];
						$note_title = filter_var($note_title, FILTER_SANITIZE_STRING);

						// Check if note-cotent field is empty (required)
						if (empty($_POST['note-content'])) {
							$server_results['status'] = 'error';
							$server_results['message'] = 'Error: Missing note content';
						
						} else {
							// Take the content and put into variable
							$note_content = $_POST['note-content'];
							$note_content = filter_var($note_content, FILTER_SANITIZE_STRING);
						}
					}
				}
			} 
		}

		// Retrieve total_notes from DB
		$sql = "SELECT logs.total_notes FROM logs WHERE logs.log_id=?";

		$stmt = $this->_mysqli->prepare($sql);
		$stmt->bind_param("i", $log_id);
		$stmt->execute();

		// Results
		$result = $stmt->get_result();
		$row = mysqli_fetch_row($result);
		$total_notes = $row[0];

		// Check if total_notes is greater than or equal to 150 
		if ($total_notes >= 150) {
			$server_results['status'] = 'error';
			$server_results['message'] = 'You have created over 150 notes. You cannot create any more unless you delete notes.';
			
		} else {
			// Insert the data  
			if ($server_results['status'] === 'success') {
				// Create SQL template -> Prepare statement template -> Bind the parameters -> Execute 
				$sql = "INSERT INTO activities (log_id, note_ticker, note_title, note_content) VALUES (?, ?, ?, ?)";

				$stmt = $this->_mysqli->prepare($sql);
				$stmt->bind_param("isss", $log_id, $note_ticker, $note_title, $note_content);
				$stmt->execute();

				// Get the results 
				$result = $stmt->get_result();

				if ($this->_mysqli->errno === 0) {
					$server_results['message'] = 'Note saved successfully! Sending you back to the Notes..';

				} else {
					$server_results['status'] = 'error';
					$server_results['message'] = '[D1] MySQLi error #: ' . $this->_mysqli->errno . ': ' . $this->_mysqli->error;
				}
			}

			// Increase total_notes 
			$total_notes = $total_notes + 1;

			// Store new total_notes number in DB 
			$sql = "UPDATE logs SET total_notes=? WHERE log_id=?";

			$stmt = $this->_mysqli->prepare($sql);
			$stmt->bind_param("ii", $total_notes, $log_id);
			$stmt->execute();
		}

		// Create and then output the JSON data 
		$JSON_data = json_encode($server_results, JSON_HEX_APOS | JSON_HEX_QUOT);
		return $JSON_data;
	}

	/* [READ ALL] Returns All the Items From the Database */
	public function readAllData() {
		// Store the default status 
		$server_results['status'] = 'success';

		// Check the log-id field 
		if (!isset($_POST['log-id'])) {
			$server_results['status'] = 'error';
			$server_results['message'] = 'Error: Missing log ID';

		} else {
			$log_id = $_POST['log-id'];
			
			// Sanitize it to an integer 
			$log_id = filter_var($log_id, FILTER_SANITIZE_NUMBER_FLOAT);
		   if (!$log_id) {
				$server_results['status'] = 'error';
				$server_results['message'] = 'Error: Invalid log ID';
			}
		}

		if ($server_results['status'] === 'success') {
			// Create the SQL template 
			$sql = "SELECT * FROM activities WHERE log_id=? ORDER BY log_id DESC";

			// Prepare the statement template -> Bind the parameter -> Execute the prepared statement -> Get the results 
			$stmt = $this->_mysqli->prepare($sql);
			$stmt->bind_param("i", $log_id);
			$stmt->execute();

			// Get the result 
			$result = $stmt->get_result();

			if ($this->_mysqli->errno === 0) {
				// Get the query rows as an associative array 
				$rows = $result->fetch_all(MYSQLI_ASSOC);

				// Convert the array to JSON, then output it 
				$JSON_data = json_encode($rows, JSON_HEX_APOS | JSON_HEX_QUOT);
				return $JSON_data;

			} else {
				$server_results['status'] = 'error';
				$server_results['message'] = '[D2] MySQLi error #: ' . $this->_mysqli->errno . ': ' . $this->_mysqli->error;
			}
		}

		if ($server_results['status'] === 'error') {
			// Create and then output the JSON data 
			$JSON_data = json_encode($server_results, JSON_HEX_APOS | JSON_HEX_QUOT);
			return $JSON_data;
		}
	}

	/* [READ ITEM] Returns a Single Item From the Database */
	public function readDataItem() {
		// Store the default status 
		$server_results['status'] = 'success';

		// Check the log-id field 
		if (!isset($_POST['log-id'])) {
			$server_results['status'] = 'error';
			$server_results['message'] = 'Error: Missing log ID';

		} else {
			$log_id = $_POST['log-id'];

			// Sanitize it to an integer 
			$log_id = filter_var($log_id, FILTER_SANITIZE_NUMBER_FLOAT);
			if (!$log_id) {
				$server_results['status'] = 'error';
				$server_results['message'] = 'Error: Invalid log ID';

			} else {
				// Check the activity-id field 
				if (!isset($_POST['activity-id'])) {
					$server_results['status'] = 'error';
					$server_results['message'] = 'Error: Missing activity ID';

				} else {
					$activity_id = $_POST['activity-id'];

					// Sanitize it to an integer 
					$activity_id = filter_var($activity_id, FILTER_SANITIZE_NUMBER_FLOAT);
					if (!$activity_id) {
						$server_results['status'] = 'error';
						$server_results['message'] = 'Error: Invalid activity ID';
					}
				}
			}
		}

		// Are we good? 
		if ($server_results['status'] === 'success') {
			// Create SQL template -> Prepare statement template -> Bind the parameters -> Execute 
			$sql = "SELECT * FROM activities WHERE log_id=? AND activity_id=? LIMIT 1";

			$stmt = $this->_mysqli->prepare($sql);
			$stmt->bind_param("ii", $log_id, $activity_id);
			$stmt->execute();

			// Get the results 
			$result = $stmt->get_result();

			if ($this->_mysqli->errno === 0) {
				// Get the query row as an associative array -> Convert the array to JSON, then return it 
				$row = $result->fetch_all(MYSQLI_ASSOC);
				$JSON_data = json_encode($row, JSON_HEX_APOS | JSON_HEX_QUOT);
				return $JSON_data;

			} else {
				$server_results['status'] = 'error';
				$server_results['message'] = '[D3] MySQLi error #: ' . $this->_mysqli->errno . ': ' . $this->_mysqli->error;
			}

		}

		if ($server_results['status'] === 'error') {
			// Create and then return the JSON string 
			$JSON_data = json_encode($server_results, JSON_HEX_APOS | JSON_HEX_QUOT);
			return $JSON_data;
		}
	}

	/* [UPDATE] Updates a Data Item With New Values */ 
	public function updateData() {
		// Store the default status 
		$server_results['status'] = 'success';

		// Check the log-id field 
		if (!isset($_POST['log-id'])) {
			$server_results['status'] = 'error';
			$server_results['message'] = 'Error: Missing log ID';
			
		} else {
			$log_id = $_POST['log-id'];
	
			// Sanitize it to an integer 
			$log_id = filter_var($log_id, FILTER_SANITIZE_NUMBER_FLOAT);

			if (!$log_id) {
				$server_results['status'] = 'error';
				$server_results['message'] = 'Error: Invalid log ID';

			} else {
				// Check if acitivity-id field is empty (required) 
				if (!isset($_POST['activity-id'])) {
					$server_results['status'] = 'error';
					$server_results['message'] = 'Error: Missing activity ID';

				} else {
					$activity_id = $_POST['activity-id'];
	
					// Sanitize it to an integer 
					$activity_id = filter_var($activity_id, FILTER_SANITIZE_NUMBER_FLOAT);
				   if (!$activity_id) {
						$server_results['status'] = 'error';
						$server_results['message'] = 'Error: Invalid actvity ID';

					} else {
						// Check if note-ticker field is empty (required) 
						if (empty($_POST['note-ticker'])) {
							$server_results['status'] = 'error';
							$server_results['message'] = 'Error: Missing note ticker'; 
					
						} else {
							// Take the content -> capitalize and put into variable 
							$note_ticker = $_POST['note-ticker'];
							$note_ticker = strtoupper($note_ticker);
								
							// Check if note-title field is empty (required) 
							if (empty($_POST['note-title'])) {
								$server_results['status'] = 'error';
								$server_results['message'] = 'Error: Missing note title';
							
							} else {
								// Take the content and put into variable 
								$note_title = $_POST['note-title'];
							
								// Check if note-cotent field is empty (required) 
								if (empty($_POST['note-content'])) {
									$server_results['status'] = 'error';
									$server_results['message'] = 'Error: Missing note content'; 
								
								} else {
									// Take the content and put into variable 
									$note_content = $_POST['note-content'];		
								}
							}	
						}
					}
				}
			}
		}

		/* INSERT THE DATA */
		if ($server_results['status'] === 'success') {	
			// Create SQL template -> Prepare statement template -> Bind the parameters -> Execute 
			$sql = "UPDATE activities SET note_ticker=?, note_title=?, note_content=? WHERE log_id=? AND activity_id=?";

			$stmt = $this->_mysqli->prepare($sql);
			$stmt->bind_param("sssii", $note_ticker, $note_title, $note_content, $log_id, $activity_id);
			$stmt->execute();

			// Get the results 
			$result = $stmt->get_result();

			if ($this->_mysqli->errno === 0) {
				$server_results['message'] = 'Note updated successfully! Sending you back to the Notes..';
				
			} else {
				$server_results['status'] = 'error';
				$server_results['message'] = '[D4] MySQLi error #: ' . $this->_mysqli->errno . ': ' . $this->_mysqli->error;
			}
		}

		// Create and then return the JSON data 
		$JSON_data = json_encode($server_results, JSON_HEX_APOS | JSON_HEX_QUOT);
		return $JSON_data;
	}

	/* [DELETE] Removes an Item from the Database */
	public function deleteData() {
		// Store the default status 
		$server_results['status'] = 'success';

		// Check the log-id field 
		if (!isset($_POST['log-id'])) {
			$server_results['status'] = 'error';
			$server_results['message'] = 'Error: Missing log ID';

		} else {
			$log_id = $_POST['log-id'];
	
			// Sanitize it to an integer 
			$log_id = filter_var($log_id, FILTER_SANITIZE_NUMBER_FLOAT);
			
			if (!$log_id) {
				$server_results['status'] = 'error';
				$server_results['message'] = 'Error: Invalid log ID';

			} else {
				// Check the activity-id field 
				if (!isset($_POST['activity-id'])) {
					$server_results['status'] = 'error';
					$server_results['message'] = 'Error: Missing activity ID';

				} else {
					$activity_id = $_POST['activity-id'];
	
					// Sanitize it to an integer 
					$activity_id = filter_var($activity_id, FILTER_SANITIZE_NUMBER_FLOAT);
					
					if (!$activity_id) {
						$server_results['status'] = 'error';
						$server_results['message'] = 'Error: Invalid activity ID';
					}
				}
			}
		}

		// Are we good? 
		if ($server_results['status'] === 'success') {
			// Create SQL template -> Prepare statement template -> Bind the parameters -> Execute 
			$sql = "DELETE FROM activities WHERE log_id=? AND activity_id=?";

			$stmt = $this->_mysqli->prepare($sql);
			$stmt->bind_param("ii", $log_id, $activity_id);
			$stmt->execute();

			// Results 
			$result = $stmt->get_result();

			if ($this->_mysqli->errno === 0) {
				$server_results['message'] = 'Note deleted successfully! Sending you back to the Notes..';

			} else {
				$server_results['status'] = 'error';
				$server_results['message'] = '[D5] MySQLi error #: ' . $this->_mysqli->errno . ': ' . $this->_mysqli->error;
			}
		}

		// Retrieve total notes from DB 
		$sql = "SELECT logs.total_notes FROM logs INNER JOIN activities ON logs.log_id = activities.log_id WHERE logs.log_id=? LIMIT 1";

		$stmt = $this->_mysqli->prepare($sql);
		$stmt->bind_param("i", $log_id);
		$stmt->execute();

		// Results 
		$result = $stmt->get_result();
		$row = mysqli_fetch_row($result);		
		$total_notes = $row[0];

		// Reduce the total_notes 
		$total_notes = $total_notes - 1;

		// Store new total_notes number in DB 
		$sql = "UPDATE logs SET total_notes=? WHERE log_id=?";

		$stmt = $this->_mysqli->prepare($sql);
		$stmt->bind_param("ii", $total_notes, $log_id);
		$stmt->execute();

		// Create and then output the JSON data 
		$JSON_data = json_encode($server_results, JSON_HEX_APOS | JSON_HEX_QUOT);
		return $JSON_data;
	}
}
?> 