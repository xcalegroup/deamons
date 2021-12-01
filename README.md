# Deamons
A library to handle deamons without any deadlocks in PHP using reflection to avoid changing code in the library

```
composer require xcalegroup/deamons
```

# Requirements
You need to set the consts in the deamon_consts.php file
```
define("DB_DEAMON_TYPE", "mysql");
define("DB_DEAMON_HOST", "localhost");
define("DB_DEAMON_USER", "username");
define("DB_DEAMON_PASS", "8&okZ1h0");
define("DB_DEAMON_NAME", "deamon");
```

**Implement the abstract functions in your class**
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