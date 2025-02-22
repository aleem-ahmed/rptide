<?php
/* User Object Definition */
class User {
	//This private property stores the MySQLi database object. A reference to the current MySQLi object is passed to the constructor when a data item object is created from the class.
	private $_mysqli;

	/* When an object is created from this class, use this constructor to check that the passed MySQLi object exists. If it does not exist, create it. */
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
				echo 'Connection Failed! Error #' . $this->_mysqli->connect_errno . ': ' . $this->_mysqli->connect_error;
				exit(0);
			}
		}
	}

	/*
	 *==============*
	 * User Methods *
	 *==============*
	*/
	
	// [CREATE USER] User and send Verification //
	public function createUser() {
		// Store default status
		$server_results['status'] = 'success';

		/* IDENTITY PROCCESSING */
		// [USERNAME] Check if '#username' is empty
		if (empty($_POST['username'])) {
			$server_results['status'] = 'error';
			$server_results['control'] = 'username';
			$server_results['message'] = 'Email address missing.';
		
		} else {
			// "#username" -> Store -> "$username" -> Santize
			$username = $_POST['username'];
			$username = filter_var($username, FILTER_SANITIZE_EMAIL);
			
			// Check if "$username" is a valid email
			if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
				$server_results['status'] = 'error';
				$server_results['control'] = 'username';
				$server_results['message'] = 'That email address is not real. Please try again.';
			
			} else {
				// Check if "$username" is valid
				if (!$username) {
					$server_results['status'] = 'error';
					$server_results['control'] = 'username';
					$server_results['message'] = 'That email address is invalid. Please try again.';

				} else {
					// [FIRST NAME] Check if "#f-name" is empty
					if (empty($_POST['f-name'])) {
						$server_results['status'] = 'error';
						$server_results['control'] = 'f-name';
						$server_results['message'] = 'First name missing.';

					} else {
						// "#f-name" -> Store -> "$f_name" -> Santize -> Lowercase
						$f_name = $_POST['f-name'];
						$f_name = filter_var($f_name, FILTER_SANITIZE_STRING);
						$f_name = strtoupper($f_name);

						// Check if "$f_name" is valid
						if (!$f_name) {
							$server_results['status'] = 'error';
							$server_results['control'] = 'f-name';
							$server_results['message'] = 'That first name is invalid. Please try again.';
						
						} else {
							// [LAST NAME] Check if "#l-name" is empty 
							if (empty($_POST['l-name'])) {
								$server_results['status'] = 'error';
								$server_results['control'] = 'l-name';
								$server_results['message'] = 'Last name missing.';

							} else {
								// "#l-name" -> Store -> "$l_name" -> Sanitize -> Lowercase
								$l_name = $_POST['l-name'];
								$l_name = filter_var($l_name, FILTER_SANITIZE_STRING);
								$f_name = strtoupper($f_name);

								// Check if '$l_name' is valid
								if (!$l_name) {
									$server_results['status'] = 'error';
									$server_results['control'] = 'l-name';
									$server_results['message'] = 'That last name is invalid. Please try again.';
								
								} else {
									// [DOB] Check if "#l-name" is empty
									if (empty($_POST['dob'])) {
										$server_results['status'] = 'error';
										$server_results['control'] = 'dob';
										$server_results['message'] = 'Date of birth missing.';
									
									} else {
										// "#l-name" -> store -> "$l_name" -> santize
										$dob = $_POST['dob'];
										$dob = filter_var($dob, FILTER_SANITIZE_STRING);

										// Validate a proper date was passed
										if (!preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|1[0-9]|2[0-9]|3[0-1])$/', $dob)) {
											$server_results['status'] = 'error';
											$server_results['control'] = 'dob';
											$server_results['message'] = 'That DOB is not proper. Please try again.';
										
										} else {
											// Check if "$dob" is valid
											if (!$dob) {
												$server_results['status'] = 'error';
												$server_results['control'] = 'dob';
												$server_results['message'] = 'That DOB is invalid. Please try again.';
											
											} else {
												// "#m-name" -> Store -> "$m_name" -> Sanitize -> Lowercase
												$m_name = $_POST['m-name'];
												$m_name = filter_var($m_name, FILTER_SANITIZE_STRING);
												$m_name = strtoupper($m_name);
											}
										}
									}
								}
							}
						}
					}

					/* CHECK IF USERNAME IS NOT TAKEN PROCCESSING */
					// [NO ISSUES] Query to check if '$username' exists already
					$sql = "SELECT * FROM users WHERE username=?";

					$stmt = $this->_mysqli->prepare($sql);
					$stmt->bind_param("s", $username);
					$stmt->execute();
					$result = $stmt->get_result();

					// If 'username' is taken, num_rows will be greater than 0
					if ($result->num_rows > 0) {
						$server_results['status'] = 'error';
						$server_results['control'] = 'username';
						$server_results['message'] = 'That email address is already being used. Please try again.';
					}
				}
			}

		}

		/* PASSWORD PROCCESSING */
		// If the server result is success start password proccessing
		if ($server_results['status'] === 'success') {

			// [PASSWORD] Check if '#password' is empty
			if (!isset($_POST['password'])) {
				$server_results['status'] = 'error';
				$server_results['control'] = 'password';
				$server_results['message'] = 'The password is missing. Please try again.';

			} else {
				// "#password" -> store -> "$password" -> santize
				$password = $_POST['password'];
				$password = filter_var($password, FILTER_SANITIZE_STRING);

				// Check if "$password" is valid
				if (!$password) {
					$server_results['status'] = 'error';
					$server_results['control'] = 'password';
					$server_results['message'] = 'The password you used was invalid. Please try again.';
				}

				// Check password length
				else if (strlen($password) < 8 ) {
					$server_results['status'] = 'error';
					$server_results['control'] = 'password';
					$server_results['message'] = 'The password must be at least 8 characters long. Please try again.';

				} else {
					// Hash the password
					$password = password_hash($password, PASSWORD_DEFAULT);
				}
			}
		}
		
		/* USER CREATION PROCCESSING */
		// If the server result is success start user creation proccessing
		if ($server_results['status'] === 'success') {
			// Create a random, secure, 32-character verification code
			$ver_code = bin2hex(openssl_random_pseudo_bytes(16));

			// Send the verification email
			$send_to = $username;
			$subject = 'Please verify your RpTide account';
			$header = 'From: RpTide <mail@rptide.com>' . "\r\n" . 'Content-type: text/html;charset=UTF-8';
			$body = <<<BODY
				<body>
					<h2>You have a new account at RpTide</h2>
					<p>Your username is the email address you provided: $username</p>
					<p>Please activate your account by clicking the link below.</p>
					<a href="http://www.rptide.com/verify_user.php?vercode=$ver_code&username=$username">
						<h2>Verify Your Account</h2>
					</a>
					<p>If you did not create a RpTide account, you can safely delete this message.</p>
					<p>
						Thanks
						<br>
						RpTide.com
					</p>
				</body>
			BODY;
				
			// Send the email
			$mail_sent = mail($send_to, $subject, $body, $header);

			if ($mail_sent) {
				// Create and prepare the SQL template
				$sql = "INSERT INTO users (username, password, verification_code, f_name, m_name, l_name, dob) VALUES (?, ?, ?, ?, ?, ?, ?)";

				$stmt = $this->_mysqli->prepare($sql);
				$stmt->bind_param("sssssss", $username, $password, $ver_code, $f_name, $m_name, $l_name, $dob);
				$stmt->execute();
				$result = $stmt->get_result();

				if ($this->_mysqli->errno === 0) {
					$server_results['control'] = 'form';
					$server_results['message'] = 'You\'re in! We\'ve sent you a verification email.<br>Be sure to click the link in that email to verify your account.';
				
				} else {
					$server_results['status'] = 'error';
					$server_results['control'] = 'form';
					$server_results['message'] = 'MySQLi error #: ' . $this->_mysqli->errno . ': ' . $this->_mysqli->error;
				}

			} else {
				$server_results['status'] = 'error';
				$server_results['control'] = 'form';
				$server_results['message'] = 'Error! The verification email could not be sent, for some reason. Please try again.';
			}
		}

		// Create and then return the JSON data
		$JSON_data = json_encode($server_results, JSON_HEX_APOS | JSON_HEX_QUOT);
		return $JSON_data;
	}

	// [VERIFY] Verifies a new user account //
	public function verifyUser() {
		// Store the default status
		$server_results['status'] = 'success';

		// Get the query string parameters
		$ver_code = $_GET['vercode'];
		$username = $_GET['username'];

		// Sanitize them
		$ver_code = filter_var($ver_code, FILTER_SANITIZE_STRING);
		$username = filter_var($username, FILTER_SANITIZE_EMAIL);

		// Prepare the SQL SELECT statement
		$sql = "SELECT * FROM users WHERE verification_code=? AND username=? AND verified=0 LIMIT 1";

		$stmt = $this->_mysqli->prepare($sql);
		$stmt->bind_param("ss", $ver_code, $username);
		$stmt->execute();
		$result = $stmt->get_result();

		// Was there an error?
		if ($this->_mysqli->errno !== 0) {
			$server_results['status'] = 'error';
			$server_results['control'] = 'form';
			$server_results['message'] = 'MySQLi error #: ' . $this->_mysqli->errno . ': ' . $this->_mysqli->error;
		}

		// Otherwise, if a row is returned, it means the user can be verified
		else if ($result->num_rows === 1) {
			// Set the success message
			$server_results['message'] = 'Your account is now verified.<p>You\'re signed in, so go ahead and <a href="create_data.php">create a note.</a>';

			// Sign in the user
			$_SESSION['username'] = $username;

			// Get the user's ID
			$row = $result->fetch_all(MYSQLI_ASSOC);
			$user_id = $row[0]['user_id'];
			

			// Set the user's verified flag in the database
			$sql = "UPDATE users SET verified=1 WHERE username=?";

			$stmt = $this->_mysqli->prepare($sql);
			$stmt->bind_param("s", $username);
			$stmt->execute();
			$result = $stmt->get_result();

			// Create a master data record (in this case, an activity log) for the user
			$sql = "INSERT INTO logs (user_id) VALUES (?)";

			$stmt = $this->_mysqli->prepare($sql);
			$stmt->bind_param("i", $user_id);
			$stmt->execute();
			$result = $stmt->get_result();

			// Get the user's log ID
			$sql = "SELECT * FROM logs WHERE user_id=? LIMIT 1";

			$stmt = $this->_mysqli->prepare($sql);
			$stmt->bind_param("i", $user_id);
			$stmt->execute();
			$result = $stmt->get_result();
			$row = $result->fetch_all(MYSQLI_ASSOC);
			$log_id = $row[0]['log_id'];
			$_SESSION['log_id'] = $log_id;

		} else {
			// Handle the case where the user is already verified -> Prepare the SQL SELECT statement
			$sql = "SELECT username FROM users WHERE verification_code=? AND username=? AND verified=1";

			$stmt = $this->_mysqli->prepare($sql);
			$stmt->bind_param("ss", $ver_code, $username);
			$stmt->execute();
			$result = $stmt->get_result();

			// Was there an error?
			if ($this->_mysqli->errno === 0) {
				$server_results['status'] = 'error';
				$server_results['control'] = 'form';
				$server_results['message'] = 'MySQLi error #: ' . $this->_mysqli->errno . ': ' . $this->_mysqli->error;
			}

			// Otherwise, if a row is returned, it means the user is already verified
			else if ($result->num_rows > 0) {
				$server_results['status'] = 'error';
				$server_results['control'] = 'form';
				$server_results['message'] = 'Yo, you\'re already verified.<p>Perhaps you\'d like to <a href="create_data.php">log a walk, run, or ride</a>?';
			
			} else {
				$server_results['status'] = 'error';
				$server_results['control'] = 'form';
				$server_results['message'] = 'Yikes. A database error occurred. These things happen.';
			}
		}

		// Create and then return the JSON data
		$JSON_data = json_encode($server_results, JSON_HEX_APOS | JSON_HEX_QUOT);
		return $JSON_data;
	}

	// [SIGN-IN] Signs a user into their account //
	public function signInUser() {
		// Store the default status
		$server_results['status'] = 'success';

		// Was the username sent?
		if (!isset($_POST['username'])) {
			$server_results['status'] = 'error';
			$server_results['control'] = 'username';
			$server_results['message'] = 'Doh! You need to enter your email address.';

		} else {
			// Sanitize it
			$username = $_POST['username'];
			$username = filter_var($username, FILTER_SANITIZE_EMAIL);

			if (!$username) {
				$server_results['status'] = 'error';
				$server_results['control'] = 'username';
				$server_results['message'] = 'Well, it appears that email address isn\'t valid. Please try again.';

			} else {
				// Make sure the username exists in the database
				$sql = "SELECT * FROM users WHERE username=? LIMIT 1";

				$stmt = $this->_mysqli->prepare($sql);
				$stmt->bind_param("s", $username);
				$stmt->execute();
				$result = $stmt->get_result();

				// If the username doesn't exist, num_rows will be 0
				if ($result->num_rows === 0) {
					$server_results['status'] = 'error';
					$server_results['control'] = 'username';
					$server_results['message'] = 'Sorry, but that email address isn’t associated with an account. Please try again.';

				} else {
					// Check the password. Was the password sent?
					if (!isset($_POST['password'])) {
						$server_results['status'] = 'error';
						$server_results['control'] = 'password';
						$server_results['message'] = 'That\'s weird: the password is missing. Please try again.';

					} else {
						// Sanitize it
						$password = $_POST['password'];
						$password = filter_var($password, FILTER_SANITIZE_STRING);

						// Is the password still valid?
						if (!$password) {
							$server_results['status'] = 'error';
							$server_results['control'] = 'password';
							$server_results['message'] = 'Sorry, but the password you used was invalid. Please try again.';

						} else {
							// Get the user data
							$row = $result->fetch_all(MYSQLI_ASSOC);

							// Confirm the password
							if (!password_verify($password, $row[0]['password'])) {
								$server_results['status'] = 'error';
								$server_results['control'] = 'password';
								$server_results['message'] = 'Sorry, but the password you used was incorrect. Please try again.';

							} else {
								// Sign in the user
								$_SESSION['username'] = $username;
								$user_id = $row[0]['user_id'];

								// Get the user's log ID
								$sql = "SELECT * FROM logs WHERE user_id=?";

								$stmt = $this->_mysqli->prepare($sql);
								$stmt->bind_param("i", $user_id);
								$stmt->execute();
								$result = $stmt->get_result();
								$row = $result->fetch_all(MYSQLI_ASSOC);
								$log_id = $row[0]['log_id'];
								$_SESSION['log_id'] = $log_id;
							}
						}
					}
				}
			}
		}

		// Create and then return the JSON data
		$JSON_data = json_encode($server_results, JSON_HEX_APOS | JSON_HEX_QUOT);
		return $JSON_data;
	}

	// [SEND-PASSWORD-RESET] Send email //
	public function sendPasswordReset() {
		// Store the default status
		$server_results['status'] = 'success';

		// Was the email address entered?
		if (!isset($_POST['username'])) {
			$server_results['status'] = 'error';
			$server_results['control'] = 'username';
			$server_results['message'] = 'Um, you really do need to enter your email address.';

		} else {
			// Sanitize it
			$username = $_POST['username'];
			$username = filter_var($username, FILTER_SANITIZE_EMAIL);

			if (!$username) {
				$server_results['status'] = 'error';
				$server_results['control'] = 'username';
				$server_results['message'] = 'Hmmm. It looks like that email address isn\'t valid. Please try again.';

			} else {
				// Make sure the email address exists in the database
				$sql = "SELECT * FROM users WHERE username=? LIMIT 1";

				$stmt = $this->_mysqli->prepare($sql);
				$stmt->bind_param("s", $username);
				$stmt->execute();
				$result = $stmt->get_result();

				// If the email doesn't exist, num_rows will be 0
				if ($result->num_rows === 0) {
					$server_results['status'] = 'error';
					$server_results['control'] = 'username';
					$server_results['message'] = 'Sorry, but that email address isn’t associated with an account. Please try again.';
				
				} else {
					// Get the user's verification code
					$row = $result->fetch_all(MYSQLI_ASSOC);
					$ver_code = $row[0]['verification_code'];
				}
			}
		}

		// If we're still good, it's time to get the reset started
		if ($server_results['status'] === 'success') {

			// Send the password reset email
			$send_to = $username;
			$subject = 'Reset your RpTide password';
			$header = 'From: RpTide <mail@rptide.com>' . "\r\n" .
					  'Content-Type: text/plain';
			$body = <<<BODY
				You're receiving this message because you requested a password reset for your RpTide account.
				Please click the link below to reset your password.

				http://www.rptide.com/reset_password.php?vercode=$ver_code&username=$username

				If you do not have a RpTide account, you can safely delete this message.

				Thanks
				RpTide.com
			BODY;

			if (mail($send_to, $subject, $body, $header)) {
				// Unset the user's verified flag in the database
				$sql = "UPDATE users SET verified=0 WHERE username=?";

				$stmt = $this->_mysqli->prepare($sql);
				$stmt->bind_param("s", $username);
				$stmt->execute();
				$result = $stmt->get_result();

				if ($this->_mysqli->errno === 0) {
					$server_results['control'] = 'form';
					$server_results['message'] = 'Okay, we\'ve sent you the reset email.<br>Be sure to click the link in that email to reset your password.';
				
				} else {
					$server_results['status'] = 'error';
					$server_results['control'] = 'form';
					$server_results['message'] = 'MySQLi error #: ' . $this->_mysqli->errno . ': ' . $this->_mysqli->error;
				}
			
			} else {
				$server_results['status'] = 'error';
				$server_results['control'] = 'form';
				$server_results['message'] = 'Error! The reset email could not be sent, for some reason. Please try again.';
			}
		}

		// Create and then return the JSON data
		$JSON_data = json_encode($server_results, JSON_HEX_APOS | JSON_HEX_QUOT);
		return $JSON_data;
	}

	// [RESET-PASSWORD] Updates the User's Password //
	public function resetPassword() {
		// Store the default status
		$server_results['status'] = 'success';

		// Get the form data
		$username = $_POST['username'];
		$ver_code = $_POST['vercode'];
		$password = $_POST['password'];

		// Sanitize the username and verification code, just to be safe
		$username = filter_var($username, FILTER_SANITIZE_EMAIL);
		$ver_code = filter_var($ver_code, FILTER_SANITIZE_STRING);

		/* VERIFY THE USER */
		// Prepare the SQL SELECT statement
		$sql = "SELECT * FROM users WHERE username=? AND verification_code=? AND verified=0";

		$stmt = $this->_mysqli->prepare($sql);
		$stmt->bind_param("ss", $username, $ver_code);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_all(MYSQLI_ASSOC);

		// Check for errors
		if ($this->_mysqli->errno !== 0) {
			$server_results['status'] = 'error';
			$server_results['control'] = 'form';
			$server_results['message'] = 'MySQLi error #: ' . $this->_mysqli->errno . ': ' . $this->_mysqli->error;
		}

		// If a row is returned, it means the user is verified so the password can be reset
		else if ($result->num_rows > 0) {
			//Was the password sent?
			if (!isset($password)) {
				$server_results['status'] = 'error';
				$server_results['control'] = 'password';
				$server_results['message'] = 'That\'s weird: the password is missing. Please try again.';

			} else {
				// Sanitize it
				$password = filter_var($password, FILTER_SANITIZE_STRING);

				// Is the password still valid?
				if (!$password) {
					$server_results['status'] = 'error';
					$server_results['control'] = 'password';
					$server_results['message'] = 'Sorry, but the password you used was invalid. Please try again.';
				}

				// Is the password long enough?
				else if (strlen($password) < 8 ) {
					$server_results['status'] = 'error';
					$server_results['control'] = 'password';
					$server_results['message'] = 'Sorry, but the password must be at least 8 characters long. Please try again.';
				
				} else {
					// If all's well, hash the password
					$password = password_hash($password, PASSWORD_DEFAULT);
				}
			}

		} else {
			$server_results['status'] = 'error';
			$server_results['control'] = 'form';
			$server_results['message'] = 'Oh, man, a database error occurred! Please try again. ';
		}

		// If we're still good, it's time to reset the password and re-verify the user
		if ($server_results['status'] === 'success') {
			// Get the user's ID
			$user_id = $row[0]['user_id'];

			// Set the user's password and verified flag in the database
			$sql = "UPDATE users SET password=?, verified=1 WHERE username=?";

			$stmt = $this->_mysqli->prepare($sql);
			$stmt->bind_param("ss", $password, $username);
			$stmt->execute();
			$result = $stmt->get_result();

			// Was there an error?
			if ($this->_mysqli->errno === 0) {
				// If not, sign in the user
				$_SESSION['username'] = $username;

				// Get the user's log ID
				$sql = "SELECT * FROM logs WHERE user_id=?";

				$stmt = $this->_mysqli->prepare($sql);
				$stmt->bind_param("i", $user_id);
				$stmt->execute();
				$result = $stmt->get_result();

				// Set the log_id and  variable
				$row = $result->fetch_all(MYSQLI_ASSOC);
				$log_id = $row[0]['log_id'];
				$_SESSION['log_id'] = $log_id;

			} else {
				$server_results['status'] = 'error';
				$server_results['control'] = 'form';
				$server_results['message'] = 'Yikes. A database error occurred. Please try again.';
			}
		}

		// Create and then return the JSON data
		$JSON_data = json_encode($server_results, JSON_HEX_APOS | JSON_HEX_QUOT);
		return $JSON_data;
	}

	// [DELETE] User //
	public function deleteUser() {
		// Store the default status
		$server_results['status'] = 'success';

		// Get the username and password
		$username = $_POST['username'];
		$password = $_POST['password'];

		// Sanitize the username, just to be safe
		$username = filter_var($username, FILTER_SANITIZE_EMAIL);

		// Make sure the username exists in the database
		$sql = "SELECT * FROM users WHERE username=? LIMIT 1";

		$stmt = $this->_mysqli->prepare($sql);
		$stmt->bind_param("s", $username);
		$stmt->execute();
		$result = $stmt->get_result();

		// Get the user's ID
		$row = $result->fetch_all(MYSQLI_ASSOC);
		$user_id = $row[0]['user_id'];

		// If the username doesn't exist, num_rows will be 0
		if ($result->num_rows === 0) {
			$server_results['status'] = 'error';
			$server_results['control'] = 'form';
			$server_results['message'] = 'Sorry, but we can\'t find your account. Please try again.';

		} else {
			// Now check the password -> Was the password sent?
			if (!isset($_POST['password'])) {
				$server_results['status'] = 'error';
				$server_results['control'] = 'password';
				$server_results['message'] = 'That\'s weird: the password is missing. Please try again.';

			} else {
				// Sanitize it
				$password = filter_var($password, FILTER_SANITIZE_STRING);

				// Is the password still valid?
				if (!$password) {
					$server_results['status'] = 'error';
					$server_results['control'] = 'password';
					$server_results['message'] = 'Sorry, but the password you used was invalid. Please try again.';

				} else {
					// Confirm the password
					if (!password_verify($password, $row[0]['password'])) {
						$server_results['status'] = 'error';
						$server_results['control'] = 'password';
						$server_results['message'] = 'Sorry, but the password you used was incorrect. Please try again.';

					} else {
						/* DELETE THE USER */
						// Delete the user's account from table "users"
						$sql = "DELETE FROM users WHERE username=? LIMIT 1";

						$stmt = $this->_mysqli->prepare($sql);
						$stmt->bind_param("s", $username);
						$stmt->execute();
						$result = $stmt->get_result();

						/* Was there an error? */
						if ($this->_mysqli->errno !== 0) {
							$server_results['status'] = 'error';
							$server_results['control'] = 'form';
							$server_results['message'] = 'MySQLi error #: ' . $this->_mysqli->errno . ': ' . $this->_mysqli->error;

						} else {
							// Get the user's "log_id" from table "logs"
							$sql = "SELECT * FROM logs WHERE user_id=? LIMIT 1";

							$stmt = $this->_mysqli->prepare($sql);
							$stmt->bind_param("i", $user_id);
							$stmt->execute();
							$result = $stmt->get_result();
							$row = $result->fetch_all(MYSQLI_ASSOC);
							$log_id = $row[0]['log_id'];

						   // Delete the user's activities from table "activities"
							$sql = "DELETE FROM activities WHERE log_id=?";

							$stmt = $this->_mysqli->prepare($sql);
							$stmt->bind_param("i", $log_id);
							$stmt->execute();
							$result = $stmt->get_result();

							// Was there an error?
							if ($this->_mysqli->errno !== 0) {
								$server_results['status'] = 'error';
								$server_results['control'] = 'form';
								$server_results['message'] = 'MySQLi error #: ' . $this->_mysqli->errno . ': ' . $this->_mysqli->error;

							} else {
								// Delete the user's master data record (log) from table "logs"
								$sql = "DELETE FROM logs WHERE log_id=?	LIMIT 1";

								$stmt = $this->_mysqli->prepare($sql);
								$stmt->bind_param("i", $log_id);
								$stmt->execute();
								$result = $stmt->get_result();

								// Was there an error?
								if ($this->_mysqli->errno !== 0) {
									$server_results['status'] = 'error';
									$server_results['control'] = 'form';
									$server_results['message'] = 'MySQLi error #: ' . $this->_mysqli->errno . ': ' . $this->_mysqli->error;

								} else {
									// Free up all the session variables
									session_unset();
								}
							}
						}
					}
				}
			}
		}
		
		// Create and then return the JSON data
		$JSON_data = json_encode($server_results, JSON_HEX_APOS | JSON_HEX_QUOT);
		return $JSON_data;
	}
}
?>