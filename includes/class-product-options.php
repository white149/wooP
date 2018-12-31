<?php

class wpa_product_options
{
    private $postID;

    public function __construct(){
        
        $this->postID = $_POST['customize'];
        add_action( 'init', array( &$this, 'init' ));
        //add_filter('woocommerce_before_add_to_cart_button', array( $this, 'wpa_options_frontend' ));
        add_shortcode('wpa_product_customize',  array( $this,'wpa_product_customize_code'));
        
    }
    function init(){
        
        add_action( 'woocommerce_add_cart_item_data', array( $this, 'save_custom_data_hidden_fields' ), 10, 2 );
        add_filter( 'woocommerce_get_cart_item_from_session', array($this, 'moe_get_cart_item_from_session'), 20, 2 );
        //array($this,
        add_filter( 'woocommerce_get_item_data', array($this,'moe_get_item_data'), 10, 2 );
        add_action( 'woocommerce_checkout_create_order_line_item', array($this,'custom_checkout_create_order_line_item'), 20, 4 );
        add_filter( 'woocommerce_hidden_order_itemmeta', array($this,'remove_order_item_meta_fields') );// hide item meta in admin side
        add_action( 'woocommerce_thankyou', array($this,'create_invoice_for_wc_order'), 10, 1 );//move files to correct place and rename, needs rebuild
        add_action( 'woocommerce_before_order_itemmeta', array($this, 'file_download_link_in_order_page'), 10, 3 );
        add_action( 'woocommerce_before_calculate_totals', array($this,'add_custom_price'), 10, 1);
        add_action( 'wp_ajax_nopriv_wpa_options_calc', array($this,'wpa_options_calc') );
        add_action( 'wp_ajax_wpa_options_calc', array($this,'wpa_options_calc') );
        add_action( 'wp_ajax_wpa_image_thumbnail', array($this,'wpa_image_thumbnail') );
        add_action('wp_enqueue_scripts',  array($this,'wpa_options_frontend_ajax'), 10001);
        //add_action( 'get_footer', array($this,'prefix_add_footer_styles') );

        
    }

    


