<?php
/* HELPERS */
function issetor(&$var, $default = false) {
    return isset($var) ? $var : $default;
}

// Check to see if in Array
function isInArray($arr, $key){
    $match = false;
    foreach($arr as $a){
        if($a == $key){
            $match = true;
        }
    }
    return $match;
}

//Clean
function clean($string) {
   	$string = str_replace(' ', '+', $string);
   	return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
}

// Get the slug
function the_slug($echo=true){
  $slug = basename(get_permalink());
  do_action('before_slug', $slug);
  $slug = apply_filters('slug_filter', $slug);
  return $slug;
}

// Register the API
//wp_enqueue_script( 'wp-api' );

//Get choices value
function get_acf_key($field_name) {
  global $wpdb;
  $length = strlen($field_name);
  $sql = "
    SELECT `meta_key`
    FROM {$wpdb->postmeta}
    WHERE `meta_key` LIKE 'field_%' AND `meta_value` LIKE '%\"name\";s:$length:\"$field_name\";%';
    ";
  return $wpdb->get_var($sql);
}

/**
 * Redirect back to homepage and not allow access to
 * WP admin for Subscribers.
 */
function redirect_admin(){
	if ( ! defined('DOING_AJAX') && ! current_user_can('manage_options') ) {
		wp_redirect( site_url() );
		exit;
	}
}
add_action( 'admin_init', 'redirect_admin' );

/**
 * Disable admin bar on the frontend of your website
 * for subscribers.
 */
function disable_admin_bar() {
	if ( ! current_user_can('edit_posts') ) {
		add_filter('show_admin_bar', '__return_false');
	}
}
add_action( 'after_setup_theme', 'disable_admin_bar' );

function stripVN($str) {
    $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
    $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
    $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
    $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
    $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
    $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
    $str = preg_replace("/(đ)/", 'd', $str);

    $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
    $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
    $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
    $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
    $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
    $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
    $str = preg_replace("/(Đ)/", 'D', $str);
    return $str;
}



?>
