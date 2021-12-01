<?php
 
/**
 * Class to handle Deamons. This ensures that each job will run without deadlocks
 */
class DeamonBase
{
    public $jobId = '';
    public $data = 0;

    public function createDeamon(){
        $id;
        global $db;
		try{
			$this->jobId = $this->getUUID();

			$addsession = $db->prepare('INSERT INTO deamon_handler (unik_id) VALUES ("'.$this->jobId.'")');
			$addsession->execute();
            $id = $db->lastInsertId();
		}
		catch(Exception $e){
		}
        return $id;
    }

    /**
     * Clean up the job table. If not then job wont run again
     */
    public function cleanup(){
        global $db;
		try{
			$stmt = $db->prepare('DELETE FROM deamon_handler WHERE unik_id = "'.$this->jobId.'"');
			$stmt->execute();
            
		}
		catch(Exception $e){
		}
    }

    public function must_stop(){
        global $db;
        $stmt = $db->prepare('SELECT stop FROM deamon_handler WHERE unik_id = "'.$this->jobId.'"');
        $stmt->execute();
        return  $stmt->fetch(PDO::FETCH_ASSOC)['stop'];
    }

    public function stop($jobId){
        global $db;
        $stmt = $db->prepare('UPDATE deamon_handler SET stop = 1 WHERE unik_id = "'.$this->jobId.'"');
        $stmt->execute();
    }

    public function stopAll(){
        global $db;
        $stmt = $db->prepare('UPDATE deamon_handler SET stop = 1');
        $stmt->execute();
    }

    /**
     * Return the backlinks that we should work with in this job.
     * @return Result (Either BackLink or Domain. Based on HandleType)
     */
    public function getDeamons(){
        global $db;
        $result = array();
        
        $entries = array();
        $query = "SELECT COUNT(id) as count FROM deamon_handler";
        $db->query("SET TRANSACTION ISOLATION LEVEL READ UNCOMMITTED"); // Make sure to only read data not comitted yet. To avoid Deadlock
		$stmt = $db->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        return $row;
    }

    public function getUUID() {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
    
            // 16 bits for "time_mid"
            mt_rand( 0, 0xffff ),
    
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand( 0, 0x0fff ) | 0x4000,
    
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand( 0, 0x3fff ) | 0x8000,
    
            // 48 bits for "node"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }    
}