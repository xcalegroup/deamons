<?php
 

 /**
 * Class to handle Deamon jobs. This ensures that each job will run without deadlocks
 */
abstract class DeamonJob
{
    protected $deamon_id = '';
    protected $job_id = '';
    protected $db;

    abstract public function execute($data);
    abstract public function request();

    function __construct($db) {
        $this->db = $db;
    }

    public function create($deamon_id, $job_id){
		try{
            $this->deamon_id = $deamon_id;
            $this->job_id = $job_id;

            $class = get_class($this);

            $requestData = $this->request();// Request data - This is the data for the class

            if (isset($requestData)) {
                foreach ($requestData as $data) {
                    $this->db->query("SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED"); // Make sure to only read data not comitted yet. To avoid Deadlock
                    $addsession = $this->db->prepare('INSERT INTO deamon_jobs (deamon_id, job_id, class, data) VALUES (:deamon_id, :job_id, :class, :data)');
                    $addsession->execute(array(':deamon_id' => $this->deamon_id, ':job_id' => $this->job_id, ':class' => $class, ':data' => json_encode($data)));
                }
            }
		}
		catch(Exception $e){
            echo $e->getMessage();
		}
    }
    
    /**
     * Clean up the job table. If not then job wont run again
     */
    public function remove(){
		try{
			$stmt = $this->db->prepare('DELETE FROM deamon_jobs WHERE job_id = "'.$this->job_id.'"');
			$stmt->execute();
		}
		catch(Exception $e){
		}
    }

    public function cleanup(){
		try{
			$stmt = $this->db->prepare('DELETE FROM deamon_jobs WHERE created < (NOW() - INTERVAL 10 MINUTE)');
			$stmt->execute();
		}
		catch(Exception $e){
		}
    }

    public function getJob(){
        $result = array();
        
        $entries = array();
        $query = "SELECT * FROM deamon_jobs WHERE job_id = :this_job_id FOR UPDATE";
        $this->db->query("SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED"); // Make sure to only read data not comitted yet. To avoid Deadlock
		$stmt = $this->db->prepare($query);
        $stmt->execute(array(':this_job_id' => $this->job_id));
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $entries[] = $row;
        }
        
        return $entries;
    }
}