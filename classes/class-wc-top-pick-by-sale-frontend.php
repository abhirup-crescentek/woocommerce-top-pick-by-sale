<?php

/**
 * @version		1.0.0
 * @package		woocommerce-top-pick-by-sale
 */

class WC_Top_Pick_By_Sale_Frontend {

	public function __construct() {
		
		if ( get_wctpbs_plugin_settings( 'enable_show_order_count' ) ) {
			add_action( 'woocommerce_single_product_summary', [ $this, 'wctp_product_sold_count' ], 15 );
		}
	}
  
	public function wctp_product_sold_count() {
		global $product;
		$category_id = get_wctpbs_plugin_settings( 'top_pick_category' ) ? get_wctpbs_plugin_settings( 'top_pick_category' )['value'] : '';
		if ( $category_id ) {
            $default_cat = get_term( $category_id, 'product_cat' );
            $cat_slug = $default_cat && !is_wp_error($default_cat) ? $default_cat->slug : '';
			if ( has_term( $cat_slug, 'product_cat' ) ) {
				$days = get_wctpbs_plugin_settings( 'order_in_days' , '7' );
				$all_orders = wc_get_orders(
					array(
						'limit' => -1,
						'status' => array_map( 'wc_get_order_status_name', wc_get_is_paid_statuses() ),
						'date_after' => date( 'Y-m-d', strtotime( '-'.$days.' days' ) ),
						'return' => 'ids',
					)
				);

				$count = 0;
				foreach ( $all_orders as $all_order ) {
					$order = wc_get_order( $all_order );
					$items = $order->get_items();
					foreach ( $items as $item ) {
						$product_id = $item->get_product_id();
						if ( $product_id == $product->get_id() ) {
							$count = $count + absint( $item['qty'] ); 
						}
					}
				}
				$minimum_order = get_wctpbs_plugin_settings( 'minimum_order_of_product', 0 );
					
				if ( $count >= $minimum_order ) {
					$default_massages = wc_top_pick_by_sale_default_massages();
					$row_massage = get_wctpbs_plugin_settings( 'shown_order_count_text' , $default_massages['shown_order_count_text'] );
					$shown_order_text = str_replace( "%day_count%", $days, $row_massage );
					$shown_order_text = str_replace( "%order_count%", $count, $shown_order_text );
					echo "<p>$shown_order_text</p>";
				}
			}
		}
	}
}