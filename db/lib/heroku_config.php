<?php
    $GLOBALS["lm"]["DB"]["DB_HOST"] = getenv("DB_HOST");
    $GLOBALS["lm"]["DB"]["DB_USERNAME"] = getenv("DB_USER");
    $GLOBALS["lm"]["DB"]["DB_PASSWORD"] = getenv("DB_PW");
    $GLOBALS["lm"]["DB"]["DB_TABLE"] = getenv("DB_TABLE");
    //$GLOBALS["lm"]["DB"]["DB_PORT"] = getenv("DB_PORT");

    function debugWrite($msg)
    {
        file_put_contents("php://stdout", (string)$msg);
    }
?>