
<?php
//	Controller

function __autoload($class)
{
    $classname = ucfirst(str_replace("T", "", $class)); // strip the T from class name
    $path      = "../classes/class.".$classname.".php";
    if(is_readable($path))
        require_once($path);
    else
        throw new Exception("Error Processing Request: $class Not found : $path", 1);
}
	
	$Database			= new TDatabase();
	$Logging			= new TLogging();
    TSession::start();

    // Strip because my server is not detecated to one development site
    define('URI', str_replace("LiveStream/public", "", Tserver::uri()));

	$ParseURI			= new TParseURI(URI);

	$Logging->log("Starting script.");

