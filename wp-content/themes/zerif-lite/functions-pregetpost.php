<?php
function my_pre_get_posts($query) {
	// do not modify queries in the admin
	if( is_admin() ) {
		return $query;
	}
	// only modify queries for 'event' post type
	if( isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == 'online' ) {

		// allow the url to alter the query
		if( isset($_GET['active_event']) ) {
  	   $query->set('meta_key', 'active_event');
	     $query->set('meta_value', $_GET['active_event']);
    	}
	}
	// return
	return $query;

}

add_action('pre_get_posts', 'my_pre_get_posts');



//SEARCH on Instituion by Title
function search_institution_pre_get_posts($query) {
	// do not modify queries in the admin
	if( is_admin() ) {
		return $query;
	}
	// only modify queries for 'event' post type
	if( isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == 'institution' ) {

		// allow the url to alter the query
		if( isset($_GET['q']) ) {
	     $query->set('s', $_GET['q']);
    	}
	}
	// return
	return $query;

}

add_action('pre_get_posts', 'search_institution_pre_get_posts');

//SEARCH on Offline Event by Title
function search_offline_pre_get_posts($query) {
	// do not modify queries in the admin
	if( is_admin() ) {
		return $query;
	}
	// only modify queries for 'event' post type
	if( isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == 'offline' ) {

		// allow the url to alter the query
		if( isset($_GET['event']) ) {
	     $query->set('s', $_GET['event']);
    	}
	}
	// return
	return $query;

}

add_action('pre_get_posts', 'search_offline_pre_get_posts');

//SEARCH on Offline Event by Title
function search_admin_event_pre_get_posts($query) {
	// do not modify queries in the admin
	if( is_admin() ) {
		return $query;
	}
	// only modify queries for 'event' post type
	if( isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == 'registrees' ) {

		// allow the url to alter the query
		if( isset($_GET['event']) ) {
	     $query->set('s', $_GET['event']);
    	}
	}
	// return
	return $query;

}

add_action('pre_get_posts', 'search_admin_event_pre_get_posts');

//SEARCH on Offline Event by Title
function search_admin_city_pre_get_posts($query) {
	// do not modify queries in the admin
	if( is_admin() ) {
		return $query;
	}
	// only modify queries for 'event' post type
	if( isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == 'registrees' ) {

		// allow the url to alter the query
		if( isset($_GET['city']) ) {
	     $query->set('city', $_GET['city']);
    	}
	}
	// return
	return $query;

}

add_action('pre_get_posts', 'search_admin_city_pre_get_posts');


// Search User title
function search_user_pre_get_posts($query) {
	// do not modify queries in the admin
	if( is_admin() ) {
		return $query;
	}

	// only modify queries for 'event' post type
	if( isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == 'registrees' ) {

		// allow the url to alter the query
		if( isset($_GET['userSearch']) ) {
          $query->set(
                'meta_query', array(
                    array(
                        'key'     => 'user',
                        'value'   => $_GET['userSearch'],
                        'compare' => 'LIKE'
                    )
                )
            );
    	}
	}

	// return
	return $query;

}

add_action('pre_get_posts', 'search_user_pre_get_posts');
 ?>
