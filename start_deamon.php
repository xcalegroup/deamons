<?php
// get config
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

require_once("deamonBase.php");
require_once("deamonJob.php");
require_once("database.php");

$db = new Database();

getReflectionRequrements($config->deamons);

$deamon = new DeamonBase($db);
if ($deamon->getDeamons() > $max_deamons) {
    echo "Max deamons already started";
    exit;
}
else
	$deamonid = $deamon->createDeamon();


set_time_limit(0);

$expire_time = time()+450;

try {
    echo "Deamon " . $deamon->jobId ." started<br />";
    while (time() < $expire_time && $deamon->must_stop() == 0) {
        foreach ($jobs as $job) {
			$refclass = new ReflectionClass($job->class);
            $deamonjob = $refclass->newInstance($db);
			$deamonjob->create($deamonid, $deamon->getUUID());
			$jobs_to_execute = $deamonjob->getJob();

			if (count($jobs_to_execute) > 0) {
				foreach ($jobs_to_execute as $job_to_execute) {
					$deamonjob->execute($job_to_execute);
					usleep(rand(100,500)); // make sure not to strangle the system
				}
				$deamonjob->remove();
			} else {
				break;
			}
    	}
	}
}
catch(Exception $e) {
	echo $e->getMessage();
}
finally{
	$deamon->cleanup();
	usleep(rand(100,500));
    echo "Deamon " . $deamon->jobId ." stopped";
}
	
function getReflectionRequrements($jobs)
{
    foreach ($jobs as $job) {
        require_once($job->include);
    }
}
?>