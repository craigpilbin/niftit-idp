<?php
/**
 * Template Name: Single Institution Template Page
 */
get_header(); ?>

<div class="clear"></div>

</header> <!-- / END HOME SECTION  -->

<div id="content" class="site-content">

	<div class="container">

		<div class="content-left-wrap col-md-12">

			<div id="primary" class="content-area">

				<main id="main" class="site-main" role="main">
					<div class="row">
						<a href="<?php get_post_permalink(8); ?>">See all institutions</a>
					</div>
					<div class="row row-table">
						<div class="col-lg-8">
							<h3><?php the_title(); ?></h3>
							<div>
								<img class="img img-responsive img-padding" src="<?php the_field('institution_picture'); ?>" align="left" />
								<?php the_field('institution_description'); ?>
							</div>
						</div>
						<div class="col-lg-4 bg-mute">
							<h3>Upcoming Events</h3>

							<?php
								$id = get_the_id();

								$posts = get_posts(array(
									'numberposts'	=> -1,
									'post_type'		=> 'offline',
									'exclude' => array(121,124,125),
									'order_by' => 'post_title',
									'order' => 'ASC',
									'meta_query' => array(
											array(
												'key' => 'participating_institutions',
												'value' => $id,
												'compare' => 'LIKE'
											)
										)
								));

								echo '<ul class="list">';
								foreach( $posts as $post):
									echo '<a href="'. $post-> guid .'">';
									echo '<li>' . $post->post_title . '</li>';
									echo '</a>';
								endforeach;
								echo '</ul>';

							 ?>

							<br/><br/>

						</div>

					</div>
				</main><!-- #main -->

			</div><!-- #primary -->

		</div><!-- .content-left-wrap -->

	</div><!-- .container -->

<?php get_footer(); ?>
