<?php

    class TPageClass extends TGeneralPageClass {
        function init() {
            // Send appropriate headers to web browser to ensure css is handled properly.
            $this->showHeaders();

            // Display the CSS content
            $this->createContent();
            $this->showContent();
        }

        function showHeaders() {
            header("Content-Style-Type: text/javascript");
            header("Content-Type: text/javascript");
        }


        function handleFormSubmission() {
        }
    }
