<?php

	require_once("../classes/class.Sanitization.php");
	require_once("../classes/class.Logging.php");

	class TGeneralPageClass {
		static $_logged_in = false;
		protected $flags;

		function __construct($className) {
			$this->flags['content_exists'] = 0;
			$this->className = $className;

			$this->Sanitization = new TSanitization();
			$this->Logging		= new TLogging();

			if (isset($_POST['submit'])) {

				// Sanitize incoming POST data
				foreach($_POST as $key=>$value) {
					$this->safePost[$key] = $this->Sanitization->alphaNumeric($value);
				}
			}


			$this->flags['content_exists'] = 0;

			if (isset($_POST['submit'])) {
				$this->handleFormSubmission();

			}


		}

		function login()
		{
			$this->login = "loggedin";
			$this->init();
			
		}

		function createContent() {
			
			if (preg_match('|^/js/(.*?)$|', URI, $jmatches)) {
				$this->Logging->log("preg_match js: ".$jmatches[1]);
				// If a CSS file is being requested
				$filename = "../templates/js/".$jmatches[1];

				if (file_exists($filename)) {
					$this->Logging->log("Javascript file: $filename found.");
					$this->content = file_get_contents($filename);
					$placeholders = $this->findPlaceholders($this->content);
					foreach ($placeholders as $placeholder) {
						$placeholder = trim($placeholder,"{}");
						$filename = "../templates/js/js_content/".$placeholder.".js";
						if (file_exists($filename)) {
							$newcontent = file_get_contents($filename);
							$this->content = str_replace('{'.$placeholder.'}', $newcontent, $this->content);
						}
					}
				}

			} elseif (preg_match('!^/css/(.*?)$!imsx', URI, $pmatches)) {
				$this->Logging->log("preg_match css: ".$pmatches[1]);
				// If a CSS file is being requested
				$filename = "../templates/css/".$pmatches[1];

				if (file_exists($filename)) {
					$this->Logging->log("Css file: $filename found.");
					$this->content = file_get_contents($filename);
					$placeholders = $this->findPlaceholders($this->content);
					foreach ($placeholders as $placeholder) {
						$placeholder = trim($placeholder,"{}");
						$filename = "../templates/divcss/".$placeholder.".css";
						if (file_exists($filename)) {
							$newcontent = file_get_contents($filename);
							$this->content = str_replace('{'.$placeholder.'}', $newcontent, $this->content);
						}
					}
				}

			} else {
				// If a CSS file is not being requested

				$this->className = $this->className;

				$filename	= "../templates/pages/".$this->className.".html";

				if (file_exists($filename)) {

					$content = file_get_contents($filename);

					// Any placeholders need to change here.
					$this->content = str_replace('{submit_url}', '/'.$this->className.'/submit', $content);

					$this->flags['content_exists'] = 1;
				} else {
					echo "Page content not found.";
				}
			}
		}

		function assignPlaceholder($placeholder)
		{
			if(is_string($placeholder))
			{
				$XmlParse = new TxmlParse("..\\templates\\pages_xml\\".$this->className.".xml");
				$newcontent = $XmlParse->get_divs();
				if($placeholder == "loggedin") $placeholder = "login";
				$this->content = str_replace('{'.$placeholder.'}', $newcontent, $this->content);				
			}
			else if(is_array($placeholder))
			{
				foreach ($placeholder as $key) {
					$filename = "../templates/divcontent/".$key.".html";
					if (file_exists($filename)) {
						$newcontent = file_get_contents($filename);
						if($key == "loggedin") $key = "login";
						$this->content = str_replace('{'.$key.'}', $newcontent, $this->content);
					}
				}
			}
		}

		protected function setFormUrl()
		{
			$this->content = str_replace('{submit_url}', Tserver::http()."LiveStream/public/".$this->className, $this->content);
		}

		protected function findPlaceholders($data)
	    {
	        preg_match_all("/{.*?}/", $data, $placeholders, PREG_PATTERN_ORDER);
	        return $placeholders[0];
	    }

		function showContent() {
			// If the flag is set, or if $this->content exists
			if ($this->flags['content_exists'] == 1 || strlen($this->content) > 1) {
				$this->setFormUrl();
				echo $this->content;
			} else {
				return 0;
			}
		}
	}