    public function wpa_product_customize_code(){
        $postID = $this->postID;
        
        
        $product = wc_get_product( $postID );
        $product_min_price = $product -> get_price();
        $option_type = get_field('option_type', $postID);
        echo '';
        //echo '<button class="click-me">click</button>';
        
        
        echo '<div class="col-sm-8 wpa-image-col">
        <div class="wpa-preview-display-width"><span class="wpa_size_display_width">8</span></div>
        <div class="wpa-preview-display-height"><span class="wpa_size_display_height">8</span></div>
        <div id="wpa_preview_01" active="1" class="wpa-canvas wpa-canvas-1">1</div>
        
        <div id="wpa_preview_02" class="wpa-canvas wpa-canvas-2">2</div>
        <div class="canvas-control">
        <div class="wpa-canvas-up-button"><i class="fa fa-angle-double-up fa-4x" aria-hidden="true"></i></div>
        <div class="wpa-canvas-down-button"><i class="fa fa-angle-double-down fa-4x" aria-hidden="true"></i></div>
        </div>
        
        </div>
        ';
        echo '<form class="cart" action="http://woopainting.test/product/b116089-large-round-essential-oil-locket-replacement-pads/" method="post" enctype="multipart/form-data">';
        echo '
        <link href="/wp-content/plugins/woopainting/includes/css/tabs/style.css" rel="stylesheet">

        
        <div class="col-sm-4 cd-tabs js-cd-tabs">
		<nav>
			<ul class="cd-tabs__navigation js-cd-navigation">
				<li class="menu-design-button"><a data-content="inbox" href="#0">Inbox</a></li>
				<li><a data-content="new" class="cd-selected" href="#0">New</a></li>
				<li><a data-content="gallery" href="#0">Gallery</a></li>
				<!--<li><a data-content="store" href="#0">Store</a></li>
				<li><a data-content="settings" href="#0">Settings</a></li>
				<li><a data-content="trash" href="#0">Trash</a></li>-->
			</ul> <!-- cd-tabs__navigation -->
        </nav>
        <ul class="cd-tabs__content js-cd-content">
            <li data-content="inbox">
            </li>

        <li data-content="new"  class="cd-selected">
            ';
        
        echo '<div class="wpa-panel-col">';
        echo '<div id="loadingFilm"></div>';//ajax Loading cover

        $this->wpa_product_size_options($postID, $option_type);
        
        echo get_field('start_code', $postID);
        //select options start
        
        $this->wpa_product_select_options($postID, $option_type);

        echo get_field('ending_code', $postID);// output ending code field for custom codes;
        //select options end
        //hidden input tags
        $this->hidden_input_tags($product, $option_type);
        //
        $this->wpa_product_qty_button();
        
        echo '</div>';
        echo '</li><li data-content="gallery">';
           $this->wpa_upload_frontend();
        echo '
        </li>

        <!--<li data-content="store">
            <p>Store Lorem ipsum dolor sit amet, consectetur adipisicing elit. Earum recusandae rem animi accusamus quisquam reprehenderit sed voluptates, numquam, quibusdam velit dolores repellendus tempora corrupti accusantium obcaecati voluptate totam eveniet laboriosam?</p>

            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eligendi, enim, pariatur. Ab assumenda, accusantium! Consequatur magni placeat quae eos dicta, cum expedita sunt facilis est impedit possimus dolorum sequi nostrum nobis sit praesentium molestias nulla laudantium fugit corporis nam ut saepe harum ipsam? Debitis accusantium, omnis repudiandae modi, distinctio illo voluptatibus aperiam odio veritatis, quam perferendis eaque ullam. Temporibus tempore ad voluptates explicabo ea sit deleniti ipsum quos dolores tempora odio, ab corporis molestiae, eaque optio, perferendis! Cumque libero quia modi! Totam magni rerum id iusto explicabo distinctio, magnam, labore sed nemo expedita velit quam, perspiciatis non temporibus sit minus voluptatum. Iste, cumque sunt suscipit facere iusto asperiores, ullam dolorum excepturi quidem ea quibusdam deserunt illo. Nesciunt voluptates repellat ipsam.</p>
        </li>

        <li data-content="settings">
            <p>Settings Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laboriosam nam magni, ullam nihil a suscipit, ex blanditiis, adipisci tempore deserunt maiores. Nostrum officia, ratione enim eaque nihil quis ea, officiis iusto repellendus. Animi illo in hic, maxime deserunt unde atque a nesciunt? Non odio quidem deserunt animi quod impedit nam, voluptates eum, voluptate consequuntur sit vel, et exercitationem sint atque dolores libero dolorem accusamus ratione iste tenetur possimus excepturi. Accusamus vero, dignissimos beatae tempore mollitia officia voluptate quam animi vitae.</p>

            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Similique ipsam eum reprehenderit minima at sapiente ad ipsum animi doloremque blanditiis unde omnis, velit molestiae voluptas placeat qui provident ab facilis.</p>
        </li>

        <li data-content="trash">
            <p>Trash Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio itaque a iure nostrum animi praesentium, numquam quidem, nemo, voluptatem, aspernatur incidunt. Fugiat aspernatur excepturi fugit aut, dicta reprehenderit temporibus, nobis harum consequuntur quo sed, illum.</p>

            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima doloremque optio tenetur, natus voluptatum error vel dolorem atque perspiciatis aliquam nemo id libero dicta est saepe laudantium provident tempore ipsa, accusamus similique laborum, consequatur quia, aut non maiores. Consectetur minus ipsum aliquam pariatur dolorem rerum laudantium minima perferendis in vero voluptatem suscipit cum labore nemo explicabo, itaque nobis debitis molestias officiis? Impedit corporis voluptates reiciendis deleniti, magnam, fuga eveniet! Velit ipsa quo labore molestias mollitia, quidem, alias nisi architecto dolor aliquid qui commodi tempore deleniti animi repellat delectus hic. Alias obcaecati fuga assumenda nihil aliquid sed vero, modi, voluptatem? Vitae voluptas aperiam nostrum quo harum numquam earum facilis sequi. Labore maxime laboriosam omnis delectus odit harum recusandae sint incidunt, totam iure commodi ducimus similique doloremque! Odio quaerat dolorum, alias nihil quam iure delectus repellendus modi cupiditate dolore atque quasi obcaecati quis magni excepturi vel, non nemo consequatur, mollitia rerum amet in. Nesciunt placeat magni, provident tempora possimus ut doloribus ullam!</p>
        </li>-->
    </ul> <!-- cd-tabs__content -->
</div> <!-- cd-tabs -->
<script src="/wp-content/plugins/woopainting/includes/js/tabs.js"></script>';
        echo "</form>";
            //test area start
            
            //test area end
    }
    
