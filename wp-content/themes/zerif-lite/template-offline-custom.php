<?php
/**
 * Template Name: Offline Events List Page
 */
get_header(); ?>

<div class="clear"></div>

</header> <!-- / END HOME SECTION  -->

<div id="content" class="site-content">

	<div class="container">

		<div class="content-left-wrap col-md-12">

			<div id="primary" class="content-area">

				<main id="main" class="site-main" role="main">
					<div class="row select-section">
						<div class="col-lg-2">
							<div class="casing">
								<em class="placeholder">Destinations</em>
									<?php
										$field = get_field_object(get_acf_key('destination'));
										$values = $field['choices'];
										setHTMLSelect($values, 'select-active-event',1);
									 ?>
							 </div>
						</div><div class="col-lg-2">
							<div class="casing">
								<em class="placeholder">Majors</em>
								<?php
									$field = get_field_object(get_acf_key('major'));
									$values = $field['choices'];
									setHTMLSelect($values, 'select-active-event',1);
								 ?>
							 </div>
						</div><div class="col-lg-2">
							<div class="casing">
								<em class="placeholder">Education Level</em>
								<?php
									$field = get_field_object(get_acf_key('education_level'));
									$values = $field['choices'];
									setHTMLSelect($values, 'select-active-event',1);
								 ?>
							 </div>
						</div><div class="col-lg-2">
							<a class="btn btn-success">Submit</a>
						</div>
						<div class="col-lg-4">
							<form id="search" class="navbar-form" role="search">
								<div class="input-group">
									<input type="text" class="form-control" placeholder="Search Offline Event" value="<?php echo $_GET['q']; ?>" name="q">
									<div class="input-group-btn">
										<button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
									</div>
								</div>
							</form>
						</div>
					</div>
					<?php

					// Get offline event based off the slug
					$posts = get_posts(array(
						'numberposts'	=> -1,
						'post_type'		=> 'offline',
						'exclude' => array(121,124,125),
						'meta_query' => array(
							array(
								'key' => 'status', // name of custom field
								'value' => the_slug(), // matches exaclty "123", not just 123. This prevents a match for "1234"
								'compare' => 'LIKE'
							)
						)
					));

					if( $posts ): ?>
						<ul class="list list-unstyled">
						<?php foreach( $posts as $post): // variable must be called $post (IMPORTANT) ?>
							<?php setup_postdata($post); ?>
							<li class="col-md-4">
									<div class="panel panel-default <?php echo checkEvent(get_field('status')); ?>">
										<div class="panel-heading">
											<h3 class="panel-title"><?php the_title(); ?></h3>
										</div>
										<div class="panel-body">
											<?php
												$picture = get_field('picture');

												if ($picture):
													echo '<img class="img img-reponsive" src="' . $picture["sizes"]["medium"] . '" alt="event"/>';
												endif;
											?>
											<label>Start Date:</label> <?php echo gmdate("D F Y, H:i", get_field('start_date')); ?><br/>
											<label>End Date:</label> <?php echo gmdate("D F Y, H:i", get_field('end_date')); ?><br/>
											<label>Location:</label><br/>
											<div><?php the_field('location'); ?></div>
											<a href="<?php the_permalink(); ?>" class="btn btn-default center-block">Click to see more</a>
										</div>
									</div>
							</li>
						<?php endforeach; ?>
						</ul>
						<?php wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly ?>
					<?php endif; ?>



				</main><!-- #main -->

			</div><!-- #primary -->

		</div><!-- .content-left-wrap -->

	</div><!-- .container -->

<?php get_footer(); ?>
