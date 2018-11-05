<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$wpcq_query_settings = get_option( 'wpcq_query_settings' );
?>

<div class="wrap">
	<h1><?php _e( 'Query Settings', 'wpcq' ); ?></h1>
	<?php settings_errors(); ?>

	<div id="wpcq-setting">

		<form method="POST" action="options.php" id="wpcq-setting-form">

			<?php
			if ( function_exists( 'wp_nonce_field' ) ) {
				wp_nonce_field( 'wpcq_nonce_feeds', 'validate_submit' );
			}
			?>

			<h3 class="hndle">
				<span><?php _e( 'Plugin options', 'wpcq' ); ?></span>
			</h3>

			<table class="form-table">
				<tbody>
					<tr>
						<th>
							<label for="wpcq-post-count">
								<?php _e( 'Number of Posts:', 'wpcq' ); ?>
							</label>
						</th>
						<td>
							<input class="regular-text" type="text" name="wpcq_query_settings[post_count]" id="wpcq-post-count" value="<?php echo ( !empty( $wpcq_query_settings['post_count'] ) ) ? (int)$wpcq_query_settings['post_count'] : 5; ?>" />
							<p class="description" id="post-count-description"><?php _e( 'Enter a numeric value. Default: 5', 'wpcq' ); ?></p>
						</td>
					</tr>

				</tbody>

			</table>
			
			<?php settings_fields( 'wpcq_query_settings' ); ?>
			<p class="submit">
				<?php submit_button( __( 'Save Changes', 'wpcq' ), 'primary', 'submit_wpcq_options', false ); ?>
			</p>

			<p><label for="detail_settings"><?php _e( 'This plugin allows you to query the posts or custom post types from front end. Use shortcodes <b>[wpcq]</b>, <b>[wpcq post_type="post-type-name" taxonomy="taxonomy-name"]</b>. By default the post type is <b>post</b> and taxonomy is <b>category</b>.', 'wpcq' ); ?></label></p>
			
		</form>

	</div>
	
</div>