<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
add_action( 'admin_menu', 'admin_settings_page' );
add_action( 'admin_init', 'sdedd_register_settings' );
	
function admin_settings_page(){
	add_submenu_page( 'edit.php?post_type=download', __( 'Subscriber Discounts', 'sdedd' ), __( 'Subscriber Discounts', 'sdedd' ), 'manage_options', 'subscriber-discounts-edd', 'sdedd_display_settings' );
}

function sdedd_register_settings(){
	register_setting( 'subscriber_discounts_settings_group', 'sdedd_settings' );
}

function sdedd_display_settings(){
	global $sdedd_options; ?>
	<div class="wrap">
	<h2><?php _e('Subscriber Discount Settings', 'sdedd'); ?></h2>
	<form method="post" action="options.php">

		<?php settings_fields( 'subscriber_discounts_settings_group' ); ?>
		<h3 class="title"><?php _e( 'Discount Code Settings', 'sdedd' ); ?></h3>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row" valign="top">
						<?php _e( 'MailChimp Key', 'sdedd' ); ?>
					</th>
					<td>
						<input id="sdedd_settings[mailchimp_key]" name="sdedd_settings[mailchimp_key]" type="text" class="regular-text" value="<?php echo $sdedd_options['mailchimp_key']; ?>"/>
						<p class="description" for="sdedd_settings[mailchimp_key]"><?php _e( 'Enter a random string of letters and numbers to be used as a means of verifying data is coming from MailChimp. Leave blank if not using MailChimp.', 'sdedd' ); ?></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" valign="top">
						<?php _e( 'Active Campaign Key', 'sdedd' ); ?>
					</th>
					<td>
						<input id="sdedd_settings[activecampaign_key]" name="sdedd_settings[activecampaign_key]" type="text" class="regular-text" value="<?php echo $sdedd_options['activecampaign_key']; ?>"/>
						<p class="description" for="sdedd_settings[activecampaign_key]"><?php _e( 'Enter a random string of letters and numbers to be used as a means of verifying data is coming from Active Campaign. Leave blank if not using Active Campaign.', 'sdedd' ); ?></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" valign="top">
						<?php _e( 'Discount Name', 'sdedd' ); ?>
					</th>
					<td>
						<input id="sdedd_settings[discount_name]" name="sdedd_settings[discount_name]" type="text" class="regular-text" value="<?php echo $sdedd_options['discount_name']; ?>"/>
						<p class="description" for="sdedd_settings[discount_name]"><?php _e( 'Enter what you would like your discounts to be called in EDD > Discount Codes. Your customer\'s email address will automatically be added to the end of this.', 'sdedd' ); ?></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" valign="top">
						<?php _e( 'Discount Amount', 'sdedd' ); ?>
					</th>
					<td>
						<input id="sdedd_settings[discount_amount]" name="sdedd_settings[discount_amount]" type="text" class="regular-text" value="<?php echo $sdedd_options['discount_amount']; ?>"/>
						<p class="description" for="sdedd_settings[discount_amount]"><?php _e( 'Amount of the discount (i.e. 20 for 20% or $20)', 'sdedd' ); ?></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" valign="top">
						<?php _e( 'Discount Type', 'sdedd' ); ?>
					</th>
					<td>
						<select id="sdedd_settings[discount_type]" name="sdedd_settings[discount_type]">
							<option value='percent' <?php selected( 'percent', $sdedd_options[ 'discount_type' ]); ?> ><?php _e( 'Percent', 'sdedd' ); ?></option>
							<option value='flat' <?php selected( 'flat', $sdedd_options[ 'discount_type' ]); ?> ><?php _e( 'Flat Amount', 'sdedd' ); ?></option>
						</select>
						<p class="description" for="sdedd_settings[discount_type]"><?php _e( 'Is the discount amount a flat rate or percentage?', 'sdedd' ); ?></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" valign="top">
						<?php _e( 'Use Once Per Customer', 'sdedd' ); ?>
					</th>
					<td>
						<input type="checkbox" id="sdedd_settings[discount_use_once]" name="sdedd_settings[discount_use_once]" value="true" <?php checked( 'true', $sdedd_options[ 'discount_use_once' ] ); ?> />
						<p class="description" for="sdedd_settings[discount_type]"><?php _e( 'Limit this discount to a single use per customer?', 'sdedd' ); ?></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" valign="top">
						<?php _e( 'Max Uses', 'sdedd' ); ?>
					</th>
					<td>
						<input id="sdedd_settings[discount_max]" name="sdedd_settings[discount_max]" type="text" class="regular-text" value="<?php echo $sdedd_options['discount_max']; ?>"/>
						<p class="description" for="sdedd_settings[discount_max]"><?php _e( 'The maximum number of times this discount can be used. Leave blank for unlimited.', 'sdedd' ); ?></p>
					</td>
				</tr>
			</tbody>
		</table>
		<h3 class="title"><?php _e( 'Email Settings', 'sdedd' ); ?></h3>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row" valign="top">
						<?php _e( 'Email Subject', 'sdedd' ); ?>
					</th>
					<td>
						<input id="sdedd_settings[email_subject]" name="sdedd_settings[email_subject]" type="text" class="regular-text" value="<?php echo $sdedd_options['email_subject']; ?>"/>
						<p class="description" for="sdedd_settings[email_subject]"><?php _e( 'What will appear in the subject line of the email sent to your subscribers.', 'sdedd' ); ?></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" valign="top">
						<?php _e( 'From Email Address', 'sdedd' ); ?>
					</th>
					<td>
						<input id="sdedd_settings[from_email]" name="sdedd_settings[from_email]" type="text" class="regular-text" value="<?php echo $sdedd_options['from_email']; ?>"/>
						<p class="description" for="sdedd_settings[from_email]"><?php _e( 'The email address that this message should come from. i.e. support@yourdomain.com', 'sdedd' ); ?></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" valign="top">
						<?php _e( 'From Display Name', 'sdedd' ); ?>
					</th>
					<td>
						<input id="sdedd_settings[from_name]" name="sdedd_settings[from_name]" type="text" class="regular-text" value="<?php echo $sdedd_options['from_name']; ?>"/>
						<p class="description" for="sdedd_settings[from_name]"><?php _e( 'The name that this message should come from. i.e. Your Store Name', 'sdedd' ); ?></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" valign="top">
						<?php _e( 'Customer Placeholder Name', 'sdedd' ); ?>
					</th>
					<td>
						<input id="sdedd_settings[name_placeholder]" name="sdedd_settings[name_placeholder]" type="text" class="regular-text" value="<?php echo $sdedd_options['name_placeholder']; ?>"/>
						<p class="description" for="sdedd_settings[name_placeholder]"><?php _e( 'To be used in the {firstname} placeholder if no name is provided by the customer.', 'sdedd' ); ?></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" valign="top">
						<?php _e( 'Message', 'sdedd' ); ?>
					</th>
					<td>
						<textarea rows="6" cols="75" id="sdedd_settings[message]" name="sdedd_settings[message]"><?php echo $sdedd_options['message']; ?></textarea>
						<p class="description" for="sdedd_settings[message]"><?php _e( 'The message to be sent to your customer with the discount code. You can use the following placeholder tags to be filled in when the message is sent: <ul><li>{firstname} - enters the customer\'s first name or the value entered as the placeholder name.</li><li>{code} - use where you want the discount code to be displayed in your message.</li></ul>', 'sdedd' ); ?></p>
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e( 'Save Options', 'sdedd' ); ?>" />
		</p>
	</form>
	<?php
}