var wpa_width_now;
var wpa_height_now;
//share with canvas

jQuery(document).ready(function($){
//prepare all values submit to ajax
//var wpa_orig_price_val = $('.wpa_price').val(); //get product original price
var wpa_post_id = $('button[name=add-to-cart]').val();
var wpa_option_val = new Array();
var wpa_product_data = new Object();

var wpa_area_sqin;

var wpa_product_fade = new Array();

jQuery('.wpa_dropdown_group[parent]').each(function(i,item){// get parent values and push to array
	opt_id = jQuery(item).attr('opt_id');
	par_ids = jQuery(item).attr('parent');	
	par_ids = par_ids.split('|');
		wpa_product_fade.push({
		opt_id : opt_id,
		par_ids : par_ids,
	});
});



//console.log(wpa_product_fade);

function process_n(par_id, opt_id){
	par_id = par_id.split('&');
	
	var count = 0;
	par_id.forEach(function(v,i,a){
		opt_id = v.substring(0,1);
		opt_selc = v.substring(1);
		if (opt_id == $('.selection_'+opt_id).attr('opt_id') && $('.selection_'+opt_id).val().substring(1) == opt_selc){
			count++;
		}
	});
	//console.log(count +'_'+ par_id.length);
	if(count == par_id.length){
		return true;
	} else {
		return false;
	}
}

function parents(){
	wpa_product_fade.forEach(function(value, i, array){
		opt_id = value.opt_id;
		par_ids = value.par_ids;
		if(par_ids.length > 1){
			par_ids.some(function(par_id) {
					show = process_n(par_id,opt_id);
					if (show){
						$('[opt_id='+ opt_id +']').show();
						$('[opt_id='+ opt_id +']').prop('disabled', false);
						return true;
					}else {
						$('[opt_id='+ opt_id +']').hide();
						$('[opt_id='+ opt_id +']').prop('disabled', true);
					}
			})
		} else{
			par_ids.forEach(function(par_id,i,arr){
				//if(par_id.includes('&')){
					show = process_n(par_id,opt_id);
					if (show){
						$('[opt_id='+ opt_id +']').show();
						$('[opt_id='+ opt_id +']').prop('disabled', false);
					}else {
						$('[opt_id='+ opt_id +']').hide();
						$('[opt_id='+ opt_id +']').prop('disabled', true);
					}
				})
		}
	});
}

if($('.wpa_is_size_option').length){
	applySizeToPreview()
};

function applySizeToPreview(){
	wpa_width_now = $('.wpa_is_size_option option:selected').attr('s_width');
	wpa_height_now = $('.wpa_is_size_option option:selected').attr('s_height');
	canvasHeight = $('.wpa-image-col').height() * 0.7;
	canvasWidth = $('.wpa-image-col').width() * 0.7;
	$('.wpa_size_display_width').text(wpa_width_now);
	$('.wpa_size_display_height').text(wpa_height_now);
	if(wpa_width_now > wpa_height_now){
		$('.wpa-canvas').width(canvasWidth);			
		canvasHeight = canvasWidth * (wpa_height_now / wpa_width_now );
		$('.wpa-canvas').height(canvasHeight);
	} else if(wpa_width_now < wpa_height_now){
		$('.wpa-canvas').height(canvasHeight);			
		canvasWidth = canvasHeight * (wpa_width_now / wpa_height_now);
		$('.wpa-canvas').width(canvasWidth);
	} else if (wpa_width_now == wpa_height_now){
		$('.wpa-canvas').height(canvasHeight);
		$('.wpa-canvas').width(canvasHeight);
	}
}

//input product size area value control
$(document).on('change', '.wpa_size_input, .wpa_option_select', function(){
	wpa_options();
	parents();
	applySizeToPreview();
	if($('.wpa_size_input').length){//check if product size options available
		if(wpa_width_now>wpa_max_width || wpa_height_now>wpa_max_height){
			//set all value to max
			$('.wpa_width_foot').val(Math.floor(wpa_max_width/12));
			$('.wpa_width_inch').val(0);
			$('.wpa_height_foot').val(Math.floor(wpa_max_height/12));
			$('.wpa_height_inch').val(0);
			$('.wpa_size_display_width').text(wpa_max_width);
			$('.wpa_size_display_height').text(wpa_max_height);
			$('.wpa_size_display_area').text(wpa_max_width*wpa_max_height/144);
			$('.wpa_width').val(wpa_max_width);
			wpa_product_data.wpa_width_now = wpa_max_width;//change ajax data back to the max value
			$('.wpa_height').val(wpa_max_height);
			wpa_product_data.wpa_height_now = wpa_max_height;
			
		} else {
		$('.wpa_size_display_width').text(wpa_width_now);
		$('.wpa_size_display_height').text(wpa_height_now);
		$('.wpa_size_display_area').text(parseFloat(wpa_area_sqin/144).toFixed(2));
		$('.wpa_width').val(wpa_width_now);
		$('.wpa_height').val(wpa_height_now);
		
		}

		

	}
	

	$('.wpa_calc_btn').click();
});

$(document).on('change', '.wpa_option_select_qty', function(){
	var result_price = $('option:selected', this).attr('price');
	var result_weight = $('option:selected', this).attr('weight');
	var result_length = $('option:selected', this).attr('length');
	$('.price .woocommerce-Price-amount').text('$' + result_price);
	$('.wpa_price').val(result_price);
	if(result_weight != 'undefined' || result_length != 'undefined'){
		$('.wpa_pkg_weight').val(result_weight);
		$('.wpa_pkg_length').val(result_length);
	}
	
});

function wpa_options() {
	if($('.wpa_size_input').length){
		
	wpa_width_now = parseFloat($('.wpa_width_foot').val())*12+parseFloat($('.wpa_width_inch').val());
	wpa_height_now = parseFloat($('.wpa_height_foot').val())*12+parseFloat($('.wpa_height_inch').val());
	wpa_area_sqin = wpa_height_now*wpa_width_now;
	wpa_perimeter_inch = (wpa_width_now+wpa_height_now)*2;
	wpa_product_data.wpa_width_now = wpa_width_now;
	wpa_product_data.wpa_height_now = wpa_height_now;
	} else{
		
	}
	wpa_product_data.wpa_post_id = wpa_post_id;
	wpa_product_data.wpa_pkg_weight = $('.wpa_pkg_weight').val();
	wpa_product_data.wpa_pkg_length = $('.wpa_pkg_length').val();
}

var preview_02;
$(document).on( 'click', '.wpa_calc_btn', function() {
	//get all selection value and save to wpa_option_val
	
	if($('.wpa_option_select [dbsides=1]:selected ').attr('dbsides') ==1){

		$('.canvas-control').css('display', 'block');
		$('.wpa-image-col').append(preview_02);
		

	} else {
		$('#wpa_preview_01').css('display', 'block');
		$('.canvas-control').css('display', 'none');
		preview_02 = $('#wpa_preview_02').detach();
		
	}

	$('.wpa_option_select').each(function(){		
		if($(this).prop('disabled') || $(this).attr('num')=="undefined" || $(this).attr('name') == '_wpa_addons[wpa_ta_time]'){
			wpa_option_val.push('x');
			
		}else {
			push_val = jQuery(this).val();
			wpa_option_val.push(push_val);
		}
		
	});
	console.log(wpa_option_val);
	jQuery.ajax({
		url : wpa_frontend_ajax.ajax_url,
		type : 'post',
		data : {
			action : 'wpa_options_calc',
			wpa_product_data : wpa_product_data,
			wpa_option_val : wpa_option_val,
		},
		success : function( response ) {
			console.log(response);
			var result = JSON.parse(response);
			wpa_option_val = [];//reset array for next time request
			//console.log(result);
			//set values back to input fields
			if($('.dropdown_group_qty').length){
				
				var price = result.wpa_price.split('|');
				var qty = result.wpa_qty.split('|');
				var weight = result.wpa_qty_pkg_weight.split('|');
				var length = result.wpa_qty_pkg_length.split('|');
				//console.log(price);
				$('.price .woocommerce-Price-amount').text('$' + price[0]);
				$('.wpa_price').val(price[0]);

				$('.wpa_option_select_qty').empty();
				for (let i = 0; i < price.length; i++) {
					$('.wpa_option_select_qty').append("<option length='"+length[i]+"' weight='"+weight[i]+"' value='"+qty[i]+"' price='"+price[i]+"'>"+qty[i]+"</option>");
				}
			} else {
				var result_price = Number.parseFloat(result.wpa_price_val).toFixed(2);
				$('.price .woocommerce-Price-amount').text('$' + result_price);
				$('.wpa_price').val(result_price);
			}
			$('.wpa_pkg_weight').val(result.wpa_pkg_weight);
			$('.wpa_pkg_length').val(result.wpa_pkg_length);
		}
	});
})


wpa_options();
parents();
$('.wpa_calc_btn').click();

//loading cover
$('#loadingFilm')
    .hide()  // Hide it initially
    .css({width: $('form.cart').width(),height: $('form.cart').height()})
    .ajaxStart(function() {
        $(this).show();
    })
    .ajaxStop(function() {
        $(this).hide();
});



});

