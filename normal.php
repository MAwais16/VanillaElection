<?php
namespace Fotaxis;

class NormalUser
{
    function __construct() {
        $this->requestHandler();
        
        include (VE_PLUGIN_PATH . "forms/electionNominations.php");
        include (VE_PLUGIN_PATH . "forms/nominateMe.php");
        
    }
    
    function getLatestActiveElection() {
        global $wpdb;
        $table_name = $wpdb->prefix . "ve_elections";
        $result = $wpdb->get_results("SELECT * FROM $table_name where is_active=1 order by id DESC limit 1");
        if (count($result) == 1) {
            return $result[0];
        } else {
            return false;
        }
    }

    function getMyNomination($election_id){
        global $wpdb;
        $table_nom = $wpdb->prefix . "ve_nominations";
        $table_seats = $wpdb->prefix . "ve_seats";
        $user_id=get_current_user_id();
        $result = $wpdb->get_results("SELECT * FROM $table_nom join $table_seats on $table_nom.seat_id=$table_seats.id where $table_nom.user_id=$user_id");
        if ($wpdb->num_rows <= 0) {
            $result = false;
        }
        return $result;

    }
    
    function getSeats($seatIds) {
        global $wpdb;
        $table_name = $wpdb->prefix . "ve_seats";
        return $wpdb->get_results("SELECT * FROM $table_name where id IN ($seatIds);");
    }
    
    function requestHandler() {
        global $wpdb;
        $wpdb->hide_errors();
        if (isset($_POST['normal_nominate'])) {
            if ($_POST['normal_nominate'] == "add") {
                
                $id = $_POST['eid'];
                $dd_seat = $_POST['dd_seat'];
                $user_id = get_current_user_id();
                $table_name = $wpdb->prefix . "ve_nominations";
                $wpdb->insert($table_name, array('seat_id' => $dd_seat, 'election_id' => $id, 'user_id' => $user_id));
                if ($wpdb->insert_id > 0) {
                    EC::notifyUpdate("Nominated, Waiting for Approval, Once nominated you will appear in nomination list of the election.");
                } else {
                    EC::notifyError("Unable to nominate");
                }
            }
        }
    }
    
    function getElectionNominations($election_id, $seat_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . "ve_nominations";
        $result = $wpdb->get_results("SELECT * FROM $table_name WHERE status=1 AND election_id=$election_id AND seat_id=$seat_id");
        if ($wpdb->num_rows <= 0) {
            $result = false;
        }
        return $result;
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