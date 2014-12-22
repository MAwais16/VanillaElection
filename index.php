<?php
/**
 * Plugin Name: Vanilla Election
 * Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
 * Description: For PSA-Kiel online Election
 * Version: 0.1
 * Author: Muhammad Awais Akhtar
 * Author Email: awaisakhtar16@yahoo.com
 * License:GPL2
 */

define( 'VE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'VE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once (VE_PLUGIN_PATH."ec.php");

use \Fotaxis\EC;

add_action('init','construct_my_class');

function construct_my_class(){
	$election= new EC();
}


?>