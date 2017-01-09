<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class Subscriber_Discounts_EDD {

	public function sdedd_run() {
		if( !function_exists( 'EDD' ) ) {
			add_action( 'admin_init', array( $this, 'sdedd_deactivate' ), 5 );
			add_action( 'admin_notices', array( $this, 'sdedd_error_message' ) );
			return;
		}
	}
	/**
	 * deactivates the plugin if EDD isn't running
	 *
	 * @since  1.0.0
	 *
	 */
	public function sdedd_deactivate() {
    deactivate_plugins( plugin_basename( dirname( dirname( __FILE__ ) ) ) . '/subscriber-discounts-for-easy-digital-downloads.php' );
	}

	/**
	 * error message if we're not using  EDD
	 *
	 * @since  1.0.0
	 *
	 */
	public function sdedd_error_message() {
    $url = 'https://easydigitaldownloads.com/?ref=4599';
	  $error = sprintf( wp_kses( __( 'Sorry, Subscriber Discounts requires <a href="%s">Easy Digital Downloads</a>. It has been deactivated.', 'sdedd' ), array(  'a' => array( 'href' => array() ) ) ), esc_url( $url ) );

		echo '<div id="message" class="error"><p>' . $error . '</p></div>';

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
	}
}
