<?php
/**
 * Template Name: Template Telesale Page
 */

$this_event = get_page_by_title( $_GET['event'], OBJECT, 'offline' );

get_header(); ?>

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

                <div class="attended">
                  <ul class="list list-inline list-unstyled">
                    <li>
                      <div class="card bg-success text-center">
                        <h5>Registered Attendees</h5>
                        <h4><?php echo get_registered($this_event)[0]; ?></h4>
                      </div>
                    </li>
                    <li>
                      <div class="card bg-danger text-center">
                        <h5>Walk-in Attendees</h5>
                        <h4><?php echo get_registered($this_event)[1]; ?></h4>
                      </div>
                    </li>
                  </ul>
                </div>
                
                <div class="pull-left col-lg-4" style="padding:10px 5px;">
                  <a href="#" id="download-attendees" event-data="<?php echo $this_event->ID; ?>" class="btn btn-primary">Download Attendees</a>
                  <br><br>
                  <a href="#" id="download-xls" event-data="<?php echo $this_event->ID; ?>" class="btn btn-primary">Export To XLS</a>
                </div>

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

								<?php
                  
                  $posts_per_page = isset($_GET['user']) && $_GET['user'] != '' ? -1 : 100;
                  
                  $offset = isset($_GET['pg']) ? $_GET['pg'] : 0;

                  $max_search_results = isset($_GET['user']) && $_GET['user'] != '' ? 50 : 100;

                  $users =  get_posts(array(
                      'posts_per_page'  => $posts_per_page,
                      'offset'           => $offset,
                      'post_type'       => 'registrees',
                      'orderby'          => 'user',
                      'order'            => 'ASC',
                      'meta_query'    => array(
                                            'relation' => 'AND',
                                            array(
                                                'key' => 'event_id',
                                                'value' => $this_event->ID,
                                                'compare' => 'LIKE'
                                            )
                                          )
                  ));

                  if($users):
								?>

                <div class="table-responsive">
  								
                  <!-- Table -->
  								<table id="users" class="table tablesorter">
  									
                    <thead>
  										<th>IDP ID</th>
  										<th>Full Name</th>
  										<th>DOB</th>
  										<th>Phone</th>
  										<th>Email</th>
  										<th>Alt Email</th>
                      <th>Attended At</th>
                      <th>Attended</th>
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
                      <th>Lead N/O</th>
  										<th>Telesale Note</th>
  										<th>Institution Note</th>
                      <th>Last Modified</th>
  										<th class="tableexport-ignore">Controls</th>
  									</thead>
  									
                    <tbody>
  										
                      <?php $results = 0; foreach($users as $u):

                        if($results >= $max_search_results){ 
                          break; 
                        }

                        $a = get_post_meta($u->ID)['user'][0];

                        $user = get_user_by('id', $a);

  											$usermeta = get_user_meta($a);

  											$contains = true;

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
                       
  											if($contains) :

  										?>
  										<tr user-data="<?php echo $user->ID; ?>" reg-data="<?php echo $u->ID; ?>">

                        <td class="user-id">
                          <?php echo $user->ID; ?>
                        </td>

                        <td class="user-fullname" field-data="alt_name" field-type="meta">

                          <!-- IF WE HAVE AN ALT NAME, USE THAT -->
                          <?php if($usermeta['alt_name'][0]) : ?>

                            <!-- UPDATE THE ALT NAME -->
                            <div class="field-default"><?php echo issetor($usermeta['alt_name'][0]); ?></div>
                            <input class="update-field form-control" type="text" value="<?php echo issetor($usermeta['alt_name'][0]); ?>" autocomplete="off" />
                          <?php else : ?>

                            <!-- SHOW ORIGINAL NAME AND UPDATE THE ALT NAME IF NEEDED -->
                            <div class="field-default"><?php echo $user->display_name; ?></div>
                            <input class="update-field form-control" type="text" value="<?php echo issetor($usermeta['alt_name'][0]); ?>" autocomplete="off" />
                          <?php endif; ?>

                          <span class="updating">updating...</span>
                          <span class="update-control btn btn-full btn-success save">Save</span>
                        </td>

                        <td class="user-dob editable" field-data="dob" field-type="meta">
                          <div class="field-default"><?php echo issetor($usermeta['dob'][0]); ?></div>
                          <input class="update-field form-control" type="text" value="<?php echo issetor($usermeta['dob'][0]); ?>" autocomplete="off" />
                          <span class="updating">updating...</span>
                          <span class="update-control btn btn-full btn-success save">Save</span>
                        </td>

                        <td class="user-phone editable" field-data="phone" field-type="meta">
                          <div class="field-default"><?php echo issetor($usermeta['phone'][0]); ?></div>
                          <input class="update-field form-control" type="text" value="<?php echo issetor($usermeta['phone'][0]); ?>" autocomplete="off" />
                          <span class="updating">updating...</span>
                          <span class="update-control btn btn-full btn-success save">Save</span>
                        </td>
                        <td class="user-email editable">
                          <?php echo $user->user_email; ?>
                        </td>
                        <td class="user-email editable" field-data="alt_email" field-type="meta">

                          <!-- IF WE HAVE AN ALT EMAIL, USE THAT -->
                          <?php if($usermeta['alt_email'][0]) : ?>

                            <!-- UPDATE THE ALT EMAIL -->
                            <div class="field-default"><?php echo issetor($usermeta['alt_email'][0]); ?></div>
                            <input class="update-field form-control" type="text" value="<?php echo issetor($usermeta['alt_email'][0]); ?>" autocomplete="off" />
                          <?php else : ?>

                            <!-- SHOW ORIGINAL EMAIL AND UPDATE THE ALT EMAIL IF NEEDED -->
                            <div class="field-default"><?php echo $user->user_email; ?></div>
                            <input class="update-field form-control" type="text" value="<?php echo issetor($usermeta['alt_email'][0]); ?>" autocomplete="off" />
                          <?php endif; ?>

                          <span class="updating">updating...</span>
                          <span class="update-control btn btn-full btn-success save">Save</span>

                        </td>

                        <td class="user-attended editable" field-data="attended_at" field-type="acf" >
                          <div class="field-default"><?php echo get_post_meta($u->ID)['attended_at'][0]; ?></div>

                          <input class="update-field form-control" type="text" value="<?php echo get_post_meta($u->ID)['attended_at'][0]; ?>" autocomplete="off" />

                          <span class="updating">updating...</span>
                          <span class="update-control btn btn-full btn-success save">Save</span>
                          <div class="helper">Format : MM/DD/YYYY</div>
                        </td>

                        <td class="attended-place editable" field-data="attended" field-type="acf">
                          <div class="field-default"><?php echo get_field('attended', $u->ID)[0]->post_title; ?></div>

                          <select class="form-control update-field select-active-event-<?php echo $user->ID; ?>" autocomplete="off">
                              <option value="0"></option>
                              <?php $cities = get_cities(); ?>
                              <?php foreach($cities as $cm) : ?>
                                  <?php if(str_replace(' ', '', $cm->post_title) == str_replace(' ', '', get_field('attended', $u->ID)[0]->post_title)) : ?>
                                      <option selected="selected" value="<?php echo $cm->ID; ?>"><?php echo $cm->post_title; ?></option>
                                  <?php else : ?>
                                      <option value="<?php echo $cm->ID; ?>"><?php echo $cm->post_title; ?></option>
                                  <?php endif; ?>
                              <?php endforeach; ?>
                          </select>

                          <span class="updating">updating...</span>
                          <span class="update-control btn btn-full btn-success save">Save</span>
                        </td>

                        <td class="user-idp editable" field-data="nearest_IDP" field-type="meta">
                          <div class="field-default"><?php echo issetor($usermeta['nearest_IDP'][0]); ?></div>
                          <?php $nearest_IDP = get_select_options('Nearest IDP Office'); ?>
                          <select class="form-control update-field select-active-event-<?php echo $user->ID; ?>" autocomplete="off">
                              <option value="0"></option>
                              <?php foreach($nearest_IDP as $cm) : ?>
                                  <?php if(str_replace(' ', '', $cm) == str_replace(' ', '', $usermeta['nearest_IDP'][0])) : ?>
                                      <option selected="selected" value="<?php echo $cm; ?>"><?php echo $cm; ?></option>
                                  <?php else : ?>
                                      <option value="<?php echo $cm; ?>"><?php echo $cm; ?></option>
                                  <?php endif; ?>
                              <?php endforeach; ?>
                          </select>

                          <span class="updating">updating...</span>
                          <span class="update-control btn btn-full btn-success save">Save</span>
                        </td>

                        <td class="user-district editable" field-data="district" field-type="meta">
  											  <div class="field-default"><?php echo issetor($usermeta['district'][0]); ?></div>
  											  <input class="update-field form-control" type="text" value="<?php echo issetor($usermeta['district'][0]); ?>" autocomplete="off" />

                          <span class="updating">updating...</span>
                          <span class="update-control btn btn-full btn-success save">Save</span>
  											</td>

                        <td class="user-destination editable" field-data="destination" field-type="meta">
                          <div class="field-default"><?php echo issetor($usermeta['destination'][0]); ?></div>
                          <?php $destination = get_select_options('Destination'); ?>
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

                          <span class="updating">updating...</span>
                          <span class="update-control btn btn-full btn-success save">Save</span>
                        </td>

                        <td class="user-counsellor editable" field-data="counsellor" field-type="meta">
                          <div class="field-default"><?php echo issetor($usermeta['counsellor'][0]); ?></div>
                          <div class="field-edit"><input class="update-field form-control" type="text" value="<?php echo issetor($usermeta['counsellor'][0]); ?>" /></div>

                          <span class="updating">updating...</span>
                          <span class="update-control btn btn-full btn-success save">Save</span>
                        </td>

                        <td class="user-oscar editable" field-data="oscar_id" field-type="meta">
                          <div class="field-default"><?php echo issetor($usermeta['oscar_id'][0]); ?></div>
                          <div class="field-edit"><input class="update-field form-control" type="text" value="<?php echo issetor($usermeta['oscar_id'][0]); ?>" /></div>

                          <span class="updating">updating...</span>
                          <span class="update-control btn btn-full btn-success save">Save</span>
                        </td>

                        <td class="user-gpa editable" field-data="gpa" field-type="meta">
                          <div class="field-default"><?php echo issetor($usermeta['gpa'][0]); ?></div>
                          <div class="field-edit"><input class="update-field form-control" type="text" value="<?php echo issetor($usermeta['gpa'][0]); ?>" /></div>

                          <span class="updating">updating...</span>
                          <span class="update-control btn btn-full btn-success save">Save</span>
                        </td>


  											<td class="user-englishlevel editable" field-data="english_level" field-type="meta">
  											  <div class="field-default"><?php echo issetor($usermeta['english_level'][0]); ?></div>
  											  <input class="update-field form-control" type="text" value="<?php echo issetor($usermeta['english_level'][0]); ?>" />

                          <span class="updating">updating...</span>
                          <span class="update-control btn btn-full btn-success save">Save</span>
  											</td>
                        
  											<td class="user-currentmajor editable" field-data="current_major" field-type="meta">
											    <div class="field-default"><?php echo issetor($usermeta['current_major'][0]); ?></div>
                          <?php $current_major = get_select_options('Current Major'); ?>
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

                          <span class="updating">updating...</span>
                          <span class="update-control btn btn-full btn-success save">Save</span>
  											</td>

  											<td class="user-currentschool editable" field-data="current_university" field-type="meta">
											    <div class="field-default"><?php echo issetor($usermeta['current_university'][0]); ?></div>
											    <input class="update-field form-control" type="text" value="<?php echo issetor($usermeta['current_university'][0]); ?>" />

                          <span class="updating">updating...</span>
                          <span class="update-control btn btn-full btn-success save">Save</span>
  											</td>

                        <td class="user-interestedlevel editable" field-data="interest_level_study" field-type="meta">
											    <div class="field-default"><?php echo issetor($usermeta['interest_level_study'][0]); ?></div>
                          <?php $interested_level = get_select_options('Interest Level of Study'); ?>
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

                          <span class="updating">updating...</span>
                          <span class="update-control btn btn-full btn-success save">Save</span>
  											</td>

  											<td class="user-interestedmajor editable" field-data="interested_major" field-type="meta">
  											  <div class="field-default"><?php echo issetor($usermeta['interested_major'][0]); ?></div>
  											  <input class="update-field form-control" type="text" value="<?php echo issetor($usermeta['interested_major'][0]); ?>" />

                          <span class="updating">updating...</span>
                          <span class="update-control btn btn-full btn-success save">Save</span>
  											</td>

  											<td class="user-intakeyear editable" field-data="intake_year" field-type="meta">
											    <div class="field-default"><?php echo issetor($usermeta['intake_year'][0]); ?></div>
                          <?php $year = get_select_options('Intake (Year)'); ?>
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

                          <span class="updating">updating...</span>
                          <span class="update-control btn btn-full btn-success save">Save</span>
  											</td>

  											<td class="user-intakemonth editable" field-data="intake_month" field-type="meta">
											    <div class="field-default"><?php echo issetor($usermeta['intake_month'][0]); ?></div>
                          <?php $month = get_select_options('Intake Month'); ?>
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

                          <span class="updating">updating...</span>
                          <span class="update-control btn btn-full btn-success save">Save</span>
  											</td>

                        <td class="user-status editable" field-data="status" field-type="meta">
                          <div class="field-default"><?php echo issetor($usermeta['status'][0]); ?></div>
                          <?php $status = get_select_options('Status'); ?>
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

                          <span class="updating">updating...</span>
                          <span class="update-control btn btn-full btn-success save">Save</span>
                        </td>

                        <td class="user-lead editable" field-data="lead" field-type="meta">
                          <div class="field-default"><?php echo issetor($usermeta['lead'][0]); ?></div>
                          <select class="form-control update-field select-active-event-<?php echo $user->ID; ?>" autocomplete="off">
                            <?php if($usermeta['lead'][0] == 'New') : ?>
                              <option value="0"></option>
                              <option selected="selected" value="New">New</option>
                              <option value="Old">Old</option>
                            <?php elseif($usermeta['lead'][0] == 'Old') : ?>
                              <option value="0"></option>
                              <option value="New">New</option>
                              <option selected="selected" value="Old">Old</option>
                            <?php else : ?>
                              <option selected="selected" value="0"></option>
                              <option value="New">New</option>
                              <option value="Old">Old</option>
                            <?php endif; ?>
                          </select>

                          <span class="updating">updating...</span>
                          <span class="update-control btn btn-full btn-success save">Save</span>
                        </td>

  											<td class="user-note tableexport-ignore">
  											    <!-- Button to trigger the modal notes -->
                            <button type="button" class="btn btn-sm" data-toggle="modal" data-target="#myModal-<?php echo $i; ?>"><span class="edit-notes">Edit Notes</span></button>

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
                                            <button type="button" class="btn btn-primary save-notes center-block" data-dismiss="modal" user-data="<?php echo $user->ID; ?>">Save and Close</button>
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
                                                          'value' => $this_event->ID,//$_GET['eventID'],
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
                            <button type="button" class="btn btn-sm <?php echo $class; ?>" data-toggle="modal" data-target="#myModal-inst-<?php echo $i; ?>"><span class="view-notes">View Notes</span></button>

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
                                            <button type="button" class="btn btn-default center-block" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                           </div>
  											</td>
                        <td class="user-last-modified">
                          <span class="field-def">
                            <?php if($usermeta['last_modified_time'][0] && ($usermeta['last_modified_by'][0])) : ?>
                              <?php echo issetor($usermeta['last_modified_time'][0]); ?> by
                              <?php echo issetor($usermeta['last_modified_by'][0]); ?>
                            <?php endif; ?>
                          </span>
                        </td>

  											<td class="edit-controls tableexport-ignore">
  											    <div class="btn btn-full btn-default edit"><span class="edit-txt">Edit</span><span class="done-txt">Done</span></div>
  											</td>
  										</tr>
  										<?php endif; ?>
                      <?php $i++; $results++; ?>
  										<?php	endforeach; ?>
  									</tbody>
  								</table>

                  <div class="export-table hidden"></div>

                </div>
								<?php wp_reset_postdata(); ?>
                <div class="page-nav">
                  <?php custom_pagination($this_event, $posts_per_page, $offset); ?>
                </div>
							<?php endif; ?>
					<?php else: ?>

						<h1>You do not have sufficient permissions to access this page</h1>

					<?php endif; ?><!-- End limit View for Staff -->
				</main><!-- #main -->

			</div><!-- #primary -->

		</div><!-- .content-left-wrap -->

	</div><!-- .container -->


<?php get_footer(); ?>
