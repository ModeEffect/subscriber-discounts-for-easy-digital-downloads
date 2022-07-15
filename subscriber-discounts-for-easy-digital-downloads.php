<?php
/**
 * Plugin Name: Subscriber Discounts for Easy Digital Downloads
 * Plugin URI: https://amplifyplugins.com
 * Description: Automatically email a discount code to new subscribers.
 * Tags: Easy Digital Downloads, MailChimp, ActiveCampaign, Discounts
 * Tested up to: 6.0.1
 * Version: 1.1.4
 * WC tested up to: 6.7.0
 * Author: AMP-MODE
 * Author URI: https://amplifyplugins.com
 * License: GPL2
 * Text Domain: sdedd
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
add_action('plugins_loaded', 'subscriber_discounts_edd_plugin_init');
function subscriber_discounts_edd_plugin_init() {
	load_plugin_textdomain( 'sdedd', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
}
/* Check if EDD is Installed
--------------------------------------------- */
function sd_edd_require() {
	$files = array(
		'sdedd',
	); //array for future use

	foreach( $files as $file ) {
		require plugin_dir_path( __FILE__ ) . 'includes/' . $file . '.php';
	}
  $sdeddcheck = new Subscriber_Discounts_EDD();
  $sdeddcheck->sdedd_run();

}
add_action( 'admin_init', 'sd_edd_require' );

global $sdedd_options;
$sdedd_options = get_option( 'sdedd_settings' );
/*
 * Includes for our Plugin
 */
 if ( ! defined( 'SDEDD_PLUGIN' ) ) {
   define( 'SDEDD_PLUGIN', __FILE__ );
 }
 if( ! defined( 'SDEDD_PLUGIN_DIR' ) ) {
  	define( 'SDEDD_PLUGIN_DIR', dirname( __FILE__ ) );
 }
 if( ! defined( 'SDEDD_PLUGIN_URL' ) ) {
	define( 'SDEDD_PLUGIN_URL', plugins_url( '', __FILE__ ) );
}
/* Process discount code */
function sdedd_process_discount(){
	$files = array(
		'create-discount',
		'options-page',
		'mailchimp-discount',
		'activecampaign-discount'
	); //array for future use

	foreach( $files as $file ) {
		include( SDEDD_PLUGIN_DIR . '/includes/' . $file . '.php' );
	}
	global $sdedd_options;
	if ( isset( $sdedd_options[ 'mailchimp_key' ] ) && $sdedd_options[ 'mailchimp_key' ] != '' ){
		$create = new SDEDD_Mailchimp_Create_Discount();
		$create->create_discount();
	}
	if ( isset( $sdedd_options[ 'activecampaign_key' ] ) && $sdedd_options[ 'activecampaign_key' ] != '' ){
		$create = new SDEDD_Activecampaign_Create_Discount();
		$create->create_discount();
	}
}
add_action( 'init', 'sdedd_process_discount' );