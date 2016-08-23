<?php

add_action("wp_ajax_create_user", "createUser");
add_action("wp_ajax_nopriv_create_user", "createUser");


// Function to set the user from the custom creation page

function createUser() {

	if ( !wp_verify_nonce( $_REQUEST['nonce'], "create_user_nonce")) {
      exit("No naughty business please");
	}

	$eventName = ($_REQUEST["eventName"])?$_REQUEST["eventName"]:'';
	$eventID = ($_REQUEST["eventID"])?$_REQUEST["eventID"]:'';
	$fullname = ($_REQUEST["fullname"])?$_REQUEST["fullname"]:'';
	$email = ($_REQUEST["email"])?$_REQUEST["email"]:'';
	$phone = ($_REQUEST["phone"])?$_REQUEST["phone"]:'';
	$dob = ($_REQUEST["dob"])?$_REQUEST["dob"]:'';

	//This will create the user
	$userdata = array(
		'user_login'  =>  $fullname,
		'display_name' => $fullname,
		'user_email' => $email,

		'user_url'    =>  get_site_url(),
		'user_pass'   =>  NULL  // When creating an user, `user_pass` is expected.
	);




	$user_id = wp_insert_user( $userdata ) ;

	//On success
	if ( ! is_wp_error( $user_id ) ) {

		//Create the registration on success
		echo "User created : ". $user_id;


		  $register_post = array(
			'post_status' => 'publish',
			'post_title' => $eventName,
			'post_type' => 'registrees'
		);

		//Insert the post and get the ID
		$post_id = wp_insert_post( $register_post );


		$field_register_event = get_acf_key('event_id');
		$field_register_event_value = array($eventID);
		$field_register_user = get_acf_key('user');
		$field_register_user_value = $user_id;

		update_field( $field_register_event, $field_register_event_value, $post_id );
		update_field( $field_register_user, $field_register_user_value, $post_id );
		update_user_meta( $user_id, 'phone', $_POST['phone'] );
		update_user_meta( $user_id, 'dob', $_POST['dob'] );
		update_user_meta( $user_id, 'created_at_event', 'Yes' );

	}


}



function my_script_enqueuer2() {
  // Register create_user function
   wp_register_script( "create_user", WP_PLUGIN_URL.'/register/create_user.js', array('jquery') );
   wp_localize_script( 'create_user', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));


   //Enqueue
   wp_enqueue_script( 'jquery' );
   wp_enqueue_script( 'create_user' );

}

add_action( 'init', 'my_script_enqueuer2' );

show_admin_bar(false);

function getComments() {

	$comments = get_posts(array(
        'posts_per_page' => -1,
        'post_type'      => 'notes',
		'author' => $current_user->ID
    ));

    $event = clean($_GET['event']);

    $count = 0;

    foreach($comments as $c){

    	$title = get_post(get_field('event_id'));
    	if($event == clean($title->post_title)){
    		$count++;
    	}
    }

	return count($count);

}

function getCountRegister() {
	$registered = 0;
	$walkins = 0;


	$users =  get_posts(array(
	    'posts_per_page'  => -1,
	    'post_type'     => 'registrees'
	)); // will get all the users

	foreach ($users as $u) {
	    $e = get_field('event_id', $u->ID);

	    if($_GET['eventID'] == $e[0]){

	        $a = get_field('user', $u->ID);

	        $um = get_user_meta($a['ID']);

	        if(isset($um['created_at_event'][0])){

	            if($um['created_at_event'][0] == 'Yes'){ //This is not correct it should not check for true or false but rather if this = the $_GET['event']
								// remember we decided to change this to the ID of the event
	              $walkins++;
	            } else {
								$registered++;
							}
	        } else {
	            $registered++;
	        }
	    }
	}

	return [$registered, $walkins];
}

//Update the last modified date when a user info is changed
function updateProfile( $user_id ) {
  update_user_meta( $user_id, 'last_modified', current_time( 'mysql' ) );
}

add_action( 'profile_update', 'updateProfile' );


?>
