<?php
namespace Fotaxis;

class EvAdmin
{	
	function __construct() {
			$this->requestHandler();
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
        		echo '
        		<div class="updated">
        		<p>Saved</p>
    			</div>';
        	}else{
        		echo '
        		<div class="error">
        		<p>Saved</p>
    			</div>';
        	}

		}

	}

}


?>