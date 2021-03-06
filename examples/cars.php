<?php

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

?>