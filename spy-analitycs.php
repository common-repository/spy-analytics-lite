<?php
/*
Plugin Name: Spy Analytics
Description: With this plugin you can record and view user actions (mouse move, mouse click, page scroll, page resize) on your WP pages. It also detects visitor's IP, Country, OS, Browser, Username (if registered)
Author: Aleksej Sytnik (sAlex)
Version: 1.3
Author URI: http://codecanyon.net/user/sAlex
*/
define('SPY_PLUGIN_URL', plugin_dir_url( __FILE__ ));
#-------------------------------------------------------------------------------------------
function spy_analytics_install() { 			#install plugin
#-------------------------------------------------------------------------------------------
    $option = get_option('spy_analytics_plugin');
    if (false === $option) {
	$option = array();
	$option['version'] = "1.3";
	$option['dbtable_name'] = "spy_analytics";
	$option['opt_record_status'] = false;
	$option['opt_record_all'] = true;
	$option['opt_record_special'] = array();
	$option['opt_record_mousemove'] = true;
	$option['opt_record_mouseclick'] = false;
	$option['opt_record_mouseclick'] = true;
	$option['opt_record_pagescroll'] = true;
	$option['opt_record_interval'] = 5;
	$option['opt_record_kill_session'] = 600;
	
	add_option('spy_analytics_plugin', $option);

	global $wpdb;
	$table = $wpdb->prefix.$option['dbtable_name'];
	$structure = "CREATE TABLE IF NOT EXISTS $table (
						      id int(9) NOT NULL AUTO_INCREMENT,
						      user_id VARCHAR(200) DEFAULT '' NOT NULL,
						      session_id VARCHAR(200) DEFAULT '' NOT NULL,
						      session_spydata text,
						      session_start int(9) NOT NULL,
						      session_end int(9) NOT NULL,
						      session_time int(9) NOT NULL,
						      UNIQUE KEY id (id) 
						    );";
	$wpdb->query($structure);
    }
}					
#-------------------------------------------------------------------------------------------
function spy_analytics_uninstaller() {		#uninstall plugin
#-------------------------------------------------------------------------------------------
    delete_option('spy_analytics_plugin');
}				
#-------------------------------------------------------------------------------------------
function build_analytics_menu() {			#build admin menu
#-------------------------------------------------------------------------------------------
    add_menu_page(__('Spy Analytics','spy-main'), __('Spy Analytics','spy-main'), 'manage_options', 'spy-settings', 'settingsPage', 'div');
    add_submenu_page('spy-settings', __('Settings','spy-setup'), __('Settings','spy-settings'), 'manage_options', 'spy-settings', 'settingsPage');
    add_submenu_page('spy-settings', __('Settings','spy-setup'), __('Analytics','spy-analytics'), 'manage_options', 'spy-analytics', 'spyPage');
	includeCss();
}
#-------------------------------------------------------------------------------------------
function includeCss(){  					#add css
#-------------------------------------------------------------------------------------------
    $spyStyleUrl = plugins_url('css/style.css', __FILE__); 
    wp_register_style('spyStyleSheets', $spyStyleUrl);
	
    $spyStyleUrl = plugins_url('css/flags.css', __FILE__); 
    wp_register_style('spyFlagsStyleSheets', $spyStyleUrl);
	
    wp_enqueue_style( 'spyStyleSheets');
    wp_enqueue_style( 'spyFlagsStyleSheets');
}
#-------------------------------------------------------------------------------------------
function backendSpy() {  					#spy logic
#-------------------------------------------------------------------------------------------
	$spyPlayerUrl = plugins_url('css/player.css', __FILE__); 
    wp_register_style('spyViewSheets', $spyPlayerUrl);
    require_once(dirname(__FILE__).'/includes/functions/fn-backend-processing.php');
	return true;
}
#-------------------------------------------------------------------------------------------
function settingsPage() {  					#settings page
#-------------------------------------------------------------------------------------------
	$option = get_option('spy_analytics_plugin');
	if(isset($option['unlock_pro']) && $option['unlock_pro'])
    	require_once(dirname(__FILE__).'/includes/markup/mk-settings-page-pro.php');
	else
    	require_once(dirname(__FILE__).'/includes/markup/mk-settings-page.php');
	includeCss();
}
#-------------------------------------------------------------------------------------------
function spyPage() {  						#session list page
#-------------------------------------------------------------------------------------------

	$option = get_option('spy_analytics_plugin');
	if(isset($option['unlock_pro']) && $option['unlock_pro'])
    	require_once(dirname(__FILE__).'/includes/markup/mk-analytics-page-pro.php');
	else
    	require_once(dirname(__FILE__).'/includes/markup/mk-analytics-page.php');
	includeCss();
}
#-------------------------------------------------------------------------------------------
function frontendSpy() {  					#spy logic
#-------------------------------------------------------------------------------------------
    wp_register_script( 'spy-js', home_url().'?spyjs=&i='.get_the_ID());
    wp_enqueue_script( 'spy-js' );
}
#register hooks
register_activation_hook( __FILE__, 'spy_analytics_install'); 
register_deactivation_hook( __FILE__, 'spy_analytics_uninstaller'); 
#build admin menu
add_action('admin_menu', 'build_analytics_menu');
#run main logic
add_action('wp_head', 'frontendSpy');
add_action('init', 'backendSpy');
?>