    function wpa_product_qty_button(){
        echo '<div class="quantity">
        <input type="button" value="-" class="minus">
        <label class="screen-reader-text" for="quantity_5bf44fcb75ca0">Quantity</label>
        <input type="number" id="quantity_5bf44fcb75ca0" class="input-text qty text" step="1" min="1" max="968" name="quantity" value="1" title="Qty" size="4" pattern="[0-9]*" inputmode="numeric" aria-labelledby="">
        <input type="button" value="+" class="plus">
            </div>
        <button type="submit" name="add-to-cart" value="132" class="single_add_to_cart_button button alt">Add to cart</button>';
    }
    function wpa_product_select_options($postID, $option_type){
        if(have_rows('group', $postID)){
            while( have_rows('group', $postID) ){// normal drop down options
                the_row();
                $row_index = get_row_index();//Group index number
                if(get_sub_field('parent_id')){
                    echo '<div parent="',get_sub_field('parent_id'),'"  opt_id="',$row_index,'" class="wpa_dropdown_group">';
                }else {
                    echo '<div class="wpa_dropdown_group">';
                }
                
                echo '<div class="col-sm-4 wpa_option_name">',get_sub_field('group_name'),'</div>';
                echo '<select opt_id="',$row_index,'" name="_wpa_options[',get_sub_field('group_name'),']" class="col-sm-8 ',(get_sub_field('is_size_option') != '' ? "wpa_is_size_option" : '' ),' wpa_option_select selection_',$row_index,'">';
                while (have_rows('attributes')) {
                    the_row();
                    echo '<option ',(get_sub_field('select_width') != '' ? "s_width=".get_sub_field('select_width')."" : '' ),(get_sub_field('select_height') != '' ? " s_height=".get_sub_field('select_height')."" : '' ), (get_sub_field('double_sides') == 1 ? ' dbsides="1"' : '' ),' value="',$row_index,get_row_index(),'">',get_sub_field('option_name'),'</option>';
                }
                echo '</select></div>';
            }
            

            if($option_type == 'by_qty'){// dropdown options when size option disabled
                $qty_group = get_field('qty_group', $postID);
                $value_1st = $qty_group[0]['qty_options'];
                $value_1st = explode('|', $value_1st);
                echo '<div class="wpa_dropdown_group dropdown_group_qty">';
                echo '<div class="col-sm-4 wpa_option_name">Qty</div>';
                echo '<select name="_wpa_values[wpa_qty]" num="num" class="col-sm-8 wpa_option_select_cp wpa_option_select_qty">';
                foreach ($value_1st as $value) {
                    echo '<option value="">',$value,'</option>';
                }
                echo '</select></div>';
            }
            $turnaround_time = get_field('turnaround_time', $postID);
            //var_dump($turnaround_time);
            echo '<div class="wpa_dropdown_group dropdown_group_time">';
            echo '<div class="col-sm-4 wpa_option_name">Turnaround Time</div>';
            echo '<select name="_wpa_addons[wpa_ta_time]" class="col-sm-8 wpa_option_select wpa_option_select_time">';
            
            foreach ($turnaround_time as $value) {
                echo '<option value="',$value['label'],'">',$value['label'],'</option>';
            }
            echo '</select></div>';
            echo '<div class="wpa-clear"></div>';
            echo '<div class="trunaround-notice"><strong>All jobs next day turnaround, Cut off time 3pm PST</strong></div>';
            echo '<div class="wpa-clear"></div>';
        };
    }
    function wpa_product_size_options($postID, $option_type){
        
        if($option_type == 'by_size'){
            if(have_rows('product_size', $postID)){//product size options output
                $product_size = get_field('product_size', $postID);
                $max_width_foot = $product_size['max_width']/12;
                $max_height_foot = $product_size['max_height']/12;
                $min_width_foot = $product_size['min_width']/12;
                $min_height_foot = $product_size['min_height']/12;
                $width_step_size = $product_size['width_step_size'];
                $height_step_size = $product_size['height_step_size'];
                echo '<div>';
                echo '<div class="wpa_size_input"><span class="col-sm-3 wpa_size_title">Width Foot</span><input type="number" min="1" max="',floor($max_width_foot),'" class="col-sm-2 wpa_size wpa_width_foot" value="1" />';
                echo '<span class="col-sm-2 wpa_size_title">Inch</span><select class="col-sm-3 wpa_size wpa_width_inch" type="number" value="">';
                //echo $step_size;
                for ($i=0; $i < 12; $i+=$width_step_size) { 
                    echo '<option value="',$i,'">',$i,'</option>';
                }
                echo '</select></div>';
                echo '<div class="wpa_size_input">
                <span class="col-sm-3 wpa_size_title">Height Foot</span>
                <input type="number" min="1" max="',floor($max_height_foot),'" class="col-sm-2 wpa_size wpa_height_foot" value="1" />';
                echo '<span class="col-sm-2 wpa_size_title">Inch</span>
                <select class="col-sm-3 wpa_size wpa_height_inch" type="number" value="" />';
                for ($i=0; $i < 12; $i+=$height_step_size) { 
                    echo '<option value="',$i,'">',$i,'</option>';
                }
                echo '</select></div>';
                echo '<div class="wpa_size_calculator"><p><span class="wpa_size_display_width">12</span>" x <span class="wpa_size_display_height">12</span>" =  <span class="wpa_size_display_area">1.00</span>ft<sup>2</sup></p><p>Note: The maximum value for this product are ',$max_width_foot*12,'" x ',$max_height_foot*12,'".</p></div>';
                echo '</div>';
                ?>
                <script>
                    var wpa_max_width = <?php echo $product_size['max_width']; ?>;
                    var wpa_max_height = <?php echo $product_size['max_height']; ?>;
                </script>
                <?php
            };
        };
    }
    function hidden_input_tags($product, $option_type){
        echo '<div class="hidden_truth">';
        echo '<button type="button" class="wpa_calc_btn">Calc Price</button>';
        echo '<span>price</span><input class="wpa_price" name="_wpa_values[wpa_price]" value="'.$product->get_price().'" />';
        if($option_type == 'by_size'){
            echo '<input class="wpa_height" name="_wpa_values[wpa_height]" value="12" placeholder="height" />';
            echo '<input class="wpa_width" name="_wpa_values[wpa_width]" value="12" placeholder="width" />';
        }
        echo '<input class="wpa_pkg_weight" name="_wpa_values[wpa_pkg_weight]" value="'.$product->get_weight().'" placeholder="weight" />';
        echo '<input class="wpa_pkg_length" name="_wpa_values[wpa_pkg_length]" value="'.$product->get_length().'" placeholder="pkg_length" />';
        echo '<input class="wpa_pkg_width" name="_wpa_values[wpa_pkg_width]" value="'.$product->get_width().'" placeholder="pkg_width" />';
        echo '<input class="wpa_pkg_height" name="_wpa_values[wpa_pkg_height]" value="'.$product->get_height().'" placeholder="pkg_height" />';
        echo '<input class="wpa_preview_01_data" name="_wpa_canvas[wpa_canvas_01_data]" value="" /><input class="wpa_preview_02_data"  name="_wpa_canvas[wpa_canvas_02_data]"value="" />';
        echo '<input class="wpa_preview_01_id" name="_wpa_canvas[wpa_canvas_01_id]" value="" /><input class="wpa_preview_02_id"  name="_wpa_canvas[wpa_canvas_02_id]"value="" />';
        echo '</div>';
    }

