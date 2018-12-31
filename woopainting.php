<?php

/**
 * @package WooPainting
 * @version 0.0.1
 */
/*
Plugin Name: WooPainting
Plugin URI: https://WooPainting.com
Description: Customize functions for WooPainting.com
Author: Kevin Bai
Version: 0.0.1
Author URI: http://moe.cc/
Text Domain: WooPainting
*/

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly
	}
	if( !function_exists('is_woocommerce_active') ){
		include( plugin_dir_path( __FILE__ ) . 'includes/functions.php');
		include( plugin_dir_path( __FILE__ ) . 'includes/tracking_number.php');
		include( plugin_dir_path( __FILE__ ) . 'includes/fp/FilePond/RequestHandler.class.php');
		
		//include( plugin_dir_path( __FILE__ ) . 'includes/others.php');
	}
	
?>