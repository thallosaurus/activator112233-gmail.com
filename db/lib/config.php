<?php
require("heroku_config.php");
$GLOBALS["lm"]["CNF"]["_DBLINK"] = null;
$GLOBALS["lm"]["CNF"]["COLOR"] = getenv("PAGECOLOR");
?>