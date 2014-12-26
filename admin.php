<?php
namespace Fotaxis;


class EvAdmin
{	

	function __construct() {

			$this->requestHandler();
			//$this->newElectionForm();

			include (VE_PLUGIN_PATH."forms/seat.php");
			include (VE_PLUGIN_PATH."forms/newElection.php");
			include (VE_PLUGIN_PATH."forms/electionList.php");

	}

	function addSeat(){

	}

	function getAllSeats(){

		global $wpdb;
		$table_name = $wpdb->prefix . "ve_seats";
		$result = $wpdb->get_results("SELECT * FROM $table_name");
		return $result;
	}

	function getSeat($id){

	}

	function newElectionForm(){
		include (VE_PLUGIN_PATH."forms/newelection.php");
	}

	function requestHandler(){
		global $wpdb;

		if(isset($_POST['post_newElection']) && $_POST['post_newElection']==1){

			$comma_seats = implode(",", $_POST['seats']);
			$table_name = $wpdb->prefix . "ve_elections";
        	$wpdb->insert($table_name, 
				array('name' => $_POST['name'],'seats'=>$comma_seats)
			);

        	if($wpdb->insert_id>0){
        		EC::notifyUpdate("Saved");
        	}else{
        		EC::notifyError("Something went wrong!");
        	}

		}else if(isset($_POST['post_listElection'])){

			$electionId=$_POST['id'];

			if($_POST['post_listElection']=="del"){
				$this->deleteElection($electionId);
			}else if($_POST['post_listElection']=="activate"){
				$this->updateElectionActive($electionId,1);
			}else if($_POST['post_listElection']=="deactivate"){
				$this->updateElectionActive($electionId,0);
			}
		}else if(isset($_POST['admin_seat'])){
			
			if($_POST['admin_seat']=="addseat"){
				$title=$_POST['title'];
				$table_name = $wpdb->prefix . "ve_seats";
				$wpdb->insert($table_name,array('title' => $title));
				if($wpdb->insert_id>0){
        			EC::notifyUpdate("Saved");
        		}else{
        			EC::notifyError("Something went wrong!");
        		}
			}
			else if($_POST['admin_seat']=="delete"){
				$deletId=$_POST['admin_seat_id'];
				$table_name = $wpdb->prefix . "ve_seats";

				$sql=$wpdb->prepare("delete FROM $table_name WHERE id = %d", $deletId);
				$result=$wpdb->query($sql);
				if($result===false){
					EC::notifyError("Error!".$wpdb->print_error());
				}else{
					EC::notifyUpdate("Updated rows:$result");
				}
			}
		}//for admin_seat

	}

	function updateElectionActive($id,$is_active){
		global $wpdb;
		$table_name = $wpdb->prefix . "ve_elections";

		$result=$wpdb->update($table_name,
			array('is_active' => $is_active),
			array( 'id' => $id )
		);		
		if($result===false){
			EC::notifyError("Error!".$wpdb->print_error());
		}else{
			EC::notifyUpdate("Updated rows:$result");
		}

	}	

	function deleteElection($id){
		global $wpdb;
		$table_name = $wpdb->prefix . "ve_elections";

		$sql=$wpdb->prepare( "delete FROM $table_name WHERE id = %d", $id );
		$result=$wpdb->query($sql);
		if($result===false){
			EC::notifyError("Error!".$wpdb->print_error());
		}else{
			EC::notifyUpdate("Updated rows:$result");
		}

		
	}

	function notifyUpdate($txt){
		echo '
        		<div class="updated">
        		<p>'.$txt.'</p>
    			</div>';
	}
	function notifyError($txt){
		echo '  <div class="error">
        		<p>'.$txt.'</p>
    			</div>';
	}

}


?>