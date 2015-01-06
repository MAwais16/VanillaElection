<?php
namespace Fotaxis;
require_once (VE_PLUGIN_PATH . "admin.php");
require_once (VE_PLUGIN_PATH . "normal.php");

class EC
{
    
    function __construct() {
        
        $this->installDB();
        
        add_action('admin_enqueue_scripts', array($this, 'loadScripts'));
        add_action('admin_menu', array($this, 'register_ec_menu'));
        //$this->addMenus();
    }
    
    function addMenus() {
        $author = wp_get_current_user();
        if (isset($author->roles[0])) {
            $current_role = $author->roles[0];
        } else {
            $current_role = 'no_role';
            //wp_die('ouch! something wrong?');
        }
        if ($current_role == 'administrator') {
            // add settings as well
        }
        add_action('admin_menu', array($this, 'register_ec_menu'));
    }
    function register_ec_menu() {
        add_menu_page('Elections', 'Elections', 'edit_posts', 've_menu_home', array($this, 'load_home'));
        $admn = new EvAdmin();
        add_submenu_page("ve_menu_home", "Election Commision", "Election Commision", "activate_plugins", "ve_menu_admin", array($admn, 'loadElectionCommission'));
        add_submenu_page("ve_menu_home", "Election Commision", "Votes", "activate_plugins", "ve_menu_admin_votes", array($admn, 'loadVotes'));
    }
    
    function load_home() {
        
        //include (VE_PLUGIN_PATH . "normal.php");
        $nu = new NormalUser();
    }
    
    function loadScripts() {
        
        wp_register_style('VeFormCss', VE_PLUGIN_URL . 'style.css');
        wp_enqueue_style('VeFormCss');
        
        //wp_enqueue_style( 'VeFormCss' );
        
        wp_register_script('momentjs', VE_PLUGIN_URL . 'moment.min.js');
        wp_enqueue_script('momentjs');
    }
    
    function installDB() {
        
        global $wpdb;
        $table_name = $wpdb->prefix . "ve_elections";
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id int NOT NULL AUTO_INCREMENT,
            time timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
            name text NOT NULL,
            seats text,
            nomination text,
            is_active int(1) DEFAULT 0,
            UNIQUE KEY id (id)
            ) $charset_collate;";
        
        $table_name = $wpdb->prefix . "ve_seats";
        $sql2 = "CREATE TABLE IF NOT EXISTS $table_name (
            id int NOT NULL AUTO_INCREMENT,
            time timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
            title text NOT NULL,
            UNIQUE KEY id (id)
            ) $charset_collate;";
        
        $table_name = $wpdb->prefix . "ve_nominations";
        $sql3 = "CREATE TABLE IF NOT EXISTS $table_name (
            id int NOT NULL AUTO_INCREMENT UNIQUE,
            time timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
            election_id int NOT NULL,
            seat_id int NOT NULL,
            user_id int NOT NULL,
            status tinyint(1) DEFAULT 0,
            PRIMARY KEY  (election_id,user_id)
            ) $charset_collate;";
        
        // must have double space between primary key and its value to save youself from wp error
        //http://codex.wordpress.org/Creating_Tables_with_Plugins
        
        $table_name = $wpdb->prefix . "ve_votes";
        $sql4 = "CREATE TABLE IF NOT EXISTS $table_name(
            id int NOT NULL AUTO_INCREMENT UNIQUE,
            time timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
            user_id int NOT NULL,
            seat_id int NOT NULL,
            election_id int NOT NULL,
            nomination_id int NOT NULL,
            status tinyint(1) DEFAULT 0,
            PRIMARY KEY  (election_id,user_id,seat_id)
            ) $charset_collate;";

        require_once (ABSPATH . 'wp-admin/includes/upgrade.php');

        dbDelta($sql);
        dbDelta($sql2);
        dbDelta($sql3);
        dbDelta($sql4);
        //add update logic later
        
    }
    
    static function notifyUpdate($txt) {
        echo '
                <div class="updated">
                <p>' . $txt . '</p>
                </div>';
    }
    static function notifyError($txt) {
        echo '  <div class="error">
                <p>' . $txt . '</p>
                </div>';
    }
}
?>