    function wpa_upload_frontend(){
        ?>
        
        <link href="/wp-content/plugins/woopainting/includes/css/filepond.min.css" rel="stylesheet">
        <script src="/wp-content/plugins/woopainting/includes/js/filepond.min.js"></script>
        <script src="/wp-content/plugins/woopainting/includes/js/konva.min.js"></script>
        <script src="/wp-content/plugins/woopainting/includes/js/canvas.js"></script>
        
        <div style="clear:both; height:1px; width:100%;"></div>
        
        <div class="filepond "><input type="file" name="filepond[]" multiple data-max-files="2"></div>
        <ul class="wpa-photo-list"><li fileid="nl9c0fu0a" class="wpa-photo"><img class="wpa-photo-img" src="/wp-content/uploads/orders/tmp/85a058a60c4e569be7a3235ea0ef1f4e.jpg"></li><li fileid="sxiwgzgfh" class="wpa-photo"><img class="wpa-photo-img" src="/wp-content/uploads/orders/tmp/deb02b341e1feee0f7440691375ea352.jpg"></li><li fileid="toe5n23u2" class="wpa-photo"><img class="wpa-photo-img" src="/wp-content/uploads/orders/tmp/bbff58fb9c86fea5d6f2bef984139a03.jpg"></li></ul>

        <div class="wpa-clear"></div>
        <script>
        // Set default server location
        

        FilePond.setOptions({
            required: true,
            maxFiles: 2,
            server: '/wp-content/plugins/woopainting/includes/fp/'
        });
        
        // Create ponds on the page
        var pond = FilePond.create( document.querySelector('input[type="file"]') , {
            
        });
        const pond_e = document.querySelector('.filepond--root');
        pond_e.addEventListener('FilePond:processfile', e=> {
            console.log(e);
            var file = new Array;
            file.push(e.detail.file.serverId, e.detail.file.filename, e.detail.file.id, e.detail.file.fileExtension);
            jQuery.ajax({
                url : wpa_image_thumbnail_ajax.ajax_url,
                type : 'post',
                data : {
                    action : 'wpa_image_thumbnail',
                    wpa_img_data : file,
                },
                success : function( response ) {
                    var result = JSON.parse(response);
                    console.log(result);
                    //var fileID = fileFName.split('.').slice(0, -1).join('.');
                    url = `<li fileid="${result.fileID}" class="wpa-photo"><img class="wpa-photo-img" src="/wp-content/uploads/orders/tmp/${result.fileFullName}"  /></li>`;
                    console.log(url);
                    jQuery('.wpa-photo-list').append(url);
                }
            })
        });
        pond_e.addEventListener('FilePond:removefile', e=> {
            console.log(e.detail.file.id);
            files = [];
            jQuery('.wpa-photo[fileid="'+ e.detail.file.id +'"]').remove();
            
        });
        
        </script>
        <?php
    }
    
    function save_custom_data_hidden_fields( $cart_item, $product_id ){
        $cart_item['wpa_values'] = $_POST['_wpa_values'];
        $cart_item['wpa_options'] = $_POST['_wpa_options'];
        $cart_item['wpa_addons'] = $_POST['_wpa_addons'];
        $cart_item['wpa_canvas'] = $_POST['_wpa_canvas'];
        // Get submitted field data items, pass input field name along
        $cart_item['wpa_files'] = FilePond\RequestHandler::loadFilesByField('filepond');
        //var_dump($cart_item['wpa_files']);
        // Items will always be an array as multiple files could be submitted
        return $cart_item;
    }
    
    function moe_get_cart_item_from_session( $cart_item, $values ) {
        $productID = $values['product_id'];
        $option_type = get_field('option_type', $productID);
        if($option_type == 'by_size'){
            $cart_item['wpa_height'] = $values['wpa_values']['wpa_height'];
            $cart_item['wpa_width'] = $values['wpa_values']['wpa_width'];
        }else if($option_type == 'by_qty') {
            $cart_item['wpa_qty'] = $values['wpa_values']['wpa_qty'];
        }
        $cart_item['wpa_ta_time'] = $values['wpa_addons']['wpa_ta_time'];

        // $cart_item['wpa_canvas_01'] = $values['wpa_canvas']['wpa_canvas_01_data'];
        // $cart_item['wpa_canvas_02'] = $values['wpa_canvas']['wpa_canvas_02_data'];
        // $data1 = $cart_item['wpa_canvas_01'];
        // list($type, $data1) = explode(';', $data1);
        // list(, $data1)      = explode(',', $data1);
        // $data1 = base64_decode($data1);
        // file_put_contents('wpa_canvas_01.png', $data1);
        // $data2 = $cart_item['wpa_canvas_02'];
        // list($type, $data2) = explode(';', $data2);
        // list(, $data2)      = explode(',', $data2);
        // $data2 = base64_decode($data2);
        // file_put_contents('wpa_canvas_02.png', $data2);

        $values_arr = $values['wpa_options'];
        
        foreach ($values_arr as $opt_name => $value) {
            $cart_item[$opt_name] = $values['wpa_options'][$opt_name];
        }
        
        //print_r($cart_item);
        return $cart_item;
    }
    
    function moe_get_item_data( $other_data, $cart_item ) {//this function needs reduce. 09/15/2018

        $productID = $cart_item['product_id'];

        $option_type = get_field('option_type', $productID);
        if($option_type == 'by_size'){
            $other_data[] = array(
                'name' => 'Product Height',
                'value' => sanitize_text_field($cart_item['wpa_values']['wpa_height'].=" Inch"),
            );
            $other_data[] = array(
                'name' => 'Product Width',
                'value' => sanitize_text_field($cart_item['wpa_values']['wpa_width'].=" Inch"),
            );
        } else if ($option_type == 'by_qty'){
            $other_data[] = array(
                'name' => 'Qty',
                'value' => sanitize_text_field($cart_item['wpa_values']['wpa_qty']),
            );
        }
        //var_dump($cart_item['wpa_values']);
        $files = $cart_item['wpa_files'];

        foreach ($files as $i => $file) {
            $i++;
            $other_data[] = array(
                'name' => 'File '.$i,
                'value' => $file->name
            );
        }
        

        $values_arr = $cart_item['wpa_options'];
        $group = get_field('group', $productID);
        foreach ($values_arr as $opt_name => $opt_num) {
            $opt_nums = str_split($opt_num,1);
            $other_data[] = array(
            'name' => $opt_name,
            'value' => sanitize_text_field($group[$opt_nums[0]]['attributes'][$opt_nums[1]]['option_name'])
            );
        };
        unset($group);

        $other_data[] = array(
            'name' => 'Turnaround Time',
            'value' => sanitize_text_field($cart_item['wpa_addons']['wpa_ta_time']),
        );

        return $other_data;
    }
    
    function custom_checkout_create_order_line_item( $item, $cart_item_key, $values, $order ) {
        
        $productID = $item->get_product_id();

        //$product_size_on = get_field('product_size_on', $productID);
        $option_type = get_field('option_type', $productID);
        if($option_type == 'by_size'){
            $item->update_meta_data( 'Product Height', $values['wpa_values']['wpa_height'].=" Inch");
            $item->update_meta_data( 'Product Width', $values['wpa_values']['wpa_width'].=" Inch");
        } else if($option_type == 'by_qty'){
            $item->update_meta_data( 'Qty', $values['wpa_values']['wpa_qty']);
        }
        
        $files = $values['wpa_files'];
        foreach ($files as $i => $file) {
            $item->update_meta_data( '_file_'.$i, $file->id);
            FilePond\RequestHandler::save($file, $file->id);
        }

        $values_arr = $values['wpa_options'];
        $group = get_field('group', $productID);
        foreach ($values_arr as $opt_name => $opt_num) {
            $opt_nums = str_split($opt_num,1);
            $item->update_meta_data( $opt_name, $group[$opt_nums[0]]['attributes'][$opt_nums[1]]['option_name'] );
        }
        unset($group);
        $item->update_meta_data( 'Turnaround Time', $values['wpa_addons']['wpa_ta_time']);
    }

    
    function remove_order_item_meta_fields( $fields ) {
        for ($i=0; $i < 10; $i++) { 
            $fields[] = '_file_'.$i;
        }
        return $fields;
    }

    
    function create_invoice_for_wc_order( $order_id ) {
        // $path = wp_upload_dir();
        // $path = $path['basedir'];
        $path = '/xampp/htdocs/woopainting/wp-content/uploads/orders/';
        //$path = '/var/www/woopainting.com/wp-content/uploads/orders/';
        $order = wc_get_order($order_id);
        $items = $order->get_items();
        
        $file_names= array();

        foreach ( $items as $item ) {
            for ($i=0; $i < 10; $i++) { 
                $folder_name = $item->get_meta('_file_'.$i);
                if($folder_name == ""){ break;}
                foreach (glob($path . $folder_name . DIRECTORY_SEPARATOR . '*.*') as $count => $file) {
                    $source = $file;
                    if (!is_dir($path.$order_id)) {
                        mkdir($path.$order_id, 0755, true);
                    }
                    $extention = substr(strrchr($file, '.'), 1);
                    rename($source, $path . $order_id . DIRECTORY_SEPARATOR . $folder_name .'.'.$extention);
                    
                    $new_file_name = $folder_name .'.'.$extention;
                    //print_r($new_file_name);
                    array_push($file_names, $new_file_name);
                }
                
                foreach ($file_names as $key => $file_name) {
                    $item->update_meta_data( '_file_'.$key , $file_name);
                }
                if(is_dir($path . $folder_name)){rmdir($path . $folder_name);}
                
            }
            $file_names= array();
        }
        $order->save();
        
    }
    
    function file_download_link_in_order_page( $item_id, $item, $_product ){
        global $woocommerce, $post;
        $order = new WC_Order($post->ID);
        $order_id = $order->id;

        for ($i=0; $i < 10; $i++) { 
            $field_name = $item->get_meta('_file_'.$i);
            if($field_name == ""){ break;}
            $display_num = $i+1;
            echo '<span style="margin-right:10px;"><a target="_blank" download href="/wp-content/uploads/orders/',$order_id,DIRECTORY_SEPARATOR,$field_name,'" >','File ',$display_num,'</a></span>';
        }
    }
    //save cart item meta and order meta info end
    
    function add_custom_price( $cart_obj ) {

        //  This is necessary for WC 3.0+
        if ( is_admin() && ! defined( 'DOING_AJAX' ) )
            return;

        foreach ( $cart_obj->get_cart() as $item_values ) {

            $item_id = $item_values['data']->id;
            //$original_price = $item_values['data']->price; // Product original price
    
            ## Get your custom fields values
            $new_price = $item_values['wpa_values']['wpa_price'];
            $new_weight = $item_values['wpa_values']['wpa_pkg_weight'];
            //$quantity = $item_values['custom_data']['quantity'];
            
            ## Set the new item price in cart
            $item_values['data']->set_price($new_price);
            $item_values['data']->set_weight($new_weight);
            //$item_values['data']->save();
        }
    }

