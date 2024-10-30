<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.ampersandfactory.com/
 * @since      1.0.0
 *
 * @package    Woocommerce_Brand_Coupons
 * @subpackage Woocommerce_Brand_Coupons/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woocommerce_Brand_Coupons
 * @subpackage Woocommerce_Brand_Coupons/admin
 * @author     Ampersand Factory <info@ampersandfactory.com>
 */
class Woocommerce_Brand_Coupons_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $woocommerce_brand_coupons    The ID of this plugin.
	 */
	private $woocommerce_brand_coupons;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;


	/**
	 * CUSTOM VARIABLES
	 */
	private $defaults;
	private $wbc_options;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $woocommerce_brand_coupons       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $woocommerce_brand_coupons, $version, $defaults ) {

		$this->woocommerce_brand_coupons = $woocommerce_brand_coupons;
		$this->version = $version;
		
		$this->defaults = $defaults;
		
		$this->wbc_options = get_option( 'wbc_options' );

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Brand_Coupons_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Brand_Coupons_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->woocommerce_brand_coupons, plugin_dir_url( __FILE__ ) . 'css/woocommerce-brand-coupons-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Brand_Coupons_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Brand_Coupons_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->woocommerce_brand_coupons, plugin_dir_url( __FILE__ ) . 'js/woocommerce-brand-coupons-admin.js', array( 'jquery' ), $this->version, false );
		
		
		if ( get_current_screen()->id == 'toplevel_page_woocommerce-brand-coupons' ) {

			$settings = wp_enqueue_code_editor( array( 'type' => 'text/css' ) );

			if ( $settings ) {
				wp_add_inline_script( 
					'code-editor', 
					sprintf( 
						'jQuery(document).ready(function($) { wp.codeEditor.initialize( $("#custom_css_0"), %s ); } );', 
						wp_json_encode( $settings ) 
					) 
				);
			}
		}
		
	}
	
	
	
	public function wbc_admin_notices() {
		
		if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			
			?>
			<div class="notice notice-error">
				<p><?= __( 'Please install and activate the <strong>WooCommerce</strong> plugin.', 'brand-coupons-for-woocommerce' ) ?></p>
			</div>
			<?php
			
		}
		
		if ( ! is_plugin_active( 'perfect-woocommerce-brands/perfect-woocommerce-brands.php' ) ) {
			
			wp_enqueue_style( 'thickbox' );
			wp_enqueue_script( 'plugin-install' );
			
			$pwb_plugin_url = admin_url( 'plugin-install.php?tab=plugin-information&plugin=perfect-woocommerce-brands&TB_iframe=true&width=600&height=550' );
			
			?>
			<div class="notice notice-error">
				<p><?= sprintf( __( 'Please install and activate the <strong><a class="thickbox open-plugin-details-modal" href="%s" target="_blank">Perfect Brands for WooCommerce</a></strong> plugin.', 'brand-coupons-for-woocommerce' ), $pwb_plugin_url ) ?></p>
			</div>
			<?php
			
		}
		
	}
	
	
	public function wbc_add_plugin_page() {
		add_menu_page(
			'Brand Coupons for WooCommerce', // page_title
			'Brand Coupons', // menu_title
			'manage_options', // capability
			'woocommerce-brand-coupons', // menu_slug
			array( $this, 'wbc_create_admin_page' ), // function
			'dashicons-tickets-alt', // icon_url
			100 // position
		);
	}

	public function wbc_create_admin_page() {
		
		wp_enqueue_style( 'thickbox' );
		wp_enqueue_script( 'plugin-install' );
		
		$pwb_plugin_url = admin_url( 'plugin-install.php?tab=plugin-information&plugin=perfect-woocommerce-brands&TB_iframe=true&width=600&height=550' );
		$wc_coupons_url = admin_url( 'edit.php?post_type=shop_coupon' );

		?>

		<div class="wrap">
			<h2>Brand Coupons for WooCommerce</h2>
			<p>
				<?php
					_e( 'This plugin displays your brand-restricted discount coupons automatically on the product pages of your WooCommerce store.', 'brand-coupons-for-woocommerce' );
				?>
			</p>
			<ol>
				<li><?= sprintf( __( 'Install and activate the "<a class="thickbox open-plugin-details-modal" href="%s" target="_blank">Perfect Brands for WooCommerce</a>" plugin.', 'brand-coupons-for-woocommerce' ), $pwb_plugin_url ) ?></li>
				<li><?= __( 'Add new brands and assign them to your products.', 'brand-coupons-for-woocommerce' ) ?></li>
				<li><?= sprintf( __( 'Create discount <a href="%s">Coupons</a> with brand restrictions.', 'brand-coupons-for-woocommerce' ), $wc_coupons_url ) ?></li>
				<li><?= __( 'Promotional banners will automatically be displayed on product pages, including the code and description of each coupon.', 'brand-coupons-for-woocommerce' ) ?></li>
			</ol>
			<hr style="margin: 30px 0;">
			
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'wbc_options' );
					do_settings_sections( 'wbc-admin' );
					submit_button();
				?>
			</form>
		</div>

		<?php
		
	}

	public function wbc_page_init() {
		
		register_setting(
			'wbc_options', // option_group
			'wbc_options', // option_name
			array( $this, 'wbc_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'wbc_setting_section_css_selectors', // id
			esc_attr__( 'CSS Selectors', 'brand-coupons-for-woocommerce' ), // title
			array( $this, 'wbc_section_css_selectors_info' ), // callback
			'wbc-admin' // page
		);

		add_settings_field(
			'regular_price_0', // id
			esc_attr__( 'Regular Price', 'brand-coupons-for-woocommerce' ), // title
			array( $this, 'regular_price_0_callback' ), // callback
			'wbc-admin', // page
			'wbc_setting_section_css_selectors' // section
		);

		add_settings_field(
			'sale_price_1', // id
			esc_attr__( 'Sale Price', 'brand-coupons-for-woocommerce' ), // title
			array( $this, 'sale_price_1_callback' ), // callback
			'wbc-admin', // page
			'wbc_setting_section_css_selectors' // section
		);

		add_settings_field(
			'variation_price_2', // id
			esc_attr__( 'Variation Price', 'brand-coupons-for-woocommerce' ), // title
			array( $this, 'variation_price_2_callback' ), // callback
			'wbc-admin', // page
			'wbc_setting_section_css_selectors' // section
		);

		add_settings_section(
			'wbc_setting_section_customize', // id
			esc_attr__( 'Customize', 'brand-coupons-for-woocommerce' ), // title
			array( $this, 'wbc_section_customize_info' ), // callback
			'wbc-admin' // page
		);

		add_settings_field(
			'custom_css_0', // id
			esc_attr__( 'Custom CSS', 'brand-coupons-for-woocommerce' ), // title
			array( $this, 'custom_css_0_callback' ), // callback
			'wbc-admin', // page
			'wbc_setting_section_customize' // section
		);
		
	}

	public function wbc_sanitize($input) {
		
		$sanitary_values = array();
		
		if ( isset( $input['regular_price_0'] ) && !empty( $input['regular_price_0'] ) ) {
			$sanitary_values['regular_price_0'] = sanitize_text_field( $input['regular_price_0'] );
		}

		if ( isset( $input['sale_price_1'] ) && !empty( $input['sale_price_1'] ) ) {
			$sanitary_values['sale_price_1'] = sanitize_text_field( $input['sale_price_1'] );
		}

		if ( isset( $input['variation_price_2'] ) && !empty( $input['variation_price_2'] ) ) {
			$sanitary_values['variation_price_2'] = sanitize_text_field( $input['variation_price_2'] );
		}

		if ( isset( $input['custom_css_0'] ) && !empty( $input['custom_css_0'] ) ) {
			$sanitary_values['custom_css_0'] = sanitize_textarea_field( $input['custom_css_0'] );
		}

		return $sanitary_values;
		
	}

	public function wbc_section_css_selectors_info() {
		_e( 'Customize the CSS selectors to properly get the base price of your products.', 'brand-coupons-for-woocommerce' );
	}

	public function wbc_section_customize_info() {
		_e( 'Add your own CSS code in order to customize the styles of the banner displayed on the product page.', 'brand-coupons-for-woocommerce' );
	}

	public function regular_price_0_callback() {
		printf(
			'<input class="regular-text" type="text" name="wbc_options[regular_price_0]" id="regular_price_0" value="%s" placeholder="%s">',
			isset( $this->wbc_options['regular_price_0'] ) ? esc_attr( $this->wbc_options['regular_price_0'] ) : '',
			$this->defaults['selectors']['regular_price']
		);
	}

	public function sale_price_1_callback() {
		printf(
			'<input class="regular-text" type="text" name="wbc_options[sale_price_1]" id="sale_price_1" value="%s" placeholder="%s">',
			isset( $this->wbc_options['sale_price_1'] ) ? esc_attr( $this->wbc_options['sale_price_1'] ) : '',
			$this->defaults['selectors']['sale_price']
		);
	}

	public function variation_price_2_callback() {
		printf(
			'<input class="regular-text" type="text" name="wbc_options[variation_price_2]" id="variation_price_2" value="%s" placeholder="%s">',
			isset( $this->wbc_options['variation_price_2'] ) ? esc_attr( $this->wbc_options['variation_price_2'] ) : '',
			$this->defaults['selectors']['variation_price']
		);
	}

	public function custom_css_0_callback() {
		$content = isset( $this->wbc_options['custom_css_0'] ) ? $this->wbc_options['custom_css_0'] : '';
		
		echo '<textarea class="large-text code" name="wbc_options[custom_css_0]" id="custom_css_0" rows="10" cols="50">' . esc_textarea( $content ) . '</textarea>';
	}

}
