<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
//acf setting for row index start from "0"
function acf_init_setting() {
	acf_update_setting('row_index_offset', 0);
	//acf_update_setting('show_admin', 0);
}
add_action('acf/init', 'acf_init_setting');

add_filter( 'woocommerce_get_item_data', 'displaying_cart_items_weight', 11, 2 );
function displaying_cart_items_weight( $item_data, $cart_item ) {

    $item_id = $cart_item['variation_id'];
    if($item_id == 0) $item_id = $cart_item['product_id'];
    $product_qty = $cart_item['quantity'];
    $product = wc_get_product($item_id);
    $weight_value = $cart_item['wpa_values']['wpa_pkg_weight'];
    $weight_display = $weight_value . ' ' . get_option('woocommerce_weight_unit');

    $item_data[] = array(
        'key'       => __('Weight', 'woocommerce'),
        'value'     => $weight_value,
        'display'   => $weight_display
    );
	//print_r($cart_item);
    return $item_data;
}

require_once('class-product-options.php');
// add_shortcode('wpa_product_customize', 'wpa_product_customize_code');
// function wpa_product_customize_code($atts) {
    
// print_r($_POST);

// }

add_action( 'woocommerce_before_add_to_cart_form', 'wpa_product_customize_button', 5 );
function wpa_product_customize_button() {
    $postID = get_the_ID();
    
    
    echo '<form class="customize" action="/customize/" method="post" enctype="multipart/form-data">';
    echo '<button type="submit" name="customize" value="'.$postID.'" class="single_add_to_cart_button button alt">Customize</button>';
    echo '</form>';
}

$miao = new wpa_product_options();



//frontpage options start
// add_filter('woocommerce_before_add_to_cart_button', 'wpa_options_frontend');
// function wpa_options_frontend(){	
// 	$postID = get_the_ID();
// 	$product = wc_get_product( $postID );
// 	$product_min_price = $product -> get_price();

// 	echo '<div id="loadingFilm"></div>';//ajax Loading cover

// 	//$product_size_on = get_field('product_size_on');
// 	$option_type = get_field('option_type');
// 	if($option_type == 'by_size'){
// 		if(have_rows('product_size')){//product size options output
// 			$product_size = get_field('product_size');
// 			$max_width_foot = $product_size['max_width']/12;
// 			$max_height_foot = $product_size['max_height']/12;
// 			$min_width_foot = $product_size['min_width']/12;
// 			$min_height_foot = $product_size['min_height']/12;
// 			$width_step_size = $product_size['width_step_size'];
// 			$height_step_size = $product_size['height_step_size'];
// 			echo '<div>';
// 			echo '<div class="wpa_size_input"><span class="col-sm-3 wpa_size_title">Width Foot</span><input type="number" min="',$min_width_foot,'" max="',floor($max_width_foot),'" class="col-sm-3 wpa_size wpa_width_foot" value="1" />';
// 			echo '<span class="col-sm-3 wpa_size_title">Inch</span><select class="col-sm-3 wpa_size wpa_width_inch" type="number" value="">';
// 			//echo $step_size;
// 			for ($i=0; $i < 12; $i+=$width_step_size) { 
// 				echo '<option value="',$i,'">',$i,'</option>';
// 			}
// 			echo '</select></div>';
// 			echo '<div class="wpa_size_input"><span class="col-sm-3 wpa_size_title">Height Foot</span><input type="number" min="',$min_height_foot,'" max="',floor($max_height_foot),'" class="col-sm-3 wpa_size wpa_height_foot" value="1" />';
// 			echo '<span class="col-sm-3 wpa_size_title">Inch</span><select class="col-sm-3 wpa_size wpa_height_inch" type="number" value="" />';
// 			for ($i=0; $i < 12; $i+=$height_step_size) { 
// 				echo '<option value="',$i,'">',$i,'</option>';
// 			}
// 			echo '</select></div>';
// 			echo '<div class="wpa_size_calculator"><p><span class="wpa_size_display_width">12</span>" x <span class="wpa_size_display_height">12</span>" =  <span class="wpa_size_display_area">1.00</span>ft<sup>2</sup></p><p>Note: The maximum value for this product are ',$max_width_foot*12,'" x ',$max_height_foot*12,'".</p></div>';
// 			echo '</div>';
// 		};
// 	};
	

// 	echo get_field('start_code');
// 	//select options start
// 	if(have_rows('group')){
// 		while( have_rows('group') ){// normal drop down options
// 			the_row();
// 			$row_index = get_row_index();//Group index number
// 			if(get_sub_field('parent_id')){
// 				echo '<div parent="',get_sub_field('parent_id'),'"  opt_id="',$row_index,'" class="wpa_dropdown_group">';
// 			}else {
// 				echo '<div class="wpa_dropdown_group">';
// 			}
			
// 			echo '<div class="col-sm-4 wpa_option_name">',get_sub_field('group_name'),'</div>';
// 			echo '<select opt_id="',$row_index,'" name="_wpa_options[',get_sub_field('group_name'),']" class="col-sm-8 wpa_option_select selection_',$row_index,'">';
// 			while (have_rows('attributes')) {
// 				the_row();
// 				echo '<option value="',$row_index,get_row_index(),'">',get_sub_field('option_name'),'</option>';
// 			}
// 			echo '</select></div>';
// 		}
		

// 		if($option_type == 'by_qty'){// dropdown options when size option disabled
// 			$qty_group = get_field('qty_group');
// 			$value_1st = $qty_group[0]['qty_options'];
// 			$value_1st = explode('|', $value_1st);

// 			echo '<div class="wpa_dropdown_group dropdown_group_qty">';
// 			echo '<div class="col-sm-4 wpa_option_name">Qty</div>';
// 			echo '<select name="_wpa_values[wpa_qty]" num="num" class="col-sm-8 wpa_option_select_cp wpa_option_select_qty">';
// 			foreach ($value_1st as $value) {
// 				echo '<option value="">',$value,'</option>';
// 			}
// 			echo '</select></div>';
// 		}
// 		$turnaround_time = get_field('turnaround_time');
// 		//var_dump($turnaround_time);
// 		echo '<div class="wpa_dropdown_group dropdown_group_time">';
// 		echo '<div class="col-sm-4 wpa_option_name">Turnaround Time</div>';
// 		echo '<select name="_wpa_addons[wpa_ta_time]" class="col-sm-8 wpa_option_select wpa_option_select_time">';
		
// 		foreach ($turnaround_time as $value) {
// 			echo '<option value="',$value['label'],'">',$value['label'],'</option>';
// 		}
// 		echo '</select></div>';
// 		echo '<div class="trunaround-notice"><strong>All jobs next day turnaround, Cut off time 3pm PST<strong></div>';

// 	};

// 	echo get_field('ending_code');// output ending code field for custom codes;
// 	//select options end
// 	//hidden input tags
// 	echo '<div class="hidden_truth">';
// 	echo '<button type="button" class="wpa_calc_btn">Calc Price</button>';
// 	echo '<span>price</span><input class="wpa_price" name="_wpa_values[wpa_price]" value="'.$product->get_price().'" />';
// 	if($option_type == 'by_size'){
// 		echo '<input class="wpa_height" name="_wpa_values[wpa_height]" value="12" placeholder="height" />';
// 		echo '<input class="wpa_width" name="_wpa_values[wpa_width]" value="12" placeholder="width" />';
// 	}
// 	echo '<input class="wpa_pkg_weight" name="_wpa_values[wpa_pkg_weight]" value="'.$product->get_weight().'" placeholder="weight" />';
// 	echo '<input class="wpa_pkg_length" name="_wpa_values[wpa_pkg_length]" value="'.$product->get_length().'" placeholder="pkg_length" />';
// 	echo '<input class="wpa_pkg_width" name="_wpa_values[wpa_pkg_width]" value="'.$product->get_width().'" placeholder="pkg_width" />';
// 	echo '<input class="wpa_pkg_height" name="_wpa_values[wpa_pkg_height]" value="'.$product->get_height().'" placeholder="pkg_height" />';
// 	echo '</div>';

	
// 		//test area start
		
// 			//test area end
// }



//frontpage options end


//save cart item meta and order meta info start
























?>