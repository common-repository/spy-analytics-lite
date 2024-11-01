<?php
/*
 * Spy Analytics
 * (c) 2013. Aleksej Sytnik
 */
?>
<?php
//general functions
require_once(dirname(__FILE__).'/fn-functions.php');
//detection class
require_once(dirname(__FILE__).'/geoip.php');
//when we generate js
if(isset($_GET["spyjs"])){
    require_once(dirname(__FILE__).'/fn-js-processing.php');
	die();
}
//when we get data from js
if(isset($_GET["spydata"])){
    require_once(dirname(__FILE__).'/fn-data-processing.php');
	die();
}

//when we get data from js
if(isset($_GET["spy_unlock"])){
	$option = get_option('spy_analytics_plugin');
	$option['unlock_pro'] = true;
	update_option('spy_analytics_plugin', $option);
	die("Unlocked successfully! Thank you for subscribing. Please, reload plugin page to see results");
}
//when we see user actions
if(isset($_GET["spyview"])){
    wp_enqueue_style( 'spyViewSheets');
	wp_enqueue_script( 'jquery' );
	$option = get_option('spy_analytics_plugin');
	if(isset($option['unlock_pro']) && $option['unlock_pro'])
    	require_once(dirname(__FILE__).'/fn-player-processing-pro.php');
	else
    	require_once(dirname(__FILE__).'/fn-player-processing.php');
	die();
}
?>