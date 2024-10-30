<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.ampersandfactory.com/
 * @since      1.0.0
 *
 * @package    Woocommerce_Brand_Coupons
 * @subpackage Woocommerce_Brand_Coupons/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Woocommerce_Brand_Coupons
 * @subpackage Woocommerce_Brand_Coupons/includes
 * @author     Ampersand Factory <info@ampersandfactory.com>
 */
class Woocommerce_Brand_Coupons_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'brand-coupons-for-woocommerce',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
