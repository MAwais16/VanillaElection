<?php
namespace Fotaxis;

class NormalUser
{
    function __construct() {
        $this->requestHandler();
        
        //include (VE_PLUGIN_PATH . "forms/electionNominations.php");


        include (VE_PLUGIN_PATH . "forms/nominateMe.php");
    }
    
    function getActiveElection(){
        global $wpdb;
        $table_name = $wpdb->prefix . "ve_elections";
        $result=$wpdb->get_results("SELECT * FROM $table_name where is_active=1 order by id DESC limit 1");
        if(count($result)==1){
            return $result[0];
        }else{
            return false;
        }
        
    }

    function getSeats($seatIds){
        global $wpdb;
        $table_name = $wpdb->prefix . "ve_seats";
        return $wpdb->get_results("SELECT * FROM $table_name where id IN ($seatIds);");

    }

    function requestHandler() {
        
        if (isset($_POST['normal_nominate']) && $_POST['normal_nominate'] == 1) {
            //$this->saveNomination();
        }
    }
    
    function saveNomination() {
        
        $id = $_POST['eid'];
        $dd_seat = $_POST['dd_seat'];
        
        global $wpdb;
        $table_name = $wpdb->prefix . "ve_elections";
        $result = $wpdb->get_results("SELECT * FROM $table_name where is_active=1 && id=$id");
        if ($result) {
            $nom = trim($result[0]->nomination);
            
            if (strlen($nom) > 0) {
                $inJson = $this->insertNomination($nom, get_current_user_id(), $dd_seat);
            } else {
                
                //make new array
                $seats = explode(",", $result[0]->seats);
                foreach ($seats as $seat) {
                    $nomination[$seat] = array();
                }
                
                // print_r($nomination);
                // echo "<hr/>";
                // echo json_encode($nomination);
                $inJson = $this->insertNomination(json_encode($nomination), get_current_user_id(), $dd_seat);
            }
            $result = $wpdb->update($table_name, array('nomination' => $inJson), array('id' => $id));
            if ($result === false) {
                EC::notifyError("Error!" . $wpdb->print_error());
            } else {
                EC::notifyUpdate("Updated rows:$result");
            }
        }
    }
    
    function insertNomination($nomination, $uid, $seat) {
        $nom = json_decode($nomination);
        
        foreach ($nom as $key => & $value) {
            if ($key === $seat) {
                $isThere = false;
                foreach ($value as $item) {
                    if ($item == ($uid . ":0") || $item == ($uid . ":1")) {
                        $isThere = true;
                        break;
                    }
                }
                if (!$isThere) {
                    array_push($value, $uid . ":0");
                }
            }
        }
        return json_encode($nom);
    }
}
?>