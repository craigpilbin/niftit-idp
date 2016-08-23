<?php /* start WPide restore code */
                                    if ($_POST["restorewpnonce"] === "35a10a326bebcaedec26764c31fefbc8d0ff75fbdc"){
                                        if ( file_put_contents ( "/var/www/html/wp-content/themes/zerif-lite/template-admin-telesales.php" ,  preg_replace("#<\?php /\* start WPide(.*)end WPide restore code \*/ \?>#s", "", file_get_contents("/var/www/html/wp-content/plugins/wpide/backups/themes/zerif-lite/template-admin-telesales_2016-07-21-09.php") )  ) ){
                                            echo "Your file has been restored, overwritting the recently edited file! \n\n The active editor still contains the broken or unwanted code. If you no longer need that content then close the tab and start fresh with the restored file.";
                                        }
                                    }else{
                                        echo "-1";
                                    }
                                    die();
                            /* end WPide restore code */ ?><?php
/**
 * Template Name: Template Telesale Page
 */
get_header(); ?>

<?php


$i = 0;

// SETUP FIELDS FOR EDITING
$wppb_fields = get_option( 'wppb_manage_fields', 'not_found' );
$current_major = [];
$interested_level = [];
$year = [];
$month = [];
$destination = [];
$status = [];


foreach($wppb_fields as $val){
    if($val['field-title'] == 'Current Major'){
        $current_major = explode(',', $val['options']);
    }
    if($val['field-title'] == 'Interest Level of Study'){
        $interested_level = explode(',', $val['options']);
    }
    if($val['field-title'] == 'Intake (Year)'){
        $year = explode(',', $val['options']);
    }
    if($val['field-title'] == 'Intake Month'){
        $month = explode(',', $val['options']);
    }
    if($val['field-title'] == 'Destination'){
        $destination = explode(',', $val['options']);
    }
    if($val['field-title'] == 'Status'){
        $status = explode(',', $val['options']);
    }
}


$notes = [];



// foreach($notes_query as $note){
//
//     $e = get_field('event_id', $note->ID);
//
//     if(is_object($event_id[0])){
//         $e[0] = $e[0]->ID;
//     }
//
//     if($e[0] == 106){
//         array_push($notes, $note);
//     }
// }



wp_reset_postdata();



?>

<div class="clear"></div>

</header> <!-- / END HOME SECTION  -->

