<?php
require("heroku_config.php");
$GLOBALS["lm"]["CNF"]["_DBLINK"] = null;
$GLOBALS["lm"]["CNF"]["COLOR"] = getenv("PAGECOLOR");
$GLOBALS["lm"]["CNF"]["FAB_USE_SUBBUTTONS"] = getenv("FAB_USE_SUBBUTTONS");

function debugWrite($msg)
{
    file_put_contents("php://stdout", (string)$msg);
}

function fab_use_subbuttons()
{
    return $GLOBALS["lm"]["CNF"]["FAB_USE_SUBBUTTONS"];
}
?>