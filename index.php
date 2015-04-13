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

require_once dirname( __file__ ) . '/php-activerecord/ActiveRecord.php';

require_once (VE_PLUGIN_PATH."ec.php");
use \Fotaxis\EC;

ActiveRecord\Config::initialize(
    function ( $cfg ) {
        $cfg->set_model_directory( VE_PLUGIN_PATH."models/" );
        $cfg->set_connections(
            array(
                'wp' => sprintf( 'mysql://%s:%s@%s/%s?charset=%s', DB_USER, DB_PASSWORD, DB_HOST, DB_NAME, DB_CHARSET ),
            )
        );

        $cfg->set_default_connection( 'wp' );
    }
);

class WP_ve_elections extends ActiveRecord\Model {};


add_action('init','construct_my_class');

function construct_my_class(){
	$election= new EC();
}


?>