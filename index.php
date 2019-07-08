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

            add_action( 'wp_ajax_themehigh_ajax_add_to_cart', array($this, 'my_action') );
            add_action( 'wp_ajax_nopriv_themehigh_ajax_add_to_cart', array($this, 'my_action') );

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
    		!defined('TH_ASSETS_URL') && define('TH_ASSETS_URL', plugin_dir_url( __FILE__ ) . '/assets/');
    		//!defined('THLM_ASSETS_URL_PUBLIC') && define('THLM_ASSETS_URL_PUBLIC', THLM_URL . 'assets/public/');
    		//!defined('THLM_WOO_ASSETS_URL') && define('THLM_WOO_ASSETS_URL', WC()->plugin_url() . '/assets/');
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
            );
    		wp_localize_script('th-script', 'th_var', $thlm_var);
    	}

        public function init_customizer(){

            $customizer_layout =  new THTC_Customizer_Layout;
            $customizer_header = new THTC_Customizer_Header;
            $customizer_blog = new THTC_Customizer_Blog;

        }

    }

endif;

$ajax = new Themehigh_Ajax;
