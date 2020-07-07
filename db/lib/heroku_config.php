<?php
$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

    $GLOBALS["lm"]["DB"]["DB_HOST"] = getenv("DB_HOST");
    $GLOBALS["lm"]["DB"]["DB_USERNAME"] = getenv("DB_USER");
    $GLOBALS["lm"]["DB"]["DB_PASSWORD"] = getenv("DB_PW");
    $GLOBALS["lm"]["DB"]["DB_TABLE"] = getenv("DB_TABLE");
    //$GLOBALS["lm"]["DB"]["DB_PORT"] = getenv("DB_PORT");

    function getHost()
    {
        return $GLOBALS["lm"]["DB"]["DB_HOST"];
    }

    function getUsername()
    {
        return $GLOBALS["lm"]["DB"]["DB_USERNAME"];
    }

    function getDBPW()
    {
        return $GLOBALS["lm"]["DB"]["DB_PASSWORD"];
    }

    function getTablename()
    {
        return $GLOBALS["lm"]["DB"]["DB_TABLE"];
    }
?>
