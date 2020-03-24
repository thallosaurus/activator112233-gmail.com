<?php

require("queries.php");

function connectDB($w)
{
	//$usr = ($w ? $GLOBALS["iwf"]["USR"]["DB_WRITE_name"] : $GLOBALS["iwf"]["USR"]["DB_READ_name"]);
	//$pw  = ($w ? $GLOBALS["iwf"]["USR"]["DB_WRITE_pw"] :   $GLOBALS["iwf"]["USR"]["DB_READ_pw"]);

	$usr = $GLOBALS["lm"]["DB"]["DB_USERNAME"];
	$pw = $GLOBALS["lm"]["DB"]["DB_PASSWORD"];

	$GLOBALS["lm"]["CNF"]["_DBLINK"] = new PDO("mysql:host=".$GLOBALS["lm"]["DB"]["DB_HOST"].";dbname=".$GLOBALS["lm"]["DB"]["DB_TABLE"].";charset=utf8", $usr, $pw);
	
	//Prüfen auf Erfolg, sobald ERROROBJECT implementiert
	if ( $GLOBALS["lm"]["CNF"]["_DBLINK"] )
	{
		//Gut
	}
	else
	{
		//schlecht
	}
	
	$GLOBALS["lm"]["CNF"]["_DBLINK"]->setAttribute(
		PDO::ATTR_EMULATE_PREPARES, 
		true);
}

function dbExec($querry, $Params = null)
{
    $statement = $GLOBALS["lm"]["CNF"]["_DBLINK"]
		//->prepare($GLOBALS["lm"]["QRY"][$querry]);
		->prepare($GLOBALS["lm"]["QRY"][$querry]);

    $Params ? $statement->execute($Params) : $statement->execute();

    return $statement;
}

function get_last_insert_id()
{
	return $GLOBALS["lm"]["CNF"]["_DBLINK"]->lastInsertId();
}
?>