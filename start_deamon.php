<?php
require_once("deamonBase.php");
require_once("deamonJob.php");
require_once("database.php");

// get config
$file = fopen("deamons.json","r");
$config = json_decode(fread($file, filesize("deamons.json")));

$max_deamons = $config->max_deamons;
$jobs = $config->deamons;

getReflectionRequrements($config->deamons);

$deamon = new DeamonBase();
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
            $deamonjob = $refclass->newInstance();
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