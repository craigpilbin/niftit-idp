<?php
/**
 * Template Name: Home
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

					$posts = get_field('online_events_group');

					if( $posts ): ?>
						<ul class="list list-unstyled">
						<?php foreach( $posts as $post): // variable must be called $post (IMPORTANT) ?>
							<?php setup_postdata($post); ?>
							<li class="col-md-3">
								<div class="panel panel-default <?php echo checkEvent(get_field('active_event')); ?>">
										<div class="panel-heading">
											<h3 class="panel-title"><?php the_title(); ?></h3>
										</div>
										<div class="panel-body">
											<?php setDetailsEvent(get_field('active_event')); ?>
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
