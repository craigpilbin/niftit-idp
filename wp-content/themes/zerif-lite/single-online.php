<?php
/**
 * Template Name: Institutions List Page
 */
get_header(); ?>

<div class="clear"></div>

</header> <!-- / END HOME SECTION  -->

<div id="content" class="site-content">

	<div class="container">

		<div class="content-left-wrap col-md-12">

			<div id="primary" class="content-area">

				<main id="main" class="site-main" role="main">
					<?php $title = get_the_title() ?>
					<h2><?php echo $title; ?></h2>
					<a href="/online-events">Return to all online events</a>
					<?php 
						$current_user = wp_get_current_user();
						$active_event = get_field('active_event');
						
						if (user_can( $current_user, 'administrator' ) || ($active_event == 'Active')) {
						  // user is an admin
						  if (function_exists('wise_chat')) { wise_chat($title); }
						} else {
							echo 'The session is closed or restricted to administrators';
						}
					?>		
				</main><!-- #main -->

			</div><!-- #primary -->

		</div><!-- .content-left-wrap -->

	</div><!-- .container -->

<?php get_footer(); ?>