    function wpa_image_thumbnail(){
        $data = $_REQUEST['wpa_img_data']; // ["54762e2a702ad3f4d8eec287abf679a0","1.jpg","wqvrp0l2z","jpg"]
        $image_path = get_home_path().'wp-content/uploads/orders/tmp'.DIRECTORY_SEPARATOR.$data[0];
        $file_name = pathinfo($data[1], PATHINFO_FILENAME);
        
        switch ($data[3]) {
            case 'pdf':
                $image_full = $image_path. DIRECTORY_SEPARATOR . $file_name .'.'.$data[3].'[0]';
                break;
            
            default:
                $image_full = $image_path. DIRECTORY_SEPARATOR . $file_name .'.'.$data[3];
                break;
        }

        $image_save_as = $image_path.'.jpg';
        $im = new Imagick();
        $im->setResolution(1000,1000);
        $im->readimage($image_full);
        $im->setImageFormat('jpeg'); 
        
        $im->scaleImage(1000,1000,true);
        $im->setCompressionQuality(40);
        $im->writeImage($image_save_as);
        $im->clear(); 
        $im->destroy();
        $image_thumbnail = [
            'fileFullName' => $data[0].'.jpg',
            'fileName' => $data[0],
            'fileID' => $data[2]
        ];
        
        echo json_encode($image_thumbnail);
        die();
    }


    function wpa_options_calc() {
        $product_data = $_REQUEST['wpa_product_data'];
        $postID = $product_data['wpa_post_id'];
        $options = $_REQUEST['wpa_option_val'];
        $group = get_field('group', $postID);
        
        $product_obj = wc_get_product( $postID );//product object for min price
        $product_price_limit = $product_obj->get_price();
        //$product_size_on = get_field('product_size_on', $postID);//check if product size option enabled
        $option_type = get_field('option_type', $postID);
        if($option_type == 'by_size'){
            $product_area_sqft = $product_data['wpa_height_now']*$product_data['wpa_width_now']/144;
            $product_perimeter_ft = ($product_data['wpa_height_now']+$product_data['wpa_width_now'])*2/12;
            $product_unit_price = get_field('unit_price', $postID);
        } else if($option_type == 'by_qty'){
            foreach ($options as $key => $option) {
                if($option == 'x'){
                    unset($options[$key]);
                }
            }
            $product_selected_opt = end($options);
            if(strlen($product_selected_opt) == 2){
                $product_selected_opt = str_split($product_selected_opt,1);
            } else if (strlen($product_selected_opt) > 2){// process option number 1-99
                $product_selected_opt = str_split($product_selected_opt,2);
            }
        }
       
        
        $product_result = [ // set product values array for ajax send back data.
            //'wpa_price_val'=> (int)$product_data['wpa_orig_price_val'],
            'wpa_price_val'=> 0,
            'wpa_pkg_weight' => (int)$product_data['wpa_pkg_weight'],
            'wpa_pkg_length' => (int)$product_data['wpa_pkg_length'],
        ];
            

        if($option_type == 'by_size'){
            $product_result['wpa_price_val'] = $product_result['wpa_price_val'] + ($product_area_sqft-1) * $product_unit_price + $product_price_limit;
        } else if ($option_type == 'by_qty'){
            $product_result['wpa_price'] = $group[$product_selected_opt[0]]['attributes'][$product_selected_opt[1]]['qty_price'];
            $product_result['wpa_qty_pkg_weight'] = $group[$product_selected_opt[0]]['attributes'][$product_selected_opt[1]]['qty_package_weight'];
            $product_result['wpa_qty_pkg_length'] = $group[$product_selected_opt[0]]['attributes'][$product_selected_opt[1]]['qty_package_length'];
            $qty_num = $group[$product_selected_opt[0]]['attributes'][$product_selected_opt[1]]['qty_group_num'];
            $qty_group = get_field('qty_group', $postID);
            $product_result['wpa_qty'] =  $qty_group[$qty_num]['qty_options'];	
        }
        //echo json_encode($group);
        //sorting product options by priority order
        
        
        $sorted_group = array();
        foreach ($group as $key => $value1) {
            $attributes = &$value1['attributes'];
            $attributes = array_map('array_filter', $attributes);
            $attributes = array_filter($attributes);
            $sorted_group[] = $value1;
            $selected_now = current($options);
            if ($selected_now == 'x'){
                $sorted_group[$key]['selected'] = current($options);
            }else {
                $sorted_group[$key]['selected'] = substr(current($options),1);
            }
            next($options);
        }
        
        function arraySort($array, $keys, $sort = 'SORT_DESC') {
            $keysValue = [];
            foreach ($array as $k => $v) {
                $keysValue[$k] = $v[$keys];
            }
            array_multisort($keysValue, $sort, $array);
            return $array;
        }
        $ready_group = arraySort($sorted_group, 'priority', SORT_ASC);
        
        
        
        //$test = array();
        foreach ($ready_group as $key => $value) {
            
            $attributes = &$value['attributes'];//all options, don't know why but it works only if add'&', otherwise it will be mess up whole array order
            $selected = $value['selected'];//get selected  option number
            $attribute_now = $attributes[$selected];
            //$test[] = $value;
            if(array_key_exists('package_weight', $attribute_now)){
                $product_result['wpa_pkg_weight'] = $product_result['wpa_pkg_weight'] + $attribute_now['package_weight'];
            }
            if(array_key_exists('package_length', $attribute_now)){
                $product_result['wpa_pkg_length'] = $product_result['wpa_pkg_length']+ $attribute_now['package_length'];
            }
            
            if($option_type == 'by_size'){
                if(array_key_exists('price_length', $attribute_now)){
                    $product_result['wpa_price_val'] = $product_result['wpa_price_val'] + $product_perimeter_ft*$attribute_now['price_length'];
                }
                if(array_key_exists('price_area', $attribute_now)){
                    $product_result['wpa_price_val'] = $product_result['wpa_price_val'] + $product_area_sqft*$attribute_now['price_area'];
                }
                if(array_key_exists('price_percent', $attribute_now)){
                    $product_result['wpa_price_val'] = $product_result['wpa_price_val'] + $product_result['wpa_price_val']*$attribute_now['price_percent']*0.01;
                }
            } else if ($option_type == 'by_qty'){
                if(array_key_exists('price_percent', $attribute_now)){
                    $product_result['wpa_price_val'] = $product_result['wpa_price_val'] + $product_result['wpa_price_val'] * $attribute_now['price_percent']*0.01;
                }
            }
            if(array_key_exists('price_fixed', $attribute_now)){
                $product_result['wpa_price_val'] = $product_result['wpa_price_val'] + $attribute_now['price_fixed'];
            }
            if(array_key_exists('formula', $attribute_now)){
                $formula_text = $attribute_now['formula'];
                $formula_price = $product_result['wpa_price_val'];
                // $formula_text = 'PRICE*2';
                $formula = str_replace('PRICE', '$formula_price', $formula_text);
                $result = eval("return $formula;");
                $product_result['wpa_price_val'] = $result;
            }
        }

        if($product_result['wpa_price_val'] <= $product_price_limit){//min price limit by product price
            $product_result['wpa_price_val'] = $product_price_limit;
        }
        foreach ($product_result as $key => &$value) {// format values ready to send back
            if ($key == 'wpa_price_val'){
                $value = (float)sprintf("%.2f", $value);
            } elseif ($key == 'wpa_price' || $key == 'wpa_qty' ||$key == 'wpa_qty_pkg_weight'|| $key == 'wpa_qty_pkg_length'){
                $value = $value;
            }else {
                $value = ceil($value);
            }
        }
        //echo json_encode($option_type);
        echo json_encode($product_result);
        die();
    }



