<?php

	class TAuthentication {
		function __construct() {
			// Constructor
			if (isset($_GET['logout']) && $_GET['logout'] == 1) {
				Tsession::set('loggedin', 0);
			}

			if (Tsession::get('loggedin') == 1) {
				$this->isAuthorized = 1;
			} else {
				$this->isAuthorized = 0;
			}
		}

		function isAuthorized($username = Null, $password = Null) {
			// Determine if someone has authorization
			return $this->isAuthorized;
		}

		function checkUserPass() {
			if ($_POST['username'] == 'Carl' && $_POST['password'] == '123') {
				return 1;
			} else {
				return 0;
			}
		}

		function successfulLogin() {
			Tsession::set('loggedin', 1);
		}

		function failedLogin() {
			Tsession::set('loggedin', 0);
		}

		// Functions related to creating new users below.

		function createUser($username, $password) {
			$sqlQuery = "insert into user (username, password) values ('".$username."', '".$password."')";
			$this->Logging->log($sqlQuery);
		}


	}
