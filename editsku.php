<?phprequire_once('inc/_init.php');require_once('inc/functions/general.php');error_reporting(E_ALL | E_STRICT);if($lghsh['Id'] == 0){	require_once('inc/functions/authorization.php'); 	$pgcontent = logform(); 	$page_title       = 'WC Terminal LogIn';	$page_description = '';	$keywords         = '';	$head_ext         = '';	require_once('inc/_shell.php'); ///	exit;	}include_once 'inc/functions.php';setlocale(LC_MONETARY, 'en_US');$msg = NULL;require_once 'wconfig.php';$reqntnt = NULL;$list = array();$incnt = NULL;$mr = array();$pi=0;foreach($_POST['ws_skubx'] as $tid){	$r = explode("::", $tid);	$probj = wc_get_product($r[0]);	$sku = $probj->get_sku();	$rpr = $probj->get_regular_price();	$ttl = $probj->get_name();	$catarr = array();	$tagarr = array();	$product_cats_ids = wc_get_product_term_ids( $r[0], 'product_cat' );	foreach( $product_cats_ids as $cat_id ) {		$term = get_term_by( 'id', $cat_id, 'product_cat' );		array_push($catarr, $term->name);		}	$cat = join(',', $catarr);	$product_tags_ids = wc_get_product_term_ids( $r[0], 'product_tag' );	foreach( $product_tags_ids as $tag_id ) {		$term = get_term_by( 'id', $tag_id, 'product_tag' );		array_push($tagarr, $term->name);		}	$tag = join(',', $tagarr);//$img_id  = $probj->get_image_id();//$img_url = wp_get_attachment_image_url( $img_id );//echo $img_url.'<br>';//$product_meta = get_post_meta($r[0]);    $img_url = wp_get_attachment_image_url( $probj->get_image_id(), 'full' );//echo wp_get_attachment_image( $probj->get_image_id(), 'full' ).'<br>';	$incnt .=  ' <input type="hidden" name="sku_body[]" value="'.$r[0].'" /><tr><td>'.++$pi.'.</td>	<td><input type="checkbox" name="fs_chk[]" value="'.$r[0].'" style="margin: 0px;" hecked /></td>	<td><input type="text" name="fs_sku[]" value="'.$sku.'" style="width: 170px;" /></td>	<td><input type="text" name="fs_rpr[]" value="'.$rpr.'" style="width: 50px;" /></td>	<td><input type="checkbox" name="tbtn_'.$pi.'" value="1" OnChange="shh_tfld(this);" />		<div style="position: absolute; width: 900px; height: 380px; padding: 7px; background: #ccc; margin-left: '.($pi*20).'px; border: solid 1px #999; border-radius: 7px; display: none; z-index: 45'.$pi.';" id="tfld_'.$pi.'"><div style="float: left; width: 80%;">		<textarea style="width: 100%; height: 80px; padding: 3px;" name="fs_dsc[]" placeholder="Description">'.$probj->get_description().'</textarea><br>		<textarea style="width: 100%; height: 40px; padding: 3px;" name="fs_sdsc[]" placeholder="Short description">'.$probj->get_short_description().'</textarea></div><div style="float: right;"><table style="font-size: 9px; border: solid 1px #999;" cellspacing="1"><tr><td style="width: 80px; text-align: right; color: #999;">Weight</td>	<td><input type="text" style="width: 50px; padding: 3px;" name="fs_weight[]" value="'.$probj->get_weight().'" /></td></tr><tr><td style="width: 80px; text-align: right; color: #999;">Length</td>	<td><input type="text" style="width: 50px; padding: 3px;" name="fs_length[]" value="'.$probj->get_length().'" /></td></tr><tr><td style="width: 80px; text-align: right; color: #999;">Width</td>	<td><input type="text" style="width: 50px; padding: 3px;" name="fs_width[]" value="'.$probj->get_width().'" /></td></tr><tr><td style="width: 80px; text-align: right; color: #999;">Height</td>	<td><input type="text" style="width: 50px; padding: 3px;" name="fs_height[]" value="'.$probj->get_height().'" /></td></tr></table></div><div style="clear: both; height: 10px;"></div><div style="float: left; width: 80%;"><input type="text" style="width: 100%; margin-bottom: 10px; padding: 3px;" name="fs_imgnm[]" value="'.$img_url.'" placeholder="Image Name" /></div>		<div style="float: right; width: 15%;"><img src="'.$img_url.'" style="width: 100%;" /></div>		</div></td>	<td><input type="text" name="fs_ttl[]" value="'.$ttl.'" style="width: 300px;" /></td>			<td><input type="text" name="fs_cat[]" value="'.$cat.'" style="width: 250px;" /></td>	<td><input type="text" name="fs_tag[]" value="'.$tag.'" style="width: 350px;" /></td>	<td style="padding: 0px;">	<!-- a target="_blank" href=""><img src="" style="width: 20px; margin: 0px; padding: 0px;" /></a -->		<input type="file" name="fs_img_" style="width: 250px;" />	</td></tr>';	}$pi = 0;$itfr = '<br><br><style>.vtbl{	width: 100%;	margin: 0px;	background: #eee;	font-size: 11px;}.vtbl td{	background: #fff;	padding: 5px;}.vtbl input, .vtbl input:focus{	outline: none;	border: none;	font-size: 11px;}</style><script>function fllfld(on,n){ var ev = document.getElementsByName(on)[n];//var ev = on.value;alert(ipublishercr.fs_tag[0]);return;// ipublishercr.fs_tag.forEach(function(element) { // // console.log(element)// alert(element);// });// fs_tag.foreach(function(tf){		// alert("gut");	// //	fs_tag[2][0].value = ev.value+"h";// //	tf.value = ev.value+"h";	// });		}</script><form action="#" method="post" name="wsiedit" enctype="multipart/form-data" style="margin: 0px;"><table cellpadding="0" cellspacing="1" class="vtbl"><tr><th colspan="11" style="padding: 3px; text-align: right;"><input type="submit" name="submit2edit" value="Update Products" style="padding: 3px; border: solid 1px #005; cursor: pointer;" /></th></tr><tr><th></th>	<th></th>	<th></th>	<th></th>	<th colspan="2">			</th>	<th></th>	<th></th>	<th></th></tr><tr><th></th>	<th></th>	<th>Sku</th>	<th>RPr</th>	<th colspan="2">Title / Description / Short description 	<a style="cursor: pointer; color: #f90;" title="Copy above data" OnClick="dscopy(1);">Copy</a>	<a style="cursor: pointer; color: #f90;" title="Clear all" OnClick="dscopy(0);">Clr</a></th>	<th>Category</th>	<th>Tags</th>	<th>Img</th>	<th style="width: 200px;"></th></tr>'.$incnt.'</table>';$reqntnt .= $itfr.'</form><script>function shh_tfld(o){var fid = o.name.replace("tbtn_", "tfld_");var el = document.getElementById(fid);	if(o.checked){ el.style.display = "block"; }	else { el.style.display = "none"; }	return;}function dscopy(dv){var el1 = document.getElementsByName("fs_dsc[]");var el2 = document.getElementsByName("fs_sdsc[]");	for (i = 0; i <= el1.length - 1; i++) {		if(dv>0){			if(i>0 && el1[i].value==""){ el1[i].value = el1[i-1].value; }			if(i>0 && el2[i].value==""){ el2[i].value = el2[i-1].value; }			}		else{ el1[i].value = ""; el2[i].value = ""; }		}	return;}//document.getElementById("put_tag").onclick = function() {//var chboxes = document.querySelectorAll(\'input[type="checkbox"]\');//}</script>';////////////////////////// */function prsvpricelst($id){ global $dbh;	$res = mysqli_query($dbh, "SELECT * FROM cr_vndpl WHERE ID='$id'") or die (mysqli_error($dbh));	return mysqli_fetch_row($res);}function csv_r($n){	$cr = array();	if(($handle = fopen($n, 'r')) !== FALSE) {		set_time_limit(0); 		// necessary if a large csv file		$row = 0;		while(($data = fgetcsv($handle, 1000, ',')) !== FALSE) {			$col_count = count($data);			for($col=0; $col<$col_count; $col++){				$cr[$row][$col]=$data[$col];				}			$row++;			}		fclose($handle);		}	return $cr;}function attchImg($image_url, $post_id, $imn){    $upload_dir = wp_upload_dir();    $image_data = file_get_contents($image_url);    $filename = $imn . '-' . basename($image_url);	    if (wp_mkdir_p($upload_dir['path'])) { $file = $upload_dir['path'] . '/' . $filename; } 	else                                 { $file = $upload_dir['basedir'] . '/' . $filename; }    file_put_contents($file, $image_data);    $wp_filetype = wp_check_filetype($filename, null);    $attachment = array(        'post_mime_type' => $wp_filetype['type'],        'post_title' => sanitize_file_name($filename),        'post_content' => '',        'post_status' => 'inherit',    );    $attach_id = wp_insert_attachment($attachment, $file, $post_id);    require_once ABSPATH . 'wp-admin/includes/image.php';    $attach_data = wp_generate_attachment_metadata($attach_id, $file);    $res1 = wp_update_attachment_metadata($attach_id, $attach_data);    return $attach_id;}function get_cats( $args = '' ) {	$defaults = array( 'taxonomy' => 'category' );	$args     = wp_parse_args( $args, $defaults );	/**	 * Filters the taxonomy used to retrieve terms when calling get_categories().	 *	 * @since 2.7.0	 *	 * @param string $taxonomy Taxonomy to retrieve terms from.	 * @param array  $args     An array of arguments. See get_terms().	 */	$args['taxonomy'] = apply_filters( 'get_categories_taxonomy', $args['taxonomy'], $args );	// Back compat.	if ( isset( $args['type'] ) && 'link' === $args['type'] ) {		_deprecated_argument(			__FUNCTION__,			'3.0.0',			sprintf(				/* translators: 1: "type => link", 2: "taxonomy => link_category" */				__( '%1$s is deprecated. Use %2$s instead.' ),				'<code>type => link</code>',				'<code>taxonomy => link_category</code>'			)		);		$args['taxonomy'] = 'link_category';	}	$categories = get_terms( $args );	if ( is_wp_error( $categories ) ) {		$categories = array();	} else {		$categories = (array) $categories;		foreach ( array_keys( $categories ) as $k ) {			_make_cat_compat( $categories[ $k ] );		}	}	return $categories;}?>