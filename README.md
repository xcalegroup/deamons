# Deamons
A library to handle deamons without any deadlocks in PHP using reflection to avoid changing code in the library

# Composer and Database
```
composer require xcalegroup/deamons
```
Create these tables
```
CREATE TABLE `deamon_handler` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `unik_id` varchar(200) NOT NULL,
 `stop` tinyint(4) NOT NULL DEFAULT 0,
 `timestamp` datetime NOT NULL DEFAULT current_timestamp(),
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

CREATE TABLE `deamon_jobs` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `deamon_id` int(11) NOT NULL,
 `job_id` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
 `class` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
 `data` text COLLATE utf8_unicode_ci NOT NULL,
 `created` timestamp NULL DEFAULT current_timestamp(),
 `updated` timestamp NULL DEFAULT current_timestamp(),
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1975 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_c
```

# Requirements
You need to add a json config file to your project
```
{
    "version" : "1.0",
    "max_deamons" : 15,
    "database" : {
            "DB_TYPE" : "mysql",
            "DB_HOST" : "localhost",
            "DB_USER" : "dbuser",
            "DB_PASS" : "password",
            "DB_NAME" : "dbname"
    },
    "deamons" : [
        {
            "class" : "cars",
            "include" : "./examples/cars.php"
        }
    ]
}
```

# How to start the Deamon
```
Your start the Deamon as a cron job.
Set the config file as a parameter to the file.

Or you can run it for test in a browser using the ?config=deamons.json
You can use file paths in the url

```
![alt text](https://raw.githubusercontent.com/xcalegroup/deamons/master/cron.png "Cron in Plesk")

# Implement the abstract functions in your class**
```
require_once("./deamonJob.php");

class cars extends DeamonJob
{
    /**
     * Execute the code for the job with the data supplied in the request method
     * @param $data contains the data stored for each job in the request method.
     */
    public function execute($data)
    {
        echo "Do code with :";
        print_r($data);
    }

    /**
     * Return the data variables to use in execute method.
     * Each array entry equals a new job. You decide how many jobs to start for each deamon.
     * The content for each array entry can be anything you like. Values, JSON, arrays, objects etc.
     * All data is being json_encoded
     * Note: return null on no more data
     */
    public function request()
    {
        return array(array("car" => "BMW", "model" => "330e"), array("car" => "Audi", "model" => "A6"));
    }
}
```