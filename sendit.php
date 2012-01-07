<?php
/*
Plugin Name: Sendit reload!
Plugin URI: http://www.giuseppesurace.com/sendit-wp-newsletter-mailing-list/
Description: The new version of Sendit. 
Version: 2.0.0
Author: Giuseppe Surace
Author URI: http://www.giuseppesurace.com
*/

include_once plugin_dir_path( __FILE__ ).'/libs/install-core.php';
include_once plugin_dir_path( __FILE__ ).'/libs/markup.php';
include_once plugin_dir_path( __FILE__ ).'/libs/actions.php';
include_once plugin_dir_path( __FILE__ ).'/libs/admin.php';
include_once plugin_dir_path( __FILE__ ).'/libs/extensions-handler.php';


load_plugin_textdomain('sendit', false, basename(dirname(__FILE__)) . '/languages'); //thanks to Davide http://www.jqueryitalia.org

register_activation_hook( __FILE__, 'sendit_install' );
register_activation_hook( __FILE__, 'sendit_sampledata');

add_action('wp_head', 'sendit_js');
add_action('wp_head', 'sendit_loading_image');
add_action('wp_head', 'sendit_register_head');
add_action('plugins_loaded','DisplayForm');
add_action('admin_menu', 'gestisci_menu');

add_action('admin_head', 'sendit_admin_head');
add_action('admin_head', 'sendit_admin_js');
add_action('init', 'sendit_custom_post_type_init');
add_action('save_post', 'sendit_save_postdata');

add_action('save_post', 'send_newsletter');



?>