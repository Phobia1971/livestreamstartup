<?php

	require_once("../classes/class.Sanitization.php");
	require_once("../classes/class.Logging.php");

	class TGeneralPageClass {
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

			$this->init();


			$this->flags['content_exists'] = 0;

			if (isset($_POST['submit'])) {
				$this->handleFormSubmission();

			}


		}

		function createContent() {

			if (preg_match('!^/css/(.*?)$!imsx', URI, $pmatches)) {
				$this->Logging->log("preg_match");
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

				$className = $this->className;

				$filename	= "../templates/pages/".$className.".html";

				if (file_exists($filename)) {

					$content = file_get_contents($filename);

					// Any placeholders need to change here.
					$this->content = str_replace('{submit_url}', '/'.$className.'/submit', $content);

					$this->flags['content_exists'] = 1;
				} else {
					echo "Page content not found.";
				}
			}
		}

		function assignPlaceholder($placeholder) {
			$filename = "../templates/divcontent/".$placeholder.".html";
			if (file_exists($filename)) {
				$newcontent = file_get_contents($filename);
				$this->content = str_replace('{'.$placeholder.'}', $newcontent, $this->content);
			}

		}

		protected function findPlaceholders($data)
	    {
	        preg_match_all("/{.*?}/", $data, $placeholders, PREG_PATTERN_ORDER);
	        return $placeholders[0];
	    }

		function showContent() {
			// If the flag is set, or if $this->content exists
			if ($this->flags['content_exists'] == 1 || strlen($this->content) > 1) {
				echo $this->content;
			} else {
				return 0;
			}
		}
	}
