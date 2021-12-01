<?php
    require_once("deamonBase.php");
    require_once("database.php");

    $deamon = new DeamonBase();
    $deamon->stopAll();

    echo "All deamons stopping";
    exit;
?>