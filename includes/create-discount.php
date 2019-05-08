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

	public function __construct(){
		$this->sdedd_options = get_option( 'sdedd_settings' );
	}
	/**
	 * Our discount type. Used for type specific filters/actions
	 * @var string
	 * @since 1.1.0
	 */
	public $discount_type = 'default';


	/**
	 * The key used in the webhook.
	 * @return string
	 * @since 1.1.0
	 */
	public function get_key(){
		$key = false;
		return $key;
	}

	/**
	 * The name we'll give the discount when it is created
	 * @since 1.1.0
	 * @return string
	 */
	public function get_discount_name(){
		$discount_name = $this->sdedd_options[ 'discount_name' ] . ' ' . $this->get_email();
		return $discount_name;
	}

	/**
	 * Get email address from the webhook
	 *
	 * @access public
	 * @since 1.1.0
	 * @return string
	 */
	public function get_email() {
		$email = '';
		if( isset( $_POST['email'] ) ){
			$email = $_POST['email'];
		}
		if ( ! is_email( $email ) ){
			$email = '';
		}
		return $email;
	}

	/**
	 * Get contact name from the webhook
	 *
	 * @access public
	 * @since 1.1.0
	 * @return string
	 */
	public function get_name() {
		$name = '';
		if( isset( $_POST['data']['merges'] ) ){
			$name = $_POST['data']['merges']['FNAME'];
		}
		if ( empty( $name ) ){
			$name = $this->sdedd_options['name_placeholder'];
		}
		return $name;
	}

	/**
	 * Can we create a discount?
	 *
	 * @access public
	 * @since 1.1.0
	 * @return bool Whether the discount should be created
	 */
	public function can_discount() {
		$discount = true;
		if ( ! isset( $_GET['trigger-special-discount'] ) || ! isset( $_GET['discount-key'] ) || $_GET['discount-key'] != $this->get_key() || ! function_exists( 'edd_store_discount' ) ) {
			$discount = false;
		}
		if ( ! $this->already_discounted() ){
			$discount = false;
		}
		if ( ! $this->get_key() ){
			$discount = false;
		}
		if ( $this->get_email() == '' ){
			$discount = false;
		}
		// Only create a discount if the type is another type
		if ( $this->discount_type == 'default' ){
			$discount = false;
		}
		return (bool) $discount;
	}

	/**
	 * Does discount exist already?
	 *
	 * @access public
	 * @since 1.1.0
	 * @return bool Whether the discount already exists
	 */
	public function already_discounted() {
		global $wpdb;

		$name = $this->get_discount_name();

		$query   = "
		    SELECT      *
		    FROM        $wpdb->posts
		    WHERE       $wpdb->posts.post_title LIKE '$name%'
		    AND         $wpdb->posts.post_type = 'edd_discount'
		    ORDER BY    $wpdb->posts.post_title
		";
		$results = $wpdb->get_results( $query );
		$discount = true;
		// Check if we already created a discount for this email.
		if ( is_array( $results ) && count( $results ) ) {
			$discount = false;
		}
		return (bool) $discount;
	}

	/**
	 * Create the discount code
	 *
	 * @access public
	 * @since 1.1.0
	 * @return void
	 */
	public function create_discount(){
		if ( !$this->can_discount() ){
			return;
		}
		$timestamp		= time();
		$numbers_array	= str_split( $timestamp . rand( 10, 99 ) );
		$letters_array	= array_combine( range( 1, 26 ), range( 'a', 'z' ) );
		$final_code		= '';

		foreach ( $numbers_array as $key => $value ) {
			$final_code .= $letters_array[ $value ];
		}

		$discount_args = array(
			'code'		=> $final_code,
			'name'		=> $this->get_discount_name(),
			'status'	=> 'active',
			'max'		=> $this->sdedd_options[ 'discount_max' ],
			'amount'	=> $this->sdedd_options[ 'discount_amount' ],
			'type'		=> $this->sdedd_options[ 'discount_type' ],
			'use_once'	=> 'true' == $this->sdedd_options[ 'discount_use_once' ] ? 'true' : 'false',
		);

		//Create the discount
		edd_store_discount( $discount_args );

		//Send the discount to the subscriber
		$first_name = $this->get_name();

		$vars = array(
			'{firstname}'	=> esc_html( $first_name ),
			'{code}'		=> esc_html( $final_code ),
		);

		$message = strtr( $this->sdedd_options[ 'message' ], $vars );

		$subject = $this->sdedd_options[ 'email_subject' ];

		$headers = array(
			'Content-Type: text/html; charset=UTF-8',
			'From: ' . $this->sdedd_options[ 'from_name' ] . ' <' . $this->sdedd_options[ 'from_email' ] . '>'
		);
		wp_mail( $this->get_email(), $subject, $message, $headers );
	}
}