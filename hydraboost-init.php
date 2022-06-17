<?php
/**
 * Plugin Name: Hydraboost Core
 * Description: Hydraboost Theme Companion Plugin
 * Plugin URI:  https://elementor.com/
 * Version:     1.0.0
 * Author:      Iftekhar Rahman
 * Author URI:  https://developers.elementor.com/
 * Text Domain: hydraboost-core
 * 
 * Elementor tested up to:     3.5.0
 * Elementor Pro tested up to: 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function hydraboost_core() {

	// Load plugin file
	require_once( __DIR__ . '/includes/plugin.php' );

	// Run the plugin
	\Hydraboost_Elementor\Plugin::instance();

}
add_action( 'plugins_loaded', 'hydraboost_core' );