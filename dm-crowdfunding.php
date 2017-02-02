<?php if(!defined('ABSPATH')) die('Fatal Error');
/*
Plugin Name: DM Crowdfunding
Plugin URI: http://divestmedia.com
Description: Divest Media plugin for Crowd Funding Project
Author: ralphjesy@gmail.com
Version: 1.0
Author URI: http://github.com/ralphjesy12
*/
define( 'DM_CROWD_VERSION', '1.0' );
define( 'DM_CROWD_MIN_WP_VERSION', '4.4' );
define( 'DM_CROWD_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'DM_CROWD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'DM_CROWD_DEBUG' , true );

require_once DM_CROWD_PLUGIN_DIR . '/vendor/autoload.php';
require_once DM_CROWD_PLUGIN_DIR . '/lib/class-crowdfunding.php';


if(DM_CROWD_DEBUG){
    // if (!ini_get('display_errors')) {
        ini_set('display_errors', '1');
    // }
}

if(class_exists('DMCROWD'))
{

    register_activation_hook(__FILE__, array('DMCROWD', 'activate'));
    register_deactivation_hook(__FILE__, array('DMCROWD', 'deactivate'));
    $DMCROWD = new DMCROWD();

}
