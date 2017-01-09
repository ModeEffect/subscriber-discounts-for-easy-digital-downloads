<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/*
 * Code adapted with permission from code provided by Ashley Gibson (Nose Graze)
 * https://www.nosegraze.com
 *
 */
class SDEDD_Create_Discount {
	public function mc_create_discount(){
		global $sdedd_options;
		if ( !isset( $sdedd_options[ 'mailchimp_key' ] ) ){
			return;
		}

		$key = $sdedd_options[ 'mailchimp_key' ];
		if ( ! isset( $_GET['trigger-special-discount'] ) || ! isset( $_GET['discount-key'] ) || $_GET['discount-key'] != $key || ! function_exists( 'edd_store_discount' ) ) {
			return;
		}

		// Now check to make sure we received data from MailChimp.
		if ( ! isset( $_POST['data']['merges'] ) ) {
			return;
		}

		$contact = $_POST['data']['merges'];
		$email   = wp_strip_all_tags( $contact['EMAIL'] );

		// make sure we're not talking to ourselves
		if ( ! is_email( $email ) ) {
			return;
		}

		global $wpdb;
		$discount_name = $sdedd_options[ 'discount_name' ] . ' ' . $email;

		$query   = "
		    SELECT      *
		    FROM        $wpdb->posts
		    WHERE       $wpdb->posts.post_title LIKE '$discount_name%'
		    AND         $wpdb->posts.post_type = 'edd_discount'
		    ORDER BY    $wpdb->posts.post_title
		";
		$results = $wpdb->get_results( $query );

		// Already created a discount for this email.
		if ( is_array( $results ) && count( $results ) ) {
			return;
		}
		$timestamp     = time();
		$numbers_array = str_split( $timestamp . rand( 10, 99 ) );
		$letters_array = array_combine( range( 1, 26 ), range( 'a', 'z' ) );
		$final_code    = '';

		foreach ( $numbers_array as $key => $value ) {
			$final_code .= $letters_array[ $value ];
		}

		$discount_args = array(
			'code'     => $final_code,
			'name'     => $discount_name,
			'status'   => 'active',
			'max'      => $sdedd_options[ 'discount_max' ],
			'amount'   => $sdedd_options[ 'discount_amount' ],
			'type'     => $sdedd_options[ 'discount_type' ],
			'use_once' => 'true' == $sdedd_options[ 'discount_use_once' ] ? 'true' : 'false',
		);
		update_option( 'discount_args', $discount_args );
		edd_store_discount( $discount_args );

		$first_name = ( array_key_exists( 'FNAME', $contact ) && ! empty( $contact['FNAME'] ) ) ? $contact['FNAME'] : $sdedd_options['name_placeholder'];

		$vars = array(
			'{firstname}'	=> esc_html( $first_name ),
			'{code}'		=> esc_html( $final_code ),
		);

		$message = strtr( $sdedd_options[ 'message' ], $vars );

		$subject = $sdedd_options[ 'email_subject' ];

		$headers = array(
			'Content-Type: text/html; charset=UTF-8',
			'From: ' . $sdedd_options[ 'from_name' ] . ' <' . $sdedd_options[ 'from_email' ] . '>'
		);
		wp_mail( $email, $subject, $message, $headers );
	}

	public function ac_create_discount(){
		global $sdedd_options;
		if ( !isset( $sdedd_options[ 'activecampaign_key' ] ) ){
			return;
		}
		$key = $sdedd_options[ 'activecampaign_key' ];
		if ( ! isset( $_GET['trigger-special-discount'] ) || ! isset( $_GET['discount-key'] ) || $_GET['discount-key'] != $key || ! function_exists( 'edd_store_discount' ) ) {
			return;
		}

		// Now check to make sure we received data from Active Campaign.
		if ( ! isset( $_POST['contact'] ) || ! isset( $_POST['contact']['email'] ) ) {
			return;
		}

		$contact = $_POST['contact'];
		$email   = wp_strip_all_tags( $contact['email'] );

		// make sure we're not talking to ourselves
		if ( ! is_email( $email ) ) {
			return;
		}

		global $wpdb;
		$discount_name = $sdedd_options[ 'discount_name' ] . ' ' . $email;

		$query   = "
		    SELECT      *
		    FROM        $wpdb->posts
		    WHERE       $wpdb->posts.post_title LIKE '$discount_name%'
		    AND         $wpdb->posts.post_type = 'edd_discount'
		    ORDER BY    $wpdb->posts.post_title
		";
		$results = $wpdb->get_results( $query );

		// Already created a discount for this email.
		if ( is_array( $results ) && count( $results ) ) {
			return;
		}
		$timestamp     = time();
		$numbers_array = str_split( $timestamp . rand( 10, 99 ) );
		$letters_array = array_combine( range( 1, 26 ), range( 'a', 'z' ) );
		$final_code    = '';

		foreach ( $numbers_array as $key => $value ) {
			$final_code .= $letters_array[ $value ];
		}

		$discount_args = array(
			'code'     => $final_code,
			'name'     => $discount_name,
			'status'   => 'active',
			'max'      => $sdedd_options[ 'discount_max' ],
			'amount'   => $sdedd_options[ 'discount_amount' ],
			'type'     => $sdedd_options[ 'discount_type' ],
			'use_once' => 'true' == $sdedd_options[ 'discount_use_once' ] ? 'true' : 'false',
		);

		edd_store_discount( $discount_args );

		$first_name = ( array_key_exists( 'FNAME', $contact ) && ! empty( $contact['FNAME'] ) ) ? $contact['FNAME'] : $sdedd_options['name_placeholder'];

		$vars = array(
			'{firstname}'	=> esc_html( $first_name ),
			'{code}'		=> esc_html( $final_code ),
		);

		$message = strtr( $sdedd_options[ 'message' ], $vars );

		$subject = $sdedd_options[ 'email_subject' ];

		$headers = array(
			'Content-Type: text/html; charset=UTF-8',
			'From: ' . $sdedd_options[ 'from_name' ] . ' <' . $sdedd_options[ 'from_email' ] . '>'
		);
		wp_mail( $email, $subject, $message, $headers );
	}
}