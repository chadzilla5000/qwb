<?php 
error_reporting(E_ALL | E_STRICT);

//require_once SITE_CONFIG_PATH.'/wp-config.php';

// include_once('../_init.php');
 include_once('../functions/general.php');
 include_once '../functions.php';


$brand = 'ghi';//(isset($_POST['brndsrch']) AND $_POST['brndsrch']) ? $_POST['brndsrch']:NULL;
$skusrch = '';
$dto = date('Y-m-d', time());
$dfr = date('Y-m-d', strtotime($dto. ' - 1 days'));

$taxqu = ($brand) ? array(
	array(
		'taxonomy' => 'product_tag',
		'field' => 'slug',
		'terms' => $brand,
		'operator' => 'IN',
		)
	) : NULL;

	$args = array(
//		'posts_per_page' => $lmt,
//		'offset'         => $ofs,
		'post_status'    => 'publish',
		'post_type'      => 'product',
//		'orderby'        => 'date',
//		'order'          => $asds,

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




	$items = get_posts( $args ); 

echo 'band'; exit;

echo sizeof($items);
exit;
?>