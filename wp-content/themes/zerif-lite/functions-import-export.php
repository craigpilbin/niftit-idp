<?php

// BULK IMPORT SCRIPT
function addUsersFromCSV(){

    $file_name = $_POST['file_name'];
    $event_id = $_POST['event_id'];

    $users = [];
    $failed_users = [];
    $successful_users = [];

    $file = fopen($_SERVER['DOCUMENT_ROOT'] . "/csv/" . $file_name, "r") or die("Unable to open file!");
    while(! feof($file)){
        array_push($users, fgetcsv($file));
    }
    fclose($file);

    foreach($users as $u){
        
        // VALIDATE EMAIL
        $valid_user = validate_email($u[1]);

        // IF USERNAME EXISTS
        if(username_exists($u[1]))
            $valid_user = "Sorry, that username already exists";

        // IF EMAIL EXISTS
        if(email_exists($u[1]))
            $valid_user = "Sorry, that email address already exists";

        
        if($valid_user == 1){

            $userdata = array(
                'user_login'  =>  $u[1],
                'display_name' => stripVN($u[0]),
                'user_email' => $u[1],
                'user_url'    =>  get_site_url(),
                'user_pass'   =>  NULL
            );

            $user_id = wp_insert_user($userdata);

            if ( ! is_wp_error( $user_id ) ) {

                update_user_meta( $user_id, 'phone', $u[2] );
                update_user_meta( $user_id, 'district', $u[5] );
                update_user_meta( $user_id, 'destination', $u[6] );
                update_user_meta( $user_id, 'intake_year', $u[3] );
                update_user_meta( $user_id, 'intake_month', $u[4] );

                $reg = registerUser($user_id, $event_id);

                if($reg){
                    // ADD IF NO ERRORS
                    array_push($u, true);
                    array_push($successful_users, $u);
                } else {
                    // ERROR REGISTERING
                    array_push($u, "ERROR REGISTERING POST");
                    array_push($successful_users, $u);
                }

            } else {

                // ADD IF WP ERRORS
                array_push($u, $user_id->get_error_message());
                array_push($failed_users, $u);

            }
            
        } else {
            array_push($u, $valid_user);
            array_push($failed_users, $u);
        }

    }

    echo json_encode([$successful_users, $failed_users]);

    die();

}
add_action( 'wp_ajax_nopriv_addUsersFromCSV', 'addUsersFromCSV' );
add_action( 'wp_ajax_addUsersFromCSV', 'addUsersFromCSV' );


function registerUser($user_id, $event_id){

    $post_id = false;

    $event = get_post($event_id);

    $register_post = array(
        'post_status' => 'publish',
        'post_title' => $event->post_title,
        'post_type' => 'registrees'
    );

    // INSERT THE POST AND GET THE ID
    $post_id = wp_insert_post( $register_post );

    // ACF KEYS
    $event_key = get_acf_key('event_id');
    $user_key = get_acf_key('user');

    // ACF UPDATE FIELDS
    update_field( $event_key, array($event_id), $post_id );
    update_field( $user_key, $user_id, $post_id );

    return $post_id;

}


// RETURN FILES
function getCsvFiles(){
    
    $dir = $_SERVER['DOCUMENT_ROOT'] . "/csv/";

    $files = scandir($dir);

    //$files = array_diff($files, array('.', '..'));

    echo json_encode($files);

    die();
}

add_action( 'wp_ajax_nopriv_getCsvFiles', 'getCsvFiles' );
add_action( 'wp_ajax_getCsvFiles', 'getCsvFiles' );


// HELPER METHODS
function validate_email($email){
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        return 'Sorry, that email address is invalid';
    else
        return true;
}


function returnAllEvents(){
    $all_events = get_posts(array(
        'numberposts'   => -1,
        'exclude' => array(121,124,125),
        'post_type'     => 'offline'
    ));

    return $all_events;
}

?>