<?php 
include_once('inc/functions/general.php');
include_once 'inc/functions.php';

$brand   = (isset($_GET['brs']))?$_GET['brs']:NULL;//(isset($_POST['brndsrch']) AND $_POST['brndsrch']) ? $_POST['brndsrch']:NULL;
$skusrch = (isset($_GET['sks']))?$_GET['sks']:NULL;
$dto     = (isset($_GET['dto']) AND $_GET['dto']) ? $_GET['dto']:date('Y-m-d', time());
$dfr     = (isset($_GET['dfr']) AND $_GET['dfr']) ? $_GET['dfr']:date('Y-m-d', strtotime($dto. ' - 1 days'));

$taxqu = ($brand) ? array(
	array(
		'taxonomy' => 'product_tag',
		'field' => 'slug',
		'terms' => $brand,
		'operator' => 'IN',
		)
	):NULL;

// $argsv = array(
			// 'post_parent' => $ii->ID,
			// 'post_type'   => 'product_variation',
			// 'numberposts' => -1,
			// ); 

	$args = array(
		'fields'         => 'ids',
		'posts_per_page' => -1,
		'offset'         => 0,
		'post_status'    => 'publish',
		'post_type'      => 'product',
		'meta_query'     => array(
			'sku_clause'  => array(
				'key'     => '_sku',
				'value'	  => $skusrch,
				'compare' => 'LIKE',
				)
			),
		'tax_query'      => $taxqu,
		'date_query'     => array(
			'column'      => 'post_modified',
			'before'      => $dto.' + 1 days',
			'after'       => $dfr.' + 0 days',
			'type'        => 'date'
			)
		);

	$argsv = array(
		'fields'         => 'ids',
		'posts_per_page' => -1,
		'offset'         => 0,
		'post_status'    => 'publish',
		'post_type'      => 'product_variation',
		'meta_query'     => array(
			'sku_clause'  => array(
				'key'     => '_sku',
				'value'	  => $skusrch,
				'compare' => 'LIKE',
				)
			),
		'tax_query'      => $taxqu,
		'date_query'     => array(
			'column'      => 'post_modified',
			'before'      => $dto.' + 1 days',
			'after'       => $dfr.' + 0 days',
			'type'        => 'date'
			)
		);


	$itp = get_posts( $args ); 
	$itv = get_posts( $argsv ); 

//	echo (sizeof($items)<10001)?sizeof($items):'10000+';
//echo '<pre>'; print_r($items); echo '</pre>';

//echo 'band'; exit;
//echo sizeof($itp);
$sum = sizeof($itp)+sizeof($itv);
echo sizeof($itp).' + '.sizeof($itv).' = '.$sum;
exit;
?>