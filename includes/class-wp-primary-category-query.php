<?php
/**
 * Wp Primary Category Query setup
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
* Initialize Wp_Primary_Category_Query class
**/
class Wp_Primary_Category_Query {

	/**
	* Class constructor
	*/
	function __construct() {

		//set up default settings
		$this->wpcq_default_settings();

		// enqueue frontend scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'wpcq_frontend_enqueues' ) );

		//add menu options
		add_action( 'admin_menu', array( $this, 'wpcq_register_menu_page' ) );

		// sanitize and save options with register_setting function
		add_action( 'admin_init', array( $this, 'wpcq_register_settings' ) );

		// tweets feeds shortcode
		add_shortcode( 'wpcq', array( $this, 'wpcq_shortcode' ) );

		// filter excerpt length
		add_filter( 'excerpt_length', array( $this, 'wpcq_custom_excerpt_length' ), 10 );

		// excerpt more filter
		add_filter( 'excerpt_more', array( $this, 'wpcq_custom_excerpt_more_link' ), 0 );

	}

	/**
	* Frontend enqueue scripts
	*/
	public function wpcq_frontend_enqueues() {
		wp_enqueue_style( 'wpcq-main-style', WPCQ_PLUGIN_URL . 'assets/css/wpcq-main-style.css' );
	}

	/**
	* Add menu page to the dashboard menu.
	*/
	public function wpcq_register_menu_page() {
		add_menu_page( __( 'Query Settings', 'wpcq' ), __( 'Query Settings', 'wpcq' ), 'manage_options', 'wp-primary-category-query.php', array( $this, 'wpcq_add_setting_page' ), '', 20 );

	}

	/**
	* Set default admin form setting on installation.
	*/
	public function wpcq_default_settings() {
	    $default_setting = array(
	        'post_count'	=> 10,
	        );

	    $settings = get_option('wpcq_query_settings');

	    if ( empty( $settings ) ) {
	        update_option( 'wpcq_query_settings', $default_setting );
	    }
	}

	/**
	* Callback function of add_menu_page. Displays the page's content.
	*/
	public function wpcq_add_setting_page() {

		//included the plugin option settings html
		include_once dirname( __FILE__ ) . '/admin-query-settings.php';

	}

	/**
	* Register settings options and save to wp_options table.
	*/
	public function wpcq_register_settings() {
	    register_setting( 'wpcq_query_settings', 'wpcq_query_settings', array( $this, 'wpcq_sanitize_settings' ) );

	}

	/**
	* Save admin form settings value to aka_store_option option.
	*/
	public function wpcq_sanitize_settings() {

		if ( ! isset( $_POST['validate_submit'] ) || ! wp_verify_nonce( $_POST['validate_submit'], 'wpcq_nonce_feeds' ) ) {
			return false;
		}
		$input_options 			= array();
		$wpcq_query_settings 	= get_option('wpcq_query_settings');

		//sanitize post_count
		$input_options['post_count'] 	= ( isset( $_POST['wpcq_query_settings']['post_count'] ) && !empty( $_POST['wpcq_query_settings']['post_count'] ) ) ? (int)$_POST['wpcq_query_settings']['post_count'] : 10;

		return $input_options;

	}

	/**
	* [wpcq] shortcode callback function
	*/
	public function wpcq_shortcode( $atts ) {

		$value = shortcode_atts( array(
			'post_type'	=> 'post',
			'taxonomy' 	=> '',
			), $atts );

		$taxonomy 		= 'category';
		$taxonomy_terms	= get_terms( 
							array(
						    'taxonomy' 		=> 'category',
						    'hide_empty' 	=> false,
						) );

		// condition for custom post types
		if ( 'post' != $value['post_type'] ) {
			$taxonomy = $value['taxonomy'];

			$taxonomy_terms	= get_terms( 
								array(
							    'taxonomy' 		=> $taxonomy,
							    'hide_empty' 	=> false,
							) );
		}


		$wpcq_query_settings 	= get_option( 'wpcq_query_settings' );

		$post_type				= ( isset( $value['post_type'] ) && !empty( $value['post_type'] ) ) ? $value['post_type'] : 'post';
		$post_count				= ( isset( $value['post_count'] ) && !empty( $value['post_count'] ) ) ? (int)$value['post_count'] : $wpcq_query_settings['post_count'];


		ob_start();
		?>
		<div class="post_listings">
			
			<?php include dirname(__FILE__) .'/front-form-html.php'; ?>

			<!-- start of post listing -->
			<?php

				$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

				// wp_query args
				$query_args = array(
					'post_type'			=> $post_type,
					'posts_per_page'	=> $post_count,
					'paged'				=> $paged
				);
				
				// check condition for $_GET form fields
				if ( isset( $_GET['submit_query'] ) && ! empty( $_GET['submit_query'] ) ) {
						
					if ( isset( $_GET['search_keywords'] ) && !empty( $_GET['search_keywords'] ) ) {

						$query_args['s']	= sanitize_text_field( $_GET['search_keywords'] );
						
					}

					if ( isset( $_GET['select_category'] ) && !empty( $_GET['select_category'] ) ) {

						$query_args['tax_query'][]	= array(
							'taxonomy' => $taxonomy,
							'terms' => intval( $_GET['select_category'] ),
							'field' => 'term_id',
						);
					}

					if ( isset( $_GET['sort_by'] ) && !empty( $_GET['sort_by'] ) ) {

						$query_args['orderby']	= sanitize_text_field( $_GET['sort_by'] );
						
					}

					if ( isset( $_GET['order'] ) && !empty( $_GET['order'] ) ) {

						$query_args['order']	= sanitize_text_field( $_GET['order'] );
						
					}
				}

				// wp_query
				$custom_post_query = new Wp_Query( $query_args );

				?>

				<ul class="post_listings">

					<?php 
						if ( $custom_post_query->have_posts() ) {
									while ( $custom_post_query->have_posts() ) {
										$custom_post_query->the_post();

										include dirname( __FILE__ ). '/post-query-loop.php';
									}
									wp_reset_postdata();
						} else {
							echo '<li>Nothing found! Try another search.</li>';
						}
					?>
				</ul>

				<nav class="pagination">
					<?php 
						$total_pages = $custom_post_query->max_num_pages;
						$big = 999999999;

					    if ($total_pages > 1){
					        $current_page = max(1, get_query_var('paged'));

					        echo paginate_links(array(
					            'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
					            'format' => '?paged=%#%',
					            'current' => $current_page,
					            'total' => $total_pages,
					        ));
					    }
					?>
				</nav>
			<!-- end of post listing -->
		</div>
		<?php

		return ob_get_clean();

	}

	/**
	* customize excerpt length
	*/
	public function wpcq_custom_excerpt_length( $length ) {
		return 20;
	}

	/**
	* customize excerpt more link
	*/
	public function wpcq_custom_excerpt_more_link( $more ) {
		remove_filter( 'excerpt_more', 'twentyseventeen_excerpt_more' );
		return ' [.....]';
	}

}

$wp_primary_category_query = new Wp_Primary_Category_Query();