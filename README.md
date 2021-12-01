# Deamons
A library to handle deamons without any deadlocks in PHP using reflection to avoid changing code in the library

```
composer require xcalegroup/deamons
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

![Alt text](/xcalegroup/deamons/master/cron.png?raw=true "Title")
![alt text](https://raw.githubusercontent.com/xcalegroup/deamons/master/cron.png)

Or you can run it for test in a browser using the ?config=deamons.json
You can use file paths in the url

```

# Implement the abstract functions in your class**
```
require_once("deamonJob.php");

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
     * The content for each array entry can be anything you like. Values, JSON, arrays, objects etc. As long as it can be presented as a string.
     * Note: return null on no more data
     */
    public function request()
    {
        return array("Make" => "BMW", "Make" => "Audi");
    }
}
```