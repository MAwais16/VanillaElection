<?php
namespace Fotaxis;


class EvAdmin
{
    
    function __construct() {
        
        $this->requestHandler();
        
        //$this->newElectionForm();
        
        
    }
    
    function loadElectionCommission() {
        include (VE_PLUGIN_PATH . "forms/seat.php");
        include (VE_PLUGIN_PATH . "forms/newElection.php");
        include (VE_PLUGIN_PATH . "forms/electionList.php");
        include (VE_PLUGIN_PATH . "forms/nominees.php");
    }
    
    function loadVotes() {
        // $votes = $this->getVoteCount(4, 2);
        // echo $votes->count;

        include (VE_PLUGIN_PATH . "forms/votes.php");
    }

    function getSeats($seatIds) {
        global $wpdb;
        $table_name = $wpdb->prefix . "ve_seats";

        $sql = $wpdb->prepare("SELECT * FROM $table_name where id IN (%s);", $seatIds);
        return $wpdb->get_results($sql);
    }

    function getElectionNominations($election_id, $seat_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . "ve_nominations";
        $sql = $wpdb->prepare("SELECT * FROM $table_name WHERE status=1 AND election_id=%d AND seat_id=%d",$election_id ,$seat_id);
        $result = $wpdb->get_results($sql);
        if ($wpdb->num_rows <= 0) {
            $result = false;
        }
        return $result;
    }
    
    function getVoteCount($election_id, $seat_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . "ve_votes";
        $sql = $wpdb->prepare("SELECT COUNT(id) AS count FROM $table_name where election_id=%d AND seat_id=%d", $election_id,$seat_id);
        $result = $wpdb->get_results($sql);
        return $result[0]->count;
    }
    function getVoteCountForNomination($election_id, $nomination_id) {
    	global $wpdb;
        $table_name = $wpdb->prefix . "ve_votes";
        $sql = $wpdb->prepare("SELECT COUNT(id) AS count FROM $table_name where election_id=%d AND nomination_id=%d",$election_id,$nomination_id );
        $result = $wpdb->get_results($sql);
        return $result[0]->count;
    }
    function getVotesForNomination($nomination_id){
        global $wpdb;
        $table_name = $wpdb->prefix . "ve_votes";
        $sql = $wpdb->prepare("SELECT * FROM $table_name WHERE nomination_id=%d",$nomination_id);
        $result = $wpdb->get_results($sql);
        if ($wpdb->num_rows <= 0) {
            $result = false;
        }
        return $result;

    }
    function getLatestActiveElection() {
        global $wpdb;
        $table_name = $wpdb->prefix . "ve_elections";
        $result = $wpdb->get_results("SELECT * FROM $table_name where is_active=1 OR is_active=2 order by id DESC limit 1");
        if(count($result) == 1) {
            return $result[0];
        } else {
            return false;
        }
    }
    
    function getNominations($election_id) {
        global $wpdb;
        $table_nom = $wpdb->prefix . "ve_nominations";
        $table_seats = $wpdb->prefix . "ve_seats";
        $sql = $wpdb->prepare("SELECT *, nom.id as nomination_id, nom.time as nomination_time FROM $table_nom as nom join $table_seats as seat on nom.seat_id=seat.id WHERE nom.election_id=%d",$election_id);
        $result = $wpdb->get_results($sql);
        
        //$result = $wpdb->get_results("SELECT * FROM $table_nom join $table_seats on $table_nom.seat_id=$table_seats.id where $table_nom.election_id=$election_id");
        if ($wpdb->num_rows <= 0) {
            $result = false;
        }
        return $result;
    }
    function updateSeatNomination($nomination_id, $status) {
        global $wpdb;
        $table_name = $wpdb->prefix . "ve_nominations";
        
        $result = $wpdb->update($table_name, array('status' => $status), array('id' => $nomination_id), array('%d'), array('%d'));
        
        if ($result === false) {
            EC::notifyError("Error!" . $wpdb->print_error());
        } else {
            EC::notifyUpdate("Updated rows:$result");
        }
    }
    