<div id="content" class="site-content">

	<div class="container container-admin">

		<div class="content-left-wrap col-md-12">

			<div id="primary" class="content-area">

				<main id="main" class="site-main" role="main">
					<?php

							//Limit permissions to contributor AKA Staff
							if ( current_user_can('moderate_comments') || current_user_can( 'manage_options' )) : ?>
								<h1>Telesale</h1>

                <!-- Search -->
                <div class="col-lg-4 text-right pull-right">
                  <form id="search" class="navbar-form" role="search">
                    <div class="input-group">
                      <input type="text" class="form-control hidden" placeholder="" value="<?php echo $_GET['event']; ?>" name="event">
                      <input type="text" class="form-control" placeholder="Search User" value="<?php if(isset($_GET['user'])) echo $_GET['user']; ?>" name="user">
                      <div class="input-group-btn">
                        <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </form>
                </div>

                <div class="clearfix"></div>

                <!-- Table -->
								<?php
									$users =  get_posts(array(
                      'posts_per_page'	=> -1,
                      'post_type'			=> 'registrees'
                  )); // will get all the users

                  if($users):
								?>

                <div class="table-responsive">
  								<!-- Table -->
  								<table id="users" class="table tablesorter">
  									<thead>
  										<th>IPD ID</th>
  										<th>Full Name</th>
  										<th>DOB</th>
  										<th>Phone</th>
  										<th>Email</th>
                      <th>Nearest IDP</th>
                      <th>District/Province</th>
                      <th>Destination</th>



                      <th>Counsellor</th>
                      <th>Oscar ID</th>
                      <th>GPA</th>


  										<th>English Level</th>
  										<th>Current Major/Year</th>
  										<th>Current School/University</th>
  										<th>Interested Level of Study</th>
  										<th>Interested Major</th>
  										<th>Intake (Year)</th>
  										<th>Intake (Month)</th>
                      <th>Status</th>

  										<th>Telesale Note</th>
  										<th>Institution Note</th>
  										<th class="tableexport-ignore">Controls</th>
  									</thead>
  									<tbody>
  										<?php foreach($users as $u):

                        $a = get_post_meta($u->ID)['user'][0];

                        $user = get_user_by('id', $a);

  											$usermeta = get_user_meta($a);

  											// IF CONTAINS STRING $_GET['user']
  											$contains = false;
  											if(isset($_GET['user']) && $_GET['user'] != '') {
  													$contains = false;
  													if (strpos(strtolower($user->display_name), strtolower($_GET['user'])) !== false) {
  																						$contains = true;
  																				} elseif (strpos(strtolower($usermeta['phone'][0]), strtolower($_GET['user'])) !== false) {
  																						$contains = true;
  																				} elseif (strpos(strtolower($user->user_email), strtolower($_GET['user'])) !== false) {
  																						$contains = true;
  																				} elseif (strpos(strtolower($user->ID), strtolower($_GET['user'])) !== false) {
  																						$contains = true;
  																				} else {
  																						$contains = false;
  																				}
  											}
  											//echo "CONTAINS : ".$contains;
  																		if($contains) :
  										?>
  										<tr user-data="<?php echo $user->ID; ?>">
  											<td class="user-id"><?php echo $user->ID; ?></td>
  											<td class="user-fullname"><?php echo $user->display_name; ?></td>
  											<td class="user-dob"><?php echo issetor($usermeta['dob'][0]); ?></td>
  											<td class="user-phone"><?php echo issetor($usermeta['phone'][0]); ?></td>
  											<td class="user-email"><?php echo $user->user_email; ?></td>
  											<td class="user-idp"><?php echo issetor($usermeta['nearest_IDP'][0]); ?></td>
                        <td class="user-district">
  											    <div class="field-default"><?php echo issetor($usermeta['district'][0]); ?></div>
  											    <input class="update-field" type="text" value="<?php echo issetor($usermeta['district'][0]); ?>" />
  											</td>
                        <td class="user-destination">
                          <div class="field-default"><?php echo issetor($usermeta['destination'][0]); ?></div>
                          <select class="form-control update-field select-active-event-<?php echo $user->ID; ?>" autocomplete="off">
                              <option value="0"></option>
                              <?php foreach($destination as $cm) : ?>
                                  <?php if(str_replace(' ', '', $cm) == str_replace(' ', '', $usermeta['destination'][0])) : ?>
                                      <option selected="selected" value="<?php echo $cm; ?>"><?php echo $cm; ?></option>
                                  <?php else : ?>
                                      <option value="<?php echo $cm; ?>"><?php echo $cm; ?></option>
                                  <?php endif; ?>
                              <?php endforeach; ?>
                          </select>
                        </td>

                        <td class="user-counsellor">
                            <div class="field-default"><?php echo issetor($usermeta['counsellor'][0]); ?></div>
                            <div class="field-edit"><input class="update-field" type="text" value="<?php echo issetor($usermeta['counsellor'][0]); ?>" /></div>
                        </td>

                        <td class="user-oscar">
                          <div class="field-default"><?php echo issetor($usermeta['oscar_id'][0]); ?></div>
                          <div class="field-edit"><input class="update-field" type="text" value="<?php echo issetor($usermeta['oscar_id'][0]); ?>" /></div>
                        </td>

                        <td class="user-gpa">
                          <div class="field-default"><?php echo issetor($usermeta['gpa'][0]); ?></div>
                          <div class="field-edit"><input class="update-field" type="text" value="<?php echo issetor($usermeta['gpa'][0]); ?>" /></div>
                        </td>


  											<td class="user-englishlevel">
  											    <div class="field-default"><?php echo issetor($usermeta['english_level'][0]); ?></div>
  											    <input class="update-field" type="text" value="<?php echo issetor($usermeta['english_level'][0]); ?>" />
  											</td>

  											<td class="user-currentmajor">
  											    <div class="field-default"><?php echo issetor($usermeta['current_major'][0]); ?></div>
  											    <select class="form-control update-field select-active-event-<?php echo $user->ID; ?>" autocomplete="off">
  											        <option value="0"></option>
  											        <?php foreach($current_major as $cm) : ?>
  											            <?php if(str_replace(' ', '', $cm) == str_replace(' ', '', $usermeta['current_major'][0])) : ?>
  											                <option selected="selected" value="<?php echo $cm; ?>"><?php echo $cm; ?></option>
  											            <?php else : ?>
  											                <option value="<?php echo $cm; ?>"><?php echo $cm; ?></option>
  											            <?php endif; ?>
  											        <?php endforeach; ?>
  											    </select>
  											</td>
  											<td class="user-currentschool">
  											    <div class="field-default"><?php echo issetor($usermeta['current_university'][0]); ?></div>
  											    <input class="update-field" type="text" value="<?php echo issetor($usermeta['current_university'][0]); ?>" />
  											</td>
  											<td class="user-interestedlevel">
  											    <div class="field-default"><?php echo issetor($usermeta['interest_level_study'][0]); ?></div>
  											    <select class="form-control update-field select-active-event-<?php echo $user->ID; ?>" autocomplete="off">
  											        <option value="0"></option>
  											        <?php foreach($interested_level as $cm) : ?>
  											            <?php if(str_replace(' ', '', $cm) == str_replace(' ', '', $usermeta['interest_level_study'][0])) : ?>
  											                <option selected="selected" value="<?php echo $cm; ?>"><?php echo $cm; ?></option>
  											            <?php else : ?>
  											                <option value="<?php echo $cm; ?>"><?php echo $cm; ?></option>
  											            <?php endif; ?>
  											        <?php endforeach; ?>
  											    </select>
  											</td>

  											<td class="user-interestedmajor">
  											    <div class="field-default"><?php echo issetor($usermeta['interested_major'][0]); ?></div>
  											    <input class="update-field" type="text" value="<?php echo issetor($usermeta['interested_major'][0]); ?>" />
  											</td>

  											<td class="user-intakeyear">
  											    <div class="field-default"><?php echo issetor($usermeta['intake_year'][0]); ?></div>
  											    <select class="form-control update-field select-active-event-<?php echo $user->ID; ?>" autocomplete="off">
  											        <option value="0"></option>
  											        <?php foreach($year as $cm) : ?>
  											            <?php if(str_replace(' ', '', $cm) == str_replace(' ', '', $usermeta['intake_year'][0])) : ?>
  											                <option selected="selected" value="<?php echo $cm; ?>"><?php echo $cm; ?></option>
  											            <?php else : ?>
  											                <option value="<?php echo $cm; ?>"><?php echo $cm; ?></option>
  											            <?php endif; ?>
  											        <?php endforeach; ?>
  											    </select>
  											</td>
  											<td class="user-intakemonth">
  											    <div class="field-default"><?php echo issetor($usermeta['intake_month'][0]); ?></div>
  											    <select class="form-control update-field select-active-event-<?php echo $user->ID; ?>" autocomplete="off">
  											        <option value="0"></option>
  											        <?php foreach($month as $cm) : ?>
  											            <?php if(str_replace(' ', '', $cm) == str_replace(' ', '', $usermeta['intake_month'][0])) : ?>
  											                <option selected="selected" value="<?php echo $cm; ?>"><?php echo $cm; ?></option>
  											            <?php else : ?>
  											                <option value="<?php echo $cm; ?>"><?php echo $cm; ?></option>
  											            <?php endif; ?>
  											        <?php endforeach; ?>
  											    </select>
  											</td>
                        <td class="user-status">
                          <div class="field-default"><?php echo issetor($usermeta['status'][0]); ?></div>
                          <div class="field-edit">
                            <select class="form-control update-field select-active-event-<?php echo $user->ID; ?>" autocomplete="off">
                                <option value="0"></option>
                                <?php foreach($status as $cm) : ?>
                                    <?php if(str_replace(' ', '', $cm) == str_replace(' ', '', $usermeta['status'][0])) : ?>
                                        <option selected="selected" value="<?php echo $cm; ?>"><?php echo $cm; ?></option>
                                    <?php else : ?>
                                        <option value="<?php echo $cm; ?>"><?php echo $cm; ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                          </div>
                        </td>
  											<td class="user-note tableexport-ignore">
  											    <!-- Button to trigger the modal notes -->
                            <button type="button" class="btn btn-sm" data-toggle="modal" data-target="#myModal-<?php echo $i; ?>"><span class="view-notes">View Notes</span><span class="edit-notes">Edit Notes</span></button>

                            <!-- Modal notes -->
                            <div class="modal fade note-modal" id="myModal-<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-<?php echo $i; ?>">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="myModalLabel">Student Note</h4>
                                        </div>
                                        <div class="modal-body">
                                            <textarea><?php echo issetor($usermeta['telesale_note'][0]); ?></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary save-notes">Update</button>
                                        </div>
                                    </div>
                                </div>
                           </div>
  											   <input class="update-notes-field hidden" type="text" value="<?php echo issetor($usermeta['telesale_note'][0]); ?>" />
  											</td>

                        <td class="user-institution-note tableexport-ignore">
                          <?php
                            $notes_query = get_posts(array(
                                'posts_per_page' => -1,
                                'post_type'      => 'notes',
                                'meta_query'    => array(
                                                      'relation' => 'AND',
                                                      array(
                                                          'key' => 'event_id',
                                                          'value' => $_GET['eventID'],
                                                          'compare' => 'LIKE'
                                                      ),
                                                      array(
                                                        'key' => 'attendee_id',
                                                        'value' => $user->ID,
                                                        'compare' => 'IN'
                                                      )
                                                    )
                            ));
                            //Adding disabled class if not notes
                            $class = '';
                            if(!$notes_query):
                              $class = 'disabled';
                            endif;
                            ?>
  											    <!-- Button to trigger the modal notes -->
                            <button type="button" class="btn btn-sm <?php echo $class; ?>" data-toggle="modal" data-target="#myModal-inst-<?php echo $i; ?>"><span class="view-notes">View Notes</span><span class="edit-notes">Edit Notes</span></button>

                            <!-- Modal notes -->
                            <div class="modal fade note-modal" id="myModal-inst-<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-<?php echo $i; ?>">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                        <div class="modal-body">

                                          <?php

                                            foreach($notes_query as $n):
                                              if(get_post_meta($n->ID)['notes'][0]):
                                                $institution_id = get_post_meta($n->ID)['institutiion_id'][0];

                                                $institution_reset = get_user_by('ID', $institution_id);

                                                $institution_name = get_posts(array(
                                                    'posts_per_page' => -1,
                                                    'post_type'      => 'institution',
                                                    'meta_key'       => 'ID',
                                                    'post__in'     => array(get_post_meta($n->ID)['institutiion_id'])
                                                ));

                                                echo '<h1>' . $institution_reset->display_name . '</h1>';
                                                echo get_post_meta($n->ID)['notes'][0];
                                                echo '<hr/>';
                                              endif;
                                            endforeach;

                                           ?>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                           </div>
  											</td>

  											<td class="edit-controls tableexport-ignore">
  											    <div class="btn btn-full btn-default edit">Edit</div>
  											    <div class="btn btn-full btn-success save">Save</div>
  											</td>
  										</tr>
  										<?php endif; ?>
                      <?php $i++; ?>
  										<?php	endforeach; ?>
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

	</div><!-- .container -->

    <style type="text/css">
        .update-field {
            display:none;
            max-width:100px;
        }
        .edit-notes {
            display:none;
        }
    </style>

	<script type="text/javascript">
	    jQuery('.edit-controls .edit').click(function(){
	        var row = jQuery(this).parent().parent();

	        row.find('.field-default').hide();
	        row.find('.update-field').show();

	        row.find('.view-notes').hide();
	        row.find('.edit-notes').show();
	    });

	    jQuery('.note-modal').on('shown.bs.modal', function(){

            jQuery('.user-note .save-notes').unbind('click').bind('click', function(){
    	        var cell = jQuery(this).parent().parent();
    	        var newNotes = cell.find('textarea').val();

    	        cell.closest('td').find('.update-notes-field').val(newNotes);

    	        cell.closest('.note-modal').modal('toggle');
    	    });

        });


	    jQuery('.edit-controls .save').click(function(){
	        var row = jQuery(this).parent().parent();
	        var userId = row.attr('user-data');

	        var district = row.find('.user-district .update-field').val();
	        var english_level = row.find('.user-englishlevel .update-field').val();
            var current_major = row.find('.user-currentmajor .update-field').val();
            var current_university = row.find('.user-currentschool .update-field').val();
            var interest_level_study = row.find('.user-interestedlevel .update-field').val();
            var interested_major = row.find('.user-interestedmajor .update-field').val();
            var intake_year = row.find('.user-intakeyear .update-field').val();
            var intake_month = row.find('.user-intakemonth .update-field').val();
            var telesale_note = row.find('.user-note .update-notes-field').val();

            //Extra
            var counsellor = row.find('.user-counsellor .update-field').val();
            var oscar_id = row.find('.user-oscar .update-field').val();
            var gpa = row.find('.user-gpa .update-field').val();
            var destination = row.find('.user-destination .update-field').val();
            var status = row.find('.user-status .update-field').val();


	        jQuery.ajax( {
                url : myAjax.ajaxurl,
                type: 'POST',
                data: {
                    action  : 'u_cb',
                    'id':userId,
                    'district':district,
                    'english_level':english_level,
                    'current_major':current_major,
                    'current_university':current_university,
                    'interest_level_study':interest_level_study,
                    'interested_major':interested_major,
                    'intake_year':intake_year,
                    'intake_month':intake_month,
                    'telesale_note':telesale_note,

                    'counsellor': counsellor,
                    'oscar_id':oscar_id,
                    'gpa': gpa,
                    'destination':destination,
                    'status':status
                }
            } )
            .success( function( results ) {
                console.log( 'User Meta Updated!', results);
                location.reload();
            } )
            .fail( function( data ) {
                console.log( data.responseText );
                console.log( 'Request failed: ' + data.statusText );
            } );
	    });
	</script>

<?php get_footer(); ?>
