<?php
/**
* Template Name: Template Institution Page
*/

$this_user_id = get_current_user_id();
$user_notes = [];

$posts = get_posts(array(
  'posts_per_page' => -1,
  'post_type'      => 'notes'
));

foreach( $posts as $post ){
  $inst = get_field('institutiion_id', $post->ID);

  if(isset($inst['ID'])){
      if($inst['ID'] == $this_user_id){
        array_push($user_notes, $post->ID);
      }
    }
}

wp_reset_postdata();

//updateEventId();
//addUsersFromCSV();
//addUsersToRegistrees();

get_header(); ?>

<div class="clear"></div>

</header> <!-- / END HOME SECTION  -->



<div id="content" class="site-content">

  <?php
    //Only display the page if the current status is upcoming

    $event = get_posts(array(
      'posts_per_page'	=> 1,
      'post_type'			=> 'offline',
      's' => $_GET['event']
      )
    );
    $status = get_post_meta($event[0]->ID)['status'][0];


    if ($status == 'Upcoming') :?>

    <div class="container container-admin">

        <div class="content-left-wrap col-md-12">

            <div id="primary" class="content-area">

                <main id="main" class="site-main" role="main">
                    <?php
                    //Limit permissions to author AKA Institution
                    if ( current_user_can('publish_pages') || current_user_can( 'manage_options' )) : ?>
                        <h1>Institution Page</h1>
                        <h3>You are login in as institution: <?php echo wp_get_current_user()->display_name; ?></h3>
						<h4>Interviews: <?php echo getComments();?> </h4>


                        <!-- Search -->
                        <div class="col-lg-3">
                            <form id="search" class="navbar-form" role="search">
                                <div class="input-group">
                                    <input type="text" class="form-control hidden" placeholder="" value="<?php echo $_GET['event']; ?>" name="event">
                                    <input type="text" class="form-control" placeholder="Search ID" value="<?php if(isset($_GET['user'])) echo $_GET['user']; ?>" name="user">
                                    <div class="input-group-btn">
                                        <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Table -->
                        <?php
                        $posts = get_posts(array(
                            'posts_per_page'	=> -1,
                            'post_type'			=> 'registrees'
                            ));

                            if( $posts ): ?>
							<div class="table-responsive">

                            <table id="registrees-institutions" class="table tablesorter">
                                <thead>
                                    <tr>
                                        <!--th>Event Name</th>
                                        <th>City</th>
                                        <th>IDP ID</th-->
                                        <th>Full Name</th>
                                        <th>DOB</th>
                                        <th>Phone</th>
                                        <th>Email</th>

                                        <!--th>District</th>
                                        <th>English Level</th-->

                                        <th>Current Major/Year</th>
                                        <th>Current School/University</th>
                                        <th>Interested Level of Study</th>
                                        <th>Interested Major</th>
                                        <!--th>Intake (Year)</th>
                                        <th>Intake (Month)</th-->
                                        <th>Display NOtes</th>
                                        <th>Add Note</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach( $posts as $post ):
                                        $i++;
                                        setup_postdata( $post );

                                        $id = get_field('event_id'); // Event ID
                                        $cities = get_field('city'); //Get cities
                                        $user = get_field('user'); //User Data

                                        if(isset($user['ID'])){
                                            $user_meta = get_user_meta($user['ID']); // User metadata
                                        }

                                        // IF CONTAINS STRING $_GET['user']
                                        $contains = true;


                                        if(isset($_GET['user']) && $_GET['user'] != '') {

                                            $contains = false;


                                            //if (strpos(strtolower($user['ID']), strtolower($_GET['user'])) !== false) {
                                            if ($user['ID'] == $_GET['user']) {
                                                $contains = true;
                                            } else {
                                                $contains = false;
                                            }
                                        } else {
                                            $contains = false;
                                        }

                                        if($contains) :?>
                                            <tr user-data="<?php echo $user['ID']; ?>">
                                                <!--td><a href="<?php the_permalink(); ?>" class="tableexport-ignore"><?php echo getEventTitle($id[0]); ?></a></td>
                                                <td><?php foreach ($cities as $city):echo $city->post_title;endforeach;?></td>
                                                <td><?php echo $user['ID']; ?></td-->
                                                <td><?php echo $user['display_name']; ?></td>
                                                <td><?php echo esc_html($user_meta['dob'][0]); ?></td>

                                                <td><?php echo $user_meta['phone'][0]; ?></td>
                                                <td><?php echo $user['user_email']; ?></td>

                                                <!--td><?php echo $user_meta['district'][0]; ?></td>
                                                <td><?php echo $user_meta['english_level'][0]; ?></td-->
                                                <td><?php echo $user_meta['current_major'][0]; ?></td>
                                                <td><?php echo $user_meta['current_university'][0]; ?></td>
                                                <td><?php echo $user_meta['interest_level_study'][0]; ?></td>
                                                <td><?php echo $user_meta['interested_major'][0]; ?></td>

                                                <!--td><?php echo $user_meta['intake_year'][0]; ?></td>
                                                <td><?php echo $user_meta['intake_month'][0]; ?></td-->

                                                <td class="notes">
                                                    <?php $m = false; foreach($user_notes as $note) {
                                                        $user_id = get_field('attendee_id', $note);
                                                        $event_id = get_field('event_id', $note);
                                                        $inst_id = get_field('institutiion_id', $note);

                                                        if(is_object($event_id[0])){
                                                            $event_id[0] = $event_id[0]->ID;
                                                        }

                                                        // ONLY SHOW IF THE NOTES ARE FROM THIS INST
                                                        if($this_user_id == $inst_id['ID']){


                                                            if($user['ID'] == $user_id['ID'] && $id[0] == $event_id[0]){

                                                                $iid = get_field('institutiion_id', $note);

                                                                $m = '<span class="aid hidden">'.$user_id['ID'].'</span><span class="iid hidden">'.$iid['ID'].'</span><span class="eid hidden">'.$event_id[0].'</span><span class="nid hidden">'.$note.'</span><span class="note">'.get_field('notes', $note).'</span>';

                                                            }

                                                        }
                                                    }

                                                    if($m){
                                                        echo $m;
                                                    } else {
                                                        echo '<span class="aid hidden">'.$user['ID'].'</span><span class="iid hidden">'.$this_user_id.'</span><span class="eid hidden">'.$id[0].'</span><span class="nid hidden"></span>';
                                                        echo '<span class="note"></span>';
                                                    }?>
                                                </td>

                                                <td>

                                                    <!-- Button to trigger the modal notes -->
                                                    <button type="button" class="btn" data-toggle="modal" data-target="#myModal-<?php echo $i; ?>">Student Note </button>
                                                    <!-- Modal notes -->
                                                    <div class="modal fade note-modal" id="myModal-<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-<?php echo $i; ?>">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                    <h4 class="modal-title" id="myModalLabel">Student Note</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <textarea><?php foreach($user_notes as $note) {
                                                                            $user_id = get_field('attendee_id', $note);
                                                                            $event_id = get_field('event_id', $note);
                                                                            $inst_id = get_field('institutiion_id', $note);

                                                                            if(is_object($event_id[0])){
                                                                                $event_id[0] = $event_id[0]->ID;
                                                                            }
                                                                            // ONLY SHOW IF THE NOTES ARE FROM THIS INST
                                                                            if($this_user_id == $inst_id['ID']){

                                                                                if($user['ID'] == $user_id['ID'] && $id[0] == $event_id[0]->ID){
                                                                                    the_field('notes', $note);
                                                                                }
                                                                            }
                                                                        } ?></textarea>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-primary save-notes">Save changes</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endif; ?><!-- contains -->

                                    <?php endforeach; ?>

                                </tbody>
                            </table>
							</div>

                        <?php wp_reset_postdata(); ?>

                    <?php endif; ?>

                <?php else: ?>

                    <h1>You do not have sufficient permissions to access this page</h1>

                <?php endif; ?><!-- End limit View for Staff -->

            </main><!-- #main -->

        </div><!-- #primary -->

    </div><!-- .content-left-wrap -->

  <?php else: ?>
    <br/>
    <h1 class="text-center">This event is now past, you cannot access it. Please contact your administrator.</h1>
    <br/>
  <?php endif;?>
