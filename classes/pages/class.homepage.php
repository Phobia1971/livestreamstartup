<?php

	class TPageClass extends TGeneralPageClass {
		function init() {
			$this->createContent();

			$this->assignPlaceholder('copyright');
			$this->assignPlaceholder('navbar');

			$this->showContent();

			$this->database = new TDatabase();
		}

		function handleFormSubmission() {
		}
	}







