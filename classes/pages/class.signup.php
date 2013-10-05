<?php

	class TPageClass extends TGeneralPageClass {

		function init() {

			$this->createContent();
			
			$this->assignPlaceholder("main_wrapper");

			$placeholders = array('copyright','navbar', 'signup', 'login', 'bottom_nav', 'footer');
			$this->assignPlaceholder($placeholders);

			$this->showContent();

			$this->database = new TDatabase();
		}

		function handleFormSubmission() {
			$sqlQuery = "insert into users (username, password) values ('".$this->safePost['username']."', '".$this->safePost['password']."')";

			$this->database->singleRowQuery($sqlQuery);

			echo "This is the form handler.\n<br />";
		}
	}
