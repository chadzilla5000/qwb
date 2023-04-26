<?php

function crt_var($post_id, $attr_label=NULL){
	$attr_options = NULL;

	switch($attr_label){
		case 'AssemblyLR':		$attr_options = array('RTA','LEFT','RIGHT');	break;
		case 'AssemblyRTA':		$attr_options = array('RTA','ASSEMBLED');		break;
		case 'SRAssembly':		$attr_options = array('SR','ASSEMBLED');		break;
		case 'SRAssemblyLR':	$attr_options = array('SR','LEFT','RIGHT');		break;
		default: return NULL; break;
		}

	$product = wc_get_product($post_id);
	if($product->is_type('variable')){ echo '<div style="color: #f00;">Simple product not found</div>'; return NULL; }

	$article_name = $product->get_title();
	$psku = $product->get_sku();
	$RPrice = (isset($_POST['prc_'.$post_id]) && $_POST['prc_'.$post_id]) ? $_POST['prc_'.$post_id] : $product->get_regular_price();
	if(!$RPrice){ return NULL; }
	
	$attr_s = join(' | ', $attr_options);
	$attr_slug = sanitize_title($attr_label);

	$attributes_array[$attr_slug] = array(
		'name'			=> $attr_label,
		'value'			=> $attr_s,
		'is_visible'	=> '1',
		'is_variation'	=> '1',
		'is_taxonomy'	=> '0' // for some reason, this is really important       
		);
	update_post_meta( $post_id, '_product_attributes', $attributes_array );

	$product_classname = WC_Product_Factory::get_product_classname( $post_id, 'variable' );
	$new_product       = new $product_classname( $post_id );
	$new_product->save();

	$variation = array(
		'post_title'	=> $article_name . ' (variation)',
		'post_content'	=> '',
		'post_status'	=> 'publish',
		'post_parent'	=> $post_id,
		'post_type'		=> 'product_variation'
		);

	foreach( $attr_options as $option ){
		$option_ext=($option=='ASSEMBLED')?'-ASM':'-'.$option;

		$variation_id = wp_insert_post( $variation );
		update_post_meta( $variation_id, '_regular_price', $RPrice );
		update_post_meta( $variation_id, '_price', $RPrice );
		update_post_meta( $post_id, '_manage_stock', "yes" );
		update_post_meta( $post_id, '_backorders', "no" );
		update_post_meta( $variation_id, '_stock_qty', 1000 );
		update_post_meta( $variation_id, 'attribute_' . $attr_slug, $option );
		update_post_meta( $variation_id, '_sku', $psku . $option_ext );
		WC_Product_Variable::sync( $post_id );
		}
	update_post_meta( $post_id, '_default_attributes', array($attr_label => $attr_options[0]));
	return $psku;
}


function crt_smp($post_id){
	$product = wc_get_product($post_id);
	if($product->is_type('simple')){  echo '<div style="color: #f00;">Variable product not found</div>'; return NULL; }

	$article_name = $product->get_title();
	$psku = $product->get_sku();
	$RPrice = (isset($_POST['prc_'.$post_id]) && $_POST['prc_'.$post_id]) ? $_POST['prc_'.$post_id] : cget_price($product);
	if(!$RPrice){ return NULL; }

	if($product->is_type( 'variable' )){
		$variations = $product->get_available_variations();
		$var_ids = wp_list_pluck($variations, 'variation_id' );
		foreach($var_ids as $vid){
			$product_var_obj = new WC_Product_Variation($vid);
			$product_var_obj->delete();
			}
		update_post_meta( $post_id, '_product_attributes', NULL);
		put2stock($product);
//		update_post_meta( $post_id, '_manage_stock', "yes" );
//		update_post_meta( $post_id, '_backorders', "no" );
//		update_post_meta( $post_id, '_stock', 1000 );
		}

	$product_classname = WC_Product_Factory::get_product_classname( $post_id, 'simple' );
	$new_product       = new $product_classname( $post_id );
	update_post_meta( $post_id, '_regular_price', $RPrice );
	$new_product->save();

	return $psku;
}

function cget_price($pro){
	$price = 0;
	if( $pro->is_type( 'simple' ) ){
		$price = $pro->get_regular_price();
		}	
	if($pro->is_type( 'variable' )){
		$variations = $pro->get_available_variations();
		$var_ids = wp_list_pluck($variations, 'variation_id' );
		foreach($var_ids as $vid){
			$product_var_obj = new WC_Product_Variation($vid);
			$price = $product_var_obj->regular_price;
			}
		}
	return $price;
}

?>