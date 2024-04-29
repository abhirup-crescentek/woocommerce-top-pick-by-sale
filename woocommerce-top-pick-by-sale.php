<?php
/**
 * Plugin Name: Woocommerce Top Pick By Sale
 * Plugin URI: https://wordpress.org/plugins/woocommerce-top-pick-by-sale
 * Description: Assign the top-selling products within a specified time frame to a chosen category. 
 * Author: Crescentek
 * Version: 1.0.0
 * Requires at least: 4.4
 * Tested up to: 6.2
 * WC requires at least: 3.0
 * WC tested up to: 7.7.0
 * Author URI: https://www.crescentek.com/
 * Text Domain: wc-top-pick-by-sale
 * Domain Path: /languages/
 */

if ( ! class_exists( 'WC_Top_Pick_By_Sale_Dependencies' ) )
	require_once 'classes/class-wc-top-pick-by-sale-dependencies.php';

require_once 'includes/wc-top-pick-by-sale-core-functions.php';
require_once 'includes/wc-top-pick-by-sale-setting-functions.php';
require_once 'config.php';
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! WC_Top_Pick_By_Sale_Dependencies::woocommerce_plugin_active_check() ) {
  add_action( 'admin_notices', 'woocommerce_inactive_notice' );
}

if ( ! class_exists( 'WC_Top_Pick_By_Sale' ) && WC_Top_Pick_By_Sale_Dependencies::woocommerce_plugin_active_check() ) {
    require_once('classes/class-wc-top-pick-by-sale.php');
    global $WC_Top_Pick_By_Sale;
    $WC_Top_Pick_By_Sale = new WC_Top_Pick_By_Sale( __FILE__ );
    $GLOBALS['WC_Top_Pick_By_Sale'] = $WC_Top_Pick_By_Sale;
    // Activation Hooks
    register_activation_hook( __FILE__, [ 'WC_Top_Pick_By_Sale', 'activate_wc_top_pick_by_sale' ] );
    // Deactivation Hooks
    register_deactivation_hook( __FILE__, [ 'WC_Top_Pick_By_Sale', 'deactivate_wc_top_pick_by_sale' ] );
}