    function getAllSeats() {
        
        global $wpdb;
        $table_name = $wpdb->prefix . "ve_seats";
        $result = $wpdb->get_results("SELECT * FROM $table_name");
        return $result;
    }
    
    function newElectionForm() {
        include (VE_PLUGIN_PATH . "forms/newelection.php");
    }
    
    function requestHandler() {
        
        global $wpdb;
        
        if (isset($_POST['post_newElection']) && $_POST['post_newElection'] == 1) {
            
            $comma_seats = implode(",", $_POST['seats']);
            $table_name = $wpdb->prefix . "ve_elections";
            $wpdb->insert($table_name, array('name' => $_POST['name'], 'seats' => $comma_seats));
            
            if ($wpdb->insert_id > 0) {
                EC::notifyUpdate("Saved");
            } else {
                EC::notifyError("Something went wrong!");
            }
        } else if (isset($_POST['post_listElection'])) {
            
            $electionId = $_POST['id'];
            
            if ($_POST['post_listElection'] == "del") {
                $this->deleteElection($electionId);
            } else if ($_POST['post_listElection'] == "activate") {
                $this->updateElectionActive($electionId, 1);
            } else if ($_POST['post_listElection'] == "deactivate") {
                $this->updateElectionActive($electionId, 0);
            } else if ($_POST['post_listElection'] == "openvoting") {
                $this->updateElectionActive($electionId, 2);
            } else if ($_POST['post_listElection'] == "closevoting") {
                $this->updateElectionActive($electionId, 0);
            }
        } else if (isset($_POST['admin_seat'])) {
            
            if ($_POST['admin_seat'] == "addseat") {
                $title = $_POST['title'];
                $table_name = $wpdb->prefix . "ve_seats";
                $wpdb->insert($table_name, array('title' => $title));
                if ($wpdb->insert_id > 0) {
                    EC::notifyUpdate("Saved");
                } else {
                    EC::notifyError("Something went wrong!");
                }
            } else if ($_POST['admin_seat'] == "delete") {
                $deletId = $_POST['admin_seat_id'];
                $table_name = $wpdb->prefix . "ve_seats";
                
                $sql = $wpdb->prepare("delete FROM $table_name WHERE id = %d", $deletId);
                $result = $wpdb->query($sql);
                if ($result === false) {
                    EC::notifyError("Error!" . $wpdb->print_error());
                } else {
                    EC::notifyUpdate("Updated rows:$result");
                }
            }
        } else if (isset($_POST['admin_nominees'])) {
            if ($_POST['admin_nominees'] == "accept") {
                $this->updateSeatNomination($_POST['admin_nominee_id'], 1);
            } else if ($_POST['admin_nominees'] == "reject") {
                $this->updateSeatNomination($_POST['admin_nominee_id'], 0);
            }
        }
    }
    
    function updateElectionActive($id, $is_active) {
        global $wpdb;
        $table_name = $wpdb->prefix . "ve_elections";
        
        $result = $wpdb->update($table_name, array('is_active' => $is_active), array('id' => $id));
        if ($result === false) {
            EC::notifyError("Error!" . $wpdb->print_error());
        } else {
            EC::notifyUpdate("Updated rows:$result");
        }
    }
    
    function deleteElection($id) {
        global $wpdb;
        $table_name = $wpdb->prefix . "ve_elections";
        
        $sql = $wpdb->prepare("delete FROM $table_name WHERE id = %d", $id);
        $result = $wpdb->query($sql);
        if ($result === false) {
            EC::notifyError("Error!" . $wpdb->print_error());
        } else {
            EC::notifyUpdate("Updated rows:$result");
        }
    }
    
    function notifyUpdate($txt) {
        echo '
        		<div class="updated">
        		<p>' . $txt . '</p>
    			</div>';
    }
    function notifyError($txt) {
        echo '  <div class="error">
        		<p>' . $txt . '</p>
    			</div>';
    }
}
?>