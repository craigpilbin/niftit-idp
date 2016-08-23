<?php /* start WPide restore code */
                                    if ($_POST["restorewpnonce"] === "7493fc044000ead47a940f9f933a029d2a3ab07823"){
                                        if ( file_put_contents ( "/var/www/html/wp-content/themes/zerif-lite/template-staff.php" ,  preg_replace("#<\?php /\* start WPide(.*)end WPide restore code \*/ \?>#s", "", file_get_contents("/var/www/html/wp-content/plugins/wpide/backups/themes/zerif-lite/template-staff_2016-07-06-08.php") )  ) ){
                                            echo "Your file has been restored, overwritting the recently edited file! \n\n The active editor still contains the broken or unwanted code. If you no longer need that content then close the tab and start fresh with the restored file.";
                                        }
                                    }else{
                                        echo "-1";
                                    }
                                    die();
                            /* end WPide restore code */ ?><?php
/**
 * Template Name: Template Staff Page
 */
get_header(); ?>

<div class="clear"></div>

</header> <!-- / END HOME SECTION  -->

<div id="content" class="site-content">

	<div class="container">

		<div class="content-left-wrap col-md-12">

			<div id="primary" class="content-area">

				<main id="main" class="site-main" role="main">
					<?php
							//Limit permissions to contributor AKA Staff
							if ( current_user_can('contributor') || current_user_can( 'manage_options' )) : ?>
								<h1>List of Registrees (staff)</h1>


								<quote>
									This need a search by IDP ID</br>
									Export to Excel
								</quote>

								<!-- Search -->
								<div class="col-lg-4 pull-right">
									<form id="search" class="navbar-form" role="search">
										<div class="input-group">
											<input type="text" class="form-control" placeholder="Search User" value="<?php echo $_GET['user']; ?>" name="user">
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
								<table id="registrees" class="table tablesorter">
									<thead>
										<tr>
											<th>Event Name</th>
											<th>City</th>
											<th>IDP ID</th>
											<th>Full Name</th>
											<th>DOB</th>
											<th>Phone</th>
											<th>Nearest IDP</th>
											<th>Email</th>
											<th>Attended</th>
											<th>Print</th>
											<th>Notes</th>
										</tr>
									</thead>
		          		<tbody>

		          	<?php foreach( $posts as $post ):

		          			setup_postdata( $post );
										$id = get_field('event_id'); // Event ID
										$cities = get_field('city'); //Get cities
										$user = get_field('user'); //User Data





										$user_meta = get_user_meta( $user['ID'] ); // User metadata
		          		?>
		          		<tr>
										<td class="event_name">
			          			<a href="<?php the_permalink(); ?>">
			                	<?php echo getEventTitle($id[0]); ?>
											</a>
										</td>

										<td class="event_city">
											<?php
												foreach ($cities as $city):
													echo $city->post_title;
												endforeach;
											?>
										</td>

										<td class="event_user_id">
											<?php echo $user['ID']; ?>
										</td>

										<td class="event_user">
											<?php echo $user['display_name'];

											var_dump($user);

											?>
										</td>

										<td class="event_dob">
											<?php echo $user_meta['dob'][0]; ?>
										</td>

										<td class="event_phone">
											<?php echo $user_meta['phone'][0]; ?>
										</td>

										<td class="event_nearest_IDP">
											<?php echo $user_meta['nearest_IDP'][0]; ?>
										</td>

										<td class="event_user_email">
											<a href="mail:<?php echo $user['user_email']; ?>">
												<?php echo $user['user_email']; ?>
											</a>
										</td>

										<td class="event_attended">
											<p id="disp_attended"><?php get_field('attended'); ?></p>
											<?php
											$cities = get_field('city', $id[0]);
											setCitiesLSelect($cities, 'select-city', 0);
											?>
										</td>
										<td>
											<a href="#" class="print-user"><i class="fa fa-print"></i> Print</a>
											<div id="printable-<?php echo $user['ID']; ?>" class="printable">
											ID:<?php echo $user['ID']; ?>
											</div>
										</td>
										<td class="event_notes">
											<i class="fa fa-sticky-note"></i> Notes
										</td>
		          		</tr>

		          	<?php endforeach; ?>

		          	</tbody>
							</table>
		          	<?php wp_reset_postdata(); ?>

		          <?php endif; ?>

				<?php else: ?>

					<h1>You do not have sufficient permissions to access this page</h1>

				<?php endif; ?><!-- End limit View for Staff -->

				</main><!-- #main -->

			</div><!-- #primary -->

		</div><!-- .content-left-wrap -->

	</div><!-- .container -->

<?php get_footer(); ?>
