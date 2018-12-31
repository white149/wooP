<?php 
add_action( 'woocommerce_email_after_order_table', 'add_order_trackingnumber', 10, 4 );
function add_order_trackingnumber( $order, $sent_to_admin, $plain_text, $email) {
	if( ! ( 'customer_completed_order' == $email->id ) ) return;
	if ( ! $sent_to_admin ) {
		$tracking_Arr = get_field('tracking_group', $order->ID);
		//print_r($tracking_Arr);
		echo '<p>Your order has been shipped by ';
		foreach ($tracking_Arr as $tracking_info) {
			$trackingNumber = $tracking_info['tracking_number'];
			$carrierName = $tracking_info['shipping_carrier'];
			switch ($carrierName) {
				case 'fedex':
				printf('<span>FedEx: </span><a target="_blank" href="https://www.fedex.com/apps/fedextrack/?tracknumbers=%s">%s</a> ', $trackingNumber,$trackingNumber);
					break;

				case 'ups':
					printf('<span>UPS: </span><a target="_blank" href="http://wwwapps.ups.com/WebTracking/track?track=yes&trackNums=%s">%s</a> ', $trackingNumber,$trackingNumber);
					break;
				
				case 'usps':
				printf('<span>USPS: </span><a target="_blank" href="https://tools.usps.com/go/TrackConfirmAction?tLabels=%s">%s</a> ', $trackingNumber,$trackingNumber);
					break;
			}
		}
		echo '</p>';
 	}
}

function tracking_number_order_column($columns)
{
    $new_columns = array();
    foreach ($columns as $column_name => $column_info) {
        $new_columns[$column_name] = $column_info;
        if ('order_total' === $column_name) {
            $new_columns['has_tracking'] = 'Tracking';
        }
    }
    return $new_columns;
}
add_filter('manage_edit-shop_order_columns', 'tracking_number_order_column');


function cw_add_order_profit_column_content( $column ) {
    global $post;
	$tracking_Arr = get_field('tracking_group',$post->ID);
	
    if ( 'has_tracking' === $column && $tracking_Arr) {
		echo '<style>#has_tracking {width:15%} .tracking-number{margin:5px; padding:5px 5px 5px 15px; width:90%; max-width:260px;} .tracking-number strong{ width: 23%;} .tracking-number a { width:100%; height:100%}</style>';
		foreach ($tracking_Arr as $tracking_info) {
			$trackingNumber = $tracking_info['tracking_number'];
			$carrierName = $tracking_info['shipping_carrier'];
			if (get_field('tracking_group',$post->ID) != ''){
				switch ($carrierName) {
					case 'fedex':
					printf('<div class="order-status status-processing tracking-number"><strong>FedEx: </strong><a target="_blank" href="https://www.fedex.com/apps/fedextrack/?tracknumbers=%s">%s</a></div>', $trackingNumber,$trackingNumber);
						break;
	
					case 'ups':
						printf('<div class="order-status status-processing tracking-number"><strong>UPS: </strong><a target="_blank" href="http://wwwapps.ups.com/WebTracking/track?track=yes&trackNums=%s">%s</a></div> ', $trackingNumber,$trackingNumber);
						break;
					
					case 'usps':
					printf('<div class="order-status status-processing tracking-number"><strong>USPS: </strong><a target="_blank" href="https://tools.usps.com/go/TrackConfirmAction?tLabels=%s">%s</a></div> ', $trackingNumber,$trackingNumber);
						break;
					default:
					echo '';
				}
			}
		}
		
        
    }
}
add_action( 'manage_shop_order_posts_custom_column', 'cw_add_order_profit_column_content' );


function cs_add_order_again_to_my_orders_actions( $actions, $order ) {
	
	$tracking_Arr = get_field('tracking_group',$order->ID);

	if ( $order->has_status( 'completed' ) ) {
		
		//echo '<p>Your order has been shipped by ';
		foreach ($tracking_Arr as $tracking_info) {
			$trackingNumber = $tracking_info['tracking_number'];
		$carrierName = $tracking_info['shipping_carrier'];
			if (get_field('tracking_group',$order->ID) != ''){
				switch ($carrierName) {
					case 'fedex':
						printf('<a target="_blank" class="woocommerce-button button view" href="http://wwwapps.ups.com/WebTracking/track?track=yes&trackNums=%s">Track</a>',$trackingNumber);
						break;

					case 'ups':
						printf('<a target="_blank" class="woocommerce-button button view" href="http://wwwapps.ups.com/WebTracking/track?track=yes&trackNums=%s">Track</a>',$trackingNumber);
						break;
					
					case 'usps':
						printf('<a target="_blank" class="woocommerce-button button view" href="https://tools.usps.com/go/TrackConfirmAction?tLabels=%s">Track</a>',$trackingNumber);
						break;
					default:
						echo '';
				}
		}
	}
		$actions['order-again'] = array(
			'url'  => wp_nonce_url( add_query_arg( 'order_again', $order->id ) , 'woocommerce-order_again' ),
			'name' => __( 'Order Again', 'woocommerce' )
		);
	}
	return $actions;	
	}

	
add_filter( 'woocommerce_my_account_my_orders_actions', 'cs_add_order_again_to_my_orders_actions', 10, 2);


?>