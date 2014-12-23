<?php
namespace Fotaxis;


class EvAdmin
{	

	function __construct() {

			$this->requestHandler();
			$this->newElectionForm();

			include (VE_PLUGIN_PATH."forms/electionList.php");
	}

	function newElectionForm(){
		include (VE_PLUGIN_PATH."forms/newelection.php");
	}

	function requestHandler(){
		global $wpdb;

		if(isset($_POST['post_newElection']) && $_POST['post_newElection']==1){

			$table_name = $wpdb->prefix . "ve_elections";
        	$wpdb->insert($table_name, 
				array('name' => $_POST['name'],'seats'=>$_POST['seats'])
			);

        	if($wpdb->insert_id>0){
        		$this->notifyUpdate("Saved");
        	}else{
        		$this->notifyError("Something went wrong!");
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
		}
	}

	function updateElectionActive($id,$is_active){
		global $wpdb;
		$table_name = $wpdb->prefix . "ve_elections";

		$result=$wpdb->update($table_name,
			array('is_active' => $is_active),
			array( 'id' => $id )
		);		
		if($result===false){
			$this->notifyError("Error!".$wpdb->print_error());
		}else{
			$this->notifyUpdate("Updated rows:$result");
		}

	}	

	function deleteElection($id){
		global $wpdb;
		$table_name = $wpdb->prefix . "ve_elections";

		$sql=$wpdb->prepare( "delete FROM $table_name WHERE id = %d", $id );
		$result=$wpdb->query($sql);
		if($result===false){
			$this->notifyError("Error!".$wpdb->print_error());
		}else{
			$this->notifyUpdate("Updated rows:$result");
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