<?php
    require_once("deamonBase.php");
    require_once("database.php");

    $configfile = "";
    if (isset($argv)) {
        $configfile = $argv[1];
    }
    else{
        $configfile = isset($_GET['config']) ? $_GET['config'] : null;
    }

    if (!isset($configfile)) {
        echo "config file is missing";
        exit;
    }

    $file = fopen($configfile,"r");
    $config = json_decode(fread($file, filesize($configfile)));

    $max_deamons = $config->max_deamons;
    $jobs = $config->deamons;

    define("DB_DEAMON_TYPE", $config->database->DB_TYPE);
    define("DB_DEAMON_HOST", $config->database->DB_HOST);
    define("DB_DEAMON_USER", $config->database->DB_USER);
    define("DB_DEAMON_PASS", $config->database->DB_PASS);
    define("DB_DEAMON_NAME", $config->database->DB_NAME);

    $deamon = new DeamonBase(new database());
    $deamon->stopAll();

    echo "All deamons stopping";
    exit;
?>