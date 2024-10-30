<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.ampersandfactory.com/
 * @since      1.0.0
 *
 * @package    Woocommerce_Brand_Coupons
 * @subpackage Woocommerce_Brand_Coupons/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Woocommerce_Brand_Coupons
 * @subpackage Woocommerce_Brand_Coupons/public
 * @author     Ampersand Factory <info@ampersandfactory.com>
 */
class Woocommerce_Brand_Coupons_Public {

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


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $woocommerce_brand_coupons       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $woocommerce_brand_coupons, $version, $defaults ) {

		$this->woocommerce_brand_coupons = $woocommerce_brand_coupons;
		$this->version = $version;
		
		$this->defaults = $defaults;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->woocommerce_brand_coupons, plugin_dir_url( __FILE__ ) . 'css/woocommerce-brand-coupons-public.css', array(), $this->version, 'all' );
		
		
		$wbc_options = get_option( 'wbc_options' );
		
		$custom_css = isset( $wbc_options['custom_css_0'] ) ? $wbc_options['custom_css_0'] : '';
		
		wp_add_inline_style( $this->woocommerce_brand_coupons, $custom_css );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->woocommerce_brand_coupons, plugin_dir_url( __FILE__ ) . 'js/woocommerce-brand-coupons-public.js', array( 'jquery' ), $this->version, false );

	}



	public function wbc_after_add_to_cart_button() {

		$args = array(
			'posts_per_page'   => -1,
			'orderby'          => 'name',
			'order'            => 'asc',
			'post_type'        => 'shop_coupon',
			'post_status'      => 'publish',
		);
		
		$coupon_posts = get_posts( $args );
		
		$output = '';
		
		if ( ! empty( $coupon_posts ) ) {
			
			foreach ( $coupon_posts as $coupon_post ) {

				$coupon_code = $coupon_post->post_name;

				$coupon = new WC_Coupon( $coupon_code );

				$coupon_brands = $coupon->get_meta( '_pwb_coupon_restriction' );
				
				if ( ! empty( $coupon_brands ) ) {
					
					foreach ( $coupon_brands as $coupon_brand ) {

						if ( has_term( $coupon_brand, 'pwb-brand' ) ) {

							$coupon_code = $coupon->get_code();
							$coupon_description = $coupon->get_description();
							$coupon_discount_type = $coupon->get_discount_type();
							$coupon_amount = $coupon->get_amount();

							$wbc_banner_info = __( 'Enter this code in your cart to apply the discount.', 'brand-coupons-for-woocommerce' );

							$output = '
							<script type="text/javascript">
								var wbc_coupon_discount_type = "' . $coupon_discount_type . '";
								var wbc_coupon_amount = ' . $coupon_amount . ';
							</script>

							<div class="wbc_banner">
								<div class="wbc_banner_description">' . $coupon_description . '</div>
								<div class="wbc_banner_code">' . $coupon_code . '</div>
								<div class="wbc_banner_price_container">
									<span class="wbc-icon wbc-icon-arrow-right"></span> <span class="wbc_banner_price"></span>
								</div>
								<div class="wbc_banner_info"><span class="wbc-icon wbc-icon-info-circle"></span>' . $wbc_banner_info . '</div>
							</div>
							';

						}

					}
					
				}
				
			}
			
		}
		
		if ( ! empty( $output ) ) {

			add_action( 'wp_print_footer_scripts', array( $this, 'wbc_footer_scripts' ) );

		}
		
		echo $output;	
		
	}

	public function wbc_footer_scripts() {
		
		if ( wp_script_is( 'jquery', 'done' ) ) {
			
			$wbc_options = get_option( 'wbc_options' );
			
			$wbc_regular_price_selector = isset( $wbc_options['regular_price_0'] ) ? 
				esc_attr( $wbc_options['regular_price_0'] ) : $this->defaults['selectors']['regular_price'];
			$wbc_sale_price_selector = isset( $wbc_options['sale_price_1'] ) ? 
				esc_attr( $wbc_options['sale_price_1'] ) : $this->defaults['selectors']['sale_price'];
			$wbc_variation_price_selector = isset( $wbc_options['variation_price_2'] ) ? 
				esc_attr( $wbc_options['variation_price_2'] ) : $this->defaults['selectors']['variation_price'];
			
			?>
			
			<script type="text/javascript">
				
				var wbc_regular_price_selector = "<?= $wbc_regular_price_selector ?>";
				var wbc_sale_price_selector = "<?= $wbc_sale_price_selector ?>";
				var wbc_variation_price_selector = "<?= $wbc_variation_price_selector ?>";
				
				function round(value, decimals) {
					return Number(Math.round(value+"e"+decimals)+"e-"+decimals);
				}
				
				jQuery(document).ready(function($) {
					
					if ( $(wbc_sale_price_selector).length ) {
						var wbc_original_price_selector = wbc_sale_price_selector;
					} else {
						var wbc_original_price_selector = wbc_regular_price_selector;
					}
					
					function generatePrice() {
						
						var wbc_original_price = $(wbc_original_price_selector).first().text();
					
						var wbc_original_price_amount = $(wbc_original_price_selector).first().clone().children().remove().end().text();
						
						var wbc_currency_symbol = $(wbc_original_price_selector + " .woocommerce-Price-currencySymbol").first().text();
						
						if (wbc_original_price.indexOf(wbc_currency_symbol) == 0) {
							var wbc_currency_symbol_position = "left";
						} else {
							var wbc_currency_symbol_position = "right";
						}
						
						if (wbc_original_price_amount.indexOf("\xa0") >= 0) {
							var wbc_currency_symbol_space = " ";
						} else {
							var wbc_currency_symbol_space = "";
						}
						
						wbc_original_price_amount = wbc_original_price_amount.trim();
						
						if (wbc_original_price_amount.charAt(wbc_original_price_amount.length-3) == ",") {
							var wbc_decimal_separator = ",";
							wbc_original_price_amount = wbc_original_price_amount.replace(".", "").replace(",", ".");
						} else {
							var wbc_decimal_separator = ".";
							wbc_original_price_amount = wbc_original_price_amount.replace(",", "");
						}
						
						wbc_original_price_amount = parseFloat(wbc_original_price_amount);
						
						if (wbc_coupon_discount_type == "percent") {
							var wbc_discount_price_amount = wbc_original_price_amount - (wbc_original_price_amount * (wbc_coupon_amount / 100));
						} else {
							var wbc_discount_price_amount = wbc_original_price_amount - wbc_coupon_amount;
						}
						
						wbc_discount_price_amount = round(wbc_discount_price_amount, 2);
						wbc_discount_price_amount = wbc_discount_price_amount.toFixed(2);
						
						if (wbc_decimal_separator == ",") {
							wbc_discount_price_amount = wbc_discount_price_amount.replace(".", ",");
						}
						
						if (wbc_currency_symbol_position == "left") {
							var wbc_discount_price = wbc_currency_symbol + wbc_currency_symbol_space + wbc_discount_price_amount;
						} else {
							var wbc_discount_price = wbc_discount_price_amount + wbc_currency_symbol_space + wbc_currency_symbol;
						}
						
						var wbc_icon_arrow_right = '<span class="wbc-icon wbc-icon-arrow-right"></span>';

						$(".wbc_banner_price_container").html(wbc_icon_arrow_right + ' <span class="wbc_banner_price">' + wbc_discount_price + '</span>');

					}
					
					if ( $(wbc_original_price_selector).length ) {
						generatePrice();
					}
					
					$(".woocommerce div.product form.cart .variations select").one("click", function() {
						var wbc_update_price = setInterval(function() {
							
							wbc_original_price_selector = wbc_variation_price_selector;
							
							if ( $(wbc_original_price_selector).length ) {
								generatePrice();
							}
							
						}, 1500);
					});
					
				});
			
			</script>

			<?php

		}

	}

}
