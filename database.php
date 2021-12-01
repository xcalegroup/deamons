<?php
require_once("deamon_consts.php");

$db = new Database();

class Database extends PDO {

    public function __construct() {

        try {
            parent::__construct(DB_DEAMON_TYPE . ':host=' . DB_DEAMON_HOST . ';dbname=' . DB_DEAMON_NAME . ';charset=utf8', DB_DEAMON_USER, DB_DEAMON_PASS);
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $custom_errormsg = 'Error connecting to database - <u>check your database connection properties in the constants.php file!</u>';
            echo "<br>\n <div style ='color:red'><strong>" . $custom_errormsg . "</strong></div><br>\n<br>\n ". $e->getMessage();
            echo "<br>\nPHP Version : ".phpversion()."<br>\n";
        }
    }
}