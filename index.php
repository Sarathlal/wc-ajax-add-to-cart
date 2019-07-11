<?php
/*
Plugin Name: ThemeHigh ajax add to cart
Plugin URI: https://wordpress.org/plugins/health-check/
Description: Checks the health of your WordPress install
Version: 0.1.0
Author: The Health Check Team
Author URI: http://health-check-team.example.com
Text Domain: health-check
Domain Path: /languages
*/


if(!defined('WPINC')){	die; }

if(!class_exists('Themehigh_Ajax')):

    class Themehigh_Ajax {

        //private $version;

        public function __construct() {
            //$this->version = $version;
            add_action('wp_enqueue_scripts', array($this, 'enqueue_styles_and_scripts'));
            $this->init();
            add_action('init', array($this, 'write_log'));

            add_action( 'wp_ajax_themehigh_ajax_add_to_cart', array($this, 'handle_add_to_cart') );
            add_action( 'wp_ajax_nopriv_themehigh_ajax_add_to_cart', array($this, 'handle_add_to_cart') );



        }

        public function init(){
    		$this->define_constants();
    		//$this->init_auto_updater();
    	}

        public static function write_log ( $log )  {
    		if ( true === WP_DEBUG ) {
    			if ( is_array( $log ) || is_object( $log ) ) {
    				error_log( print_r( $log, true ) );
    			} else {
    				error_log( $log );
    			}
    		}
    	}

    	private function define_constants(){
            global $woocommerce;
    		!defined('TH_ASSETS_URL') && define('TH_ASSETS_URL', plugin_dir_url( __FILE__ ) . '/assets/');
    		//!defined('THLM_ASSETS_URL_PUBLIC') && define('THLM_ASSETS_URL_PUBLIC', THLM_URL . 'assets/public/');
    		!defined('TH_WOO_ASSETS_URL') && define('TH_WOO_ASSETS_URL', WC()->plugin_url() . '/assets/');
    	}


        public function enqueue_styles_and_scripts() {

    		// if(strpos($hook, 'toplevel_page_customize-th-theme') === false) {
    		// 	return;
    		// }
    		$debug_mode = apply_filters('thlm_debug_mode', true);
    		$suffix = $debug_mode ? '' : '.min';

    		$this->enqueue_styles($suffix);
    		$this->enqueue_scripts($suffix);
    	}

    	private function enqueue_styles($suffix) {
    		//wp_enqueue_style('jquery-ui-style', '//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css?ver=1.11.4');
    		//wp_enqueue_style('woocommerce_admin_styles', THLM_WOO_ASSETS_URL.'css/admin.css');
    		//wp_enqueue_style('wp-color-picker');
    		wp_enqueue_style('th-style', TH_ASSETS_URL . 'css/th-ajax'. $suffix .'.css', false);
    	}

    	private function enqueue_scripts($suffix) {
    		$deps = array('jquery');

    		wp_enqueue_script('th-script', TH_ASSETS_URL . 'js/th-ajax'. $suffix .'.js', $deps, false, false);

    		$thlm_var = array(
                'admin_url' => admin_url(),
                'ajaxurl'   => admin_url( 'admin-ajax.php' ),
                'notice_wrapper' => apply_filters('th_ajax_add_to_cart_notice_wrapper', '.woocommerce-notices-wrapper'),
            );
    		wp_localize_script('th-script', 'th_var', $thlm_var);
            wp_enqueue_script( 'wc-add-to-cart' );

            if ( 'yes' != get_option( 'woocommerce_enable_ajax_add_to_cart' ) ) { 
                $this->write_log('woocommerce_enable_ajax_add_to_cart disabled');
                $this->write_log( WC()->plugin_url());
    			//self::enqueue_script( 'wc-add-to-cart' );
    		}

            // 'wc-add-to-cart'             => array(
			// 	'src'     => self::get_asset_url( 'assets/js/frontend/add-to-cart' . $suffix . '.js' ),
			// 	'deps'    => array( 'jquery', 'jquery-blockui' ),
			// 	'version' => WC_VERSION,
			// ),




    	}

        public function init_customizer(){

            $customizer_layout =  new THTC_Customizer_Layout;
            $customizer_header = new THTC_Customizer_Header;
            $customizer_blog = new THTC_Customizer_Blog;

        }


        public function handle_add_to_cart(){

            //public static function add_to_cart() {
        		//ob_start();

        		// phpcs:disable WordPress.Security.NonceVerification.NoNonceVerification
        		if ( ! isset( $_POST['product_id'] ) ) {
        			return;
        		}

                $mini_cart = '';
                $notices_html = '';


        		$product_id        = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
        		$product           = wc_get_product( $product_id );
        		$quantity          = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( wp_unslash( $_POST['quantity'] ) );
        		$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
        		$product_status    = get_post_status( $product_id );
        		$variation_id      = 0;
        		$variation         = array();

        		if ( $product && 'variation' === $product->get_type() ) {
        			$variation_id = $product_id;
        			$product_id   = $product->get_parent_id();
        			$variation    = $product->get_variation_attributes();
        		}

                $this->write_log('add to cart reached');
                $this->write_log('add to cart reached');
                $this->write_log('add to cart reached');

                $this->write_log($_POST);

        		if ( $passed_validation && false !== WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation ) && 'publish' === $product_status ) {

                    $this->write_log('add to cart reached');

        			do_action( 'woocommerce_ajax_added_to_cart', $product_id );

        			//if ( 'yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
        			wc_add_to_cart_message( array( $product_id => $quantity ), true );

                    //wp_send_json( $mini_cart_html );

        		} else {

        			// If there was an error adding to the cart, redirect to the product page to show any errors.
        			// $data = array(
        			// 	'error'       => true,
        			// 	'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id ),
        			// );

        		}


            $all_notices  = WC()->session->get( 'wc_notices', array() );
            $notice_types = apply_filters( 'woocommerce_notice_types', array( 'error', 'success', 'notice' ) );

            // Buffer output.
            ob_start();

            foreach ( $notice_types as $notice_type ) {
                if ( wc_notice_count( $notice_type ) > 0 ) {
                    wc_get_template( "notices/{$notice_type}.php", array(
                        'messages' => array_filter( $all_notices[ $notice_type ] ),
                    ) );
                }
            }

            wc_clear_notices();

            $notices = wc_kses_notice( ob_get_clean() );

            //$this->write_log($notices);
            $notices_html = "<span id='th-wooajax-notice-pointer' ></span>" . $notices;

            $mini_cart = self::get_refreshed_fragments();

            $result = array(
                'notices' => $notices_html,
                'mini_cart' => $mini_cart
            );


            wp_send_json( $result );

        }


        public static function get_refreshed_fragments() {
        global $woocommerce;
    		ob_start();

    		woocommerce_mini_cart();

    		$mini_cart = ob_get_clean();

    		$data = array(
    			'fragments' => apply_filters(
    				'woocommerce_add_to_cart_fragments',
    				array(
    					'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
    				)
    			),
    			'cart_hash' => WC()->cart->get_cart_hash(),
    		);

    		return $data;
    	}


    }

endif;

$ajax = new Themehigh_Ajax;
