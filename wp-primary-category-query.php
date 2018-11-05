<?php
/**
 * Plugin Name: Wp Primary Category Query
 * Description: This plugin allows you to query the posts or custom post types from front end. Use shortcodes [wpcq], [wpcq post_type="post-type-name" taxonomy="taxonomy-name"] into the pages. 
 * Author: Alok Shrestha
 * Version: 1.0.0
 * Author Email: alokstha1@gmail.com
 * Author URI: http://alokshrestha.com.np
 * Text Domain: wpcq
 **/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define WPCQ_PLUGIN_URL.
if ( ! defined( 'WPCQ_PLUGIN_URL' ) ) {
	define( 'WPCQ_PLUGIN_URL', plugins_url( '/', __FILE__ ) );
}

// Include the main Wp_Primary_Category_Query class page.
if ( ! class_exists( 'Wp_Primary_Category_Query' ) ) {
	include_once dirname( __FILE__ ) . '/includes/class-wp-primary-category-query.php';
}