</div><!-- .container -->

<script type="text/javascript">
        jQuery('.note-modal').on('shown.bs.modal', function(){

            tinyMCE.activeEditor.setContent(jQuery(this).parent().prev().find('.note').html());

            jQuery('.note-modal .save-notes').unbind('click').bind('click', function(){

              var cell = jQuery(this).closest('td').prev();
              var newNotes = tinyMCE.activeEditor.getContent();//jQuery(this).parent().parent().find('textarea').val();

              var aId = cell.find('.aid').text();
              var iId = cell.find('.iid').text();
              var eid = cell.find('.eid').text();
              var nid = cell.find('.nid').text();

              cell.find('.note').empty().html(newNotes);
              //console.log(aId, iId, eid, newNotes, tinyMCE.activeEditor.getContent());
              jQuery(this).closest('.note-modal').modal('toggle');



              jQuery.ajax( {
                url : myAjax.ajaxurl,
                type: 'POST',
                data: {
                    action  : 'uin_cb',
                    'a_id':aId,
                    'i_id':iId,
                    'e_id':eid,
                    'n_id':nid,
                    'notes':newNotes
                    }
                } )
                .success( function( results ) {
                    console.log( 'User Meta Updated!', results);
                    //location.reload();
                } )
                .fail( function( data ) {
                    console.log( data.responseText );
                    console.log( 'Request failed: ' + data.statusText );
                } );
          });

        });
 </script>

<?php get_footer(); ?>
