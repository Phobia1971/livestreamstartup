<?php

	class TPageClass extends TGeneralPageClass {


		function init() {

			$this->createContent();
			
			$login = (isset($this->login)?$this->login:'login');
			$this->assignPlaceholder("main_wrapper");

			$placeholders = array('copyright', 'logo', 'navbar','left_choice','right_choice', $login, 'bottom_nav', 'footer');
			$this->assignPlaceholder($placeholders);
			//code to build the left and right frame goes belowe
			$this->assignPlaceholder(array("content_left", "content_right"));
			
			//show builded page
			$this->showContent();
			
		}

		function handleFormSubmission() {
		}
	}







