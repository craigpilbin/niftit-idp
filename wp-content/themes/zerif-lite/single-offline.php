<?php
/**
 * Template Name: Single Offline Event Template
 */
get_header(); ?>

<div class="clear"></div>

</header> <!-- / END HOME SECTION  -->

<div id="content" class="site-content">

	<div class="container">

		<div class="content-left-wrap col-md-12">

			<div id="primary" class="content-area">

				<main id="main" class="site-main" role="main">
					<div class="row row-table">
						<div class="col-lg-8">
							<h3><?php the_title(); ?></h3>
							<div>
								<?php
									// Get ID
									global $id;
									$id = get_the_ID();

									if (get_field('picture')):
										echo '<img src="' . get_field("picture")["sizes"]["large"] .'" alt="event" />';
									endif;
								?>

								<?php the_field('description'); ?>

								<!-- List of participants -->
								<div class="row">
									<h3>Participating Institutions</h3>
									<?php
										$participants = get_field('participating_institutions');

										echo '<table class="table table-responsive">';
											foreach ($participants as $participant):
												echo '<tr>';
													echo '<td><a href="' . $participant->guid . '">' . $participant->post_title . '</a></td>';
												echo '</tr>';
											endforeach;
										echo '</table>';

									?>
								</div>
							</div>
						</div>
						<div class="col-lg-4 bg-mute">
							<h3>Summary Information</h3>
							<div><label>Start Date:</label> <?php echo gmdate("D F Y, H:i", get_field('start_date')); ?></div>
							<div><label>End Date:</label> <?php echo gmdate("D F Y, H:i", get_field('end_date')); ?></div>
							<div><label>Max Attending:</label> <?php echo get_field('max_number_attendees') ?></div>
							<br/>


							<?php
								//If Status is upcoming
								if(!is_user_logged_in ()):
									//If user not logged in then ask them to register
									echo '<a href="' . get_permalink(4) .'" class="btn btn-lg btn-info center-block">Sign Up</a><br/>';
									echo '<a href="' . get_permalink(63) .'" class="btn btn-lg btn-success center-block">Login</a>';

								else:
									//If user is login

										// subscriber
										if ( current_user_can('install_themes') || current_user_can( 'manage_options' )) :


										//Check if there is a registree for this event ID and this current user
										$posts = get_posts(array(
											'numberposts'	=> -1,
											'post_type'		=> 'registrees',
											'meta_query' => array(
												'relation' => 'AND',
												array(
													'key' => 'event_id', // name of custom field
													'value' => '"' . $id . '"', // matches exaclty "123", not just 123. This prevents a match for "1234"
													'compare' => 'LIKE'
												),
												array(
													'key' => 'user', // name of custom field
													'value' => get_current_user_id(), // matches exaclty "123", not just 123. This prevents a match for "1234"
													'compare' => '='
												)
											)
										));

										$eventID = $id;
										$user = wp_get_current_user();
										$userID = get_current_user_id();
										$title = get_the_title();
										$city = 'Ho Chi Minh City';//@TODO: make this dynamic for non JS users

										$nonce = wp_create_nonce("book_offline_event_nonce");

										$link = admin_url('admin-ajax.php?action=book_offline_event
																				&eventID='.$eventID.'
																				&userID='.$userID.'
																				&title='.$title.'
																				&city='.$city.'
																				&nonce='.$nonce);

										//if return is positive (meaning user already register)
										if($posts):

											echo '<a href="'. get_permalink(68) . '" class="btn btn-lg btn-default center-block">See Confirmation</a>
														<p class="small">You are already registered for this event.</p>';


											echo '<a href="'. get_permalink(214) . '/?event='.$title.'&user='.$user->display_name.'" class="btn btn-lg btn-success center-block">Self-Register</a>';


										//else return null (meaning not register to this event);
										else:
												//Get the radio button for city select
												$fields = get_field('city');

												setCitiesLSelect($fields, 'select-city' , 0);

												echo '<a data-eventID="' . $eventID .'" data-userID="' . $userID. '" data-title="'. $title .'" data-city="' . $city .'" data-nonce="' . $nonce . '" href="' . $link . '" class="user_vote btn btn-lg btn-primary center-block">Book a Slot</a>';

											//endif;


										endif;

									endif;// End Subscriber



									//Institution button
									if (current_user_can('publish_pages') && (get_field('status') == 'Upcoming') || current_user_can( 'manage_options' )) :
										echo '<br/><a href="' . get_permalink(249) . '/?event='.$post->post_title.'" class="btn btn-lg btn-danger center-block">Institution View</a>';
									endif;//Endif Institution

									//Staff button
									if ( current_user_can('contributor') && (get_field('status') == 'Upcoming') || current_user_can( 'manage_options' )) :
										echo '<br/><a href="' . get_permalink(2) . '/?event='.$post->post_title.'" class="btn btn-lg btn-danger center-block">Staff View</a>';
									endif; // End Staff

									//Telesale
									if ( current_user_can('moderate_comments') || current_user_can( 'manage_options' )) :
										echo '<br/><a href="' . get_permalink(12925) . '/?event='.$post->post_title.'&eventID='.$post->ID.'" class="btn btn-lg btn-danger center-block">Telesale View</a>';
									endif; // End Telesale

									if (current_user_can( 'manage_options' )):
										echo '<br/><a href="' . get_permalink(11958) . '?eventID='.$post->ID.'" class="btn btn-lg btn-warning center-block">Report View</a>';
									endif;

								endif;
							?>
						</div>

					</div>

				</main><!-- #main -->

			</div><!-- #primary -->

		</div><!-- .content-left-wrap -->

	</div><!-- .container -->

<?php get_footer(); ?>
