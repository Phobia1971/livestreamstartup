<?php

	// Class for parsing the URI 
	require_once("../classes/class.Authentication.php");

	class TParseURI {
		private $ControllerVars;

		function __construct($uri) {
			// Authentication class for checking if someone is logged in, etc.
			$this->Authentication 	= new TAuthentication();
			$this->doAuthentication();
			// !^/([^/]+)!imsx
			if (preg_match_all('/([a-zA-Z0-9]+)/ixsm', $uri, $pmatches)) {
				$this->pmatches = $pmatches[0];
				$this->className = array_shift($this->pmatches);
			} else {
				$this->className = 'homepage';
			}

			if (strlen($this->className) > 32) {
				// TODO : Logging Message
				die("Unexpected error.");
			}

			$this->className = preg_replace('![^a-z0-9]!imsx', '', $this->className);

			// At this point, $className is sanitized
			if (file_exists("../classes/pages/class.".$this->className.".php")) {
				require_once("../classes/pages/class.".$this->className.".php");
				TGeneralPageClass::$_logged_in = $this->ControllerVars['loggedin'];
				$this->pageClass = new TPageClass($this->className);
				$this->runMethod();				
			} else {
				die("Page not found. "."../classes/pages/class.".$this->className.".php");
			}
		}

		function getPageName() {
			return $this->pageName;
		}

		private function runMethod()
		{			
			if(isset($this->pmatches[0])) 
			{
				$method = array_shift($this->pmatches);
				if(method_exists($this->pageClass, $method))
				{
					$this->pageClass->$method($this->pmatches);
				} else {
					$this->pageClass->init();
				}
			} else {
				$this->pageClass->init();
			}
		}

		function doAuthentication() {

			if (isset($_POST["submit"]) && $_POST['submit'] == 'Submit') {
				// They submitted the login form

				if ($this->Authentication->checkUserPass()) {
					$ControllerVars['loggedin'] = true;
					$this->Authentication->successfulLogin();
				} else {
					$ControllerVars['loggedin'] = false;
					$this->Authentication->failedLogin();
				} 
			}

			// Assume not logged in
			$ControllerVars['loggedin'] = 0;

			if ($this->Authentication->isAuthorized()) {
				// Logged in
				$ControllerVars['loggedin'] = true;
			} else {
				// Not Logged in

			}

			if ($ControllerVars['loggedin'] == false) {
				// Not logged in
			}

			// At this point we know if the user is logged in
		}
	}

