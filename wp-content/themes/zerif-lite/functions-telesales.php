<?php

//Update the telesale note field
function update_telesales() {

    update_user_meta(4000, 'telesale_note', $_POST['telesale_note']);

    die();

}

add_action( 'wp_ajax_nopriv_update_telesales', 'update_telesales' );
add_action( 'wp_ajax_update_telesales', 'update_telesales' );

function my_script_enqueuer3() {
  // Register create_user function
   wp_register_script( "update_telesales", WP_PLUGIN_URL.'/register/update_notes.js', array('jquery') );
   wp_localize_script( 'update_telesales', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));


   //Enqueue
   wp_enqueue_script( 'jquery' );
   wp_enqueue_script( 'update_telesales' );

}

add_action( 'init', 'my_script_enqueuer3' );

function get_registered($this_event){
	
	$registered;
	$walkin;

	$users =  get_posts(array(
	    'posts_per_page'  => -1,
	    'post_type'     => 'registrees',
	    'orderby'          => 'user',
		'order'            => 'ASC'
	));

	foreach ($users as $u) {

	    // GET AND CHECK THE EVENT ID AGAINST THIS REGISTREE
	    $e = get_field('event_id', $u->ID);
	    if($this_event->ID == $e[0]){

	        // GET THE USER & META ASSOCIATED WITH THIS REGISTREE
	        $a = get_field('user', $u->ID);
	        $um = get_user_meta($a['ID']);


	        if(isset($um['created_at_event'][0])){

	            if($um['created_at_event'][0] == 'Yes'){
	              $walkin++;
	            } else {
	              $registered++;
	            }
	        } else {
	            $registered++;
	        }
	    }
	}

	return array($registered, $walkin);
}

function get_cities(){
	$cities =  get_posts(array(
	    'posts_per_page'  => -1,
	    'post_type'     => 'cities'
	));

	wp_reset_postdata();

	return $cities;
}

function get_select_options($type){

	$wppb_fields = get_option( 'wppb_manage_fields', 'not_found' );

	foreach($wppb_fields as $val){
	    if($val['field-title'] == $type){
	        return explode(',', $val['options']);
	    }
	}

}

function custom_pagination($this_event, $posts_per_page, $offset){  
    
    $args = array(
    	'posts_per_page'  => -1,
	    'post_type'		=> 'registrees',
      	'orderby'       => 'user',
      	//'post_status'	=> 'published',
      	'order'         => 'ASC',
      	'meta_query'    => array(
                            'relation' => 'AND',
                            array(
                                'key' => 'event_id',
                                'value' => $this_event->ID,
                                'compare' => 'LIKE'
                            )
                          )
	);

	$posts = new WP_Query( $args );

	$max_posts = $posts->found_posts; 

	wp_reset_postdata(); 

	$event = str_replace(' ', '+', $_GET['event']);
	
	if(empty($_GET['user']) || !isset($_GET['user'])){
	
		$max_pages = floor($max_posts / $posts_per_page);

		if(fmod($max_posts, $posts_per_page) > 0) $max_pages++;

		$url = strtok($_SERVER["REQUEST_URI"],'?');

		$this_page = $_GET['pg'] / $posts_per_page;

		$next_page = ($this_page + 1) * $posts_per_page;

		$prev_page = ($this_page - 1) * $posts_per_page;

		$last_page = ($max_pages - 1) * $posts_per_page;

		echo "<ul class='pagination'>";

		if($this_page > 1){
			echo '<li><a href="' . $url . '?event=' . $event . '&pg=0"><<</a></li>';
		}

		if($this_page > 0){
			echo '<li><a href="' . $url . '?event=' . $event . '&pg=' . $prev_page . '"><</a></li>';
		}

		for ($i = 0; $i < $max_pages; $i++) { 
			$count = $i + 1;
			$new_page = $i * $posts_per_page;

			if($i == $this_page - 3 || $i == $this_page - 2 || $i == $this_page - 1 || $i == $this_page + 1 || $i == $this_page + 2 || $i == $this_page + 3)
				echo '<li><a href="' . $url . '?event=' . $event . '&pg=' . $new_page . '">' . $count . '</a></li>';
			elseif($i == $this_page)
				echo '<li><a>' . $count . '</a></li>';

		}

		if($this_page < $max_pages - 1){
			echo '<li><a href="' . $url . '?event=' . $event . '&pg=' . $next_page . '">></a></li>';
		}

		if($this_page < $max_pages - 2){
			echo '<li><a href="' . $url . '?event=' . $event . '&pg=' . $last_page . '">>></a></li>';
		}

		echo "</ul>\n";
	
	}
	
}

?>
