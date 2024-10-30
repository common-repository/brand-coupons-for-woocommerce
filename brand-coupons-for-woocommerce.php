<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link                  https://www.ampersandfactory.com/
 * @since                 1.0.0
 * @package               Woocommerce_Brand_Coupons
 *
 * @wordpress-plugin
 * Plugin Name:           Brand Coupons for WooCommerce
 * Description:           Display your brand-restricted coupons on the WooCommerce product pages.
 * Version:               1.0.1
 * Author:                Ampersand Factory
 * Author URI:            https://www.ampersandfactory.com/
 * License:               GPL-2.0+
 * License URI:           http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:           brand-coupons-for-woocommerce
 * Domain Path:           /languages
 * WC requires at least:  3.0.0
 * WC tested up to:       4.9.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WBC_DEV', false );

$plugin_data = get_file_data( __FILE__, array( 'Version' => 'Version' ) );
$wbc_version = ( defined( 'WBC_DEV' ) && WBC_DEV ) ? time() : $plugin_data['Version'];

define( 'WOOCOMMERCE_BRAND_COUPONS_VERSION', $wbc_version );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woocommerce-brand-coupons-activator.php
 */
function activate_woocommerce_brand_coupons() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-brand-coupons-activator.php';
	Woocommerce_Brand_Coupons_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woocommerce-brand-coupons-deactivator.php
 */
function deactivate_woocommerce_brand_coupons() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-brand-coupons-deactivator.php';
	Woocommerce_Brand_Coupons_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woocommerce_brand_coupons' );
register_deactivation_hook( __FILE__, 'deactivate_woocommerce_brand_coupons' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-brand-coupons.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woocommerce_brand_coupons() {

	$plugin = new Woocommerce_Brand_Coupons();
	$plugin->run();

}
run_woocommerce_brand_coupons();