    //build ajax request
    
    function wpa_options_frontend_ajax(){
        wp_register_style( 'moe-styles',  plugin_dir_url( __FILE__ ) . 'css/style.css' );
        wp_enqueue_style( 'moe-styles' );
        
        wp_register_script('wpa_ajax',  plugin_dir_url( __FILE__ ) . 'js/front_ajax.js'  );
        wp_enqueue_script( 'wpa_ajax' );

        // wp_register_script('wpa_canvas',  plugin_dir_url( __FILE__ ) . 'js/canvas.js'  );
        // wp_enqueue_script( 'wpa_canvas' );

        wp_localize_script( 'wpa_ajax', 'wpa_frontend_ajax', array(
            'ajax_url' => admin_url( 'admin-ajax.php' )
        ));

        wp_localize_script( 'wpa_ajax', 'wpa_image_thumbnail_ajax', array(
            'ajax_url' => admin_url( 'admin-ajax.php' )
        ));
    }

    // function prefix_add_footer_styles() {
    //     wp_register_script('wpa_canvas',  plugin_dir_url( __FILE__ ) . 'js/canvas.js'  );
    //     wp_enqueue_script( 'wpa_canvas' );

    //     //wp_register_style( 'moe-styles',  plugin_dir_url( __FILE__ ) . 'css/style.css' );
    // 	//wp_enqueue_style( 'moe-styles' );
    // }
    

}


?>