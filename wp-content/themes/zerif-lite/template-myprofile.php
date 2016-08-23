<?php
/**
 * Template Name: My Profile
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
						<div class="col-lg-8 profile-left">
							<?php the_content(); ?>

							<?php global $current_user;
							      wp_get_current_user();

										echo get_avatar( $current_user->user_email, 100 );
							      echo '<p>Username:</p> ' . $current_user->user_login . "<br/>";
							      echo '<p>User email:</p> ' . $current_user->user_email . "<br/>";
							      echo '<p>User level:</p> ' . $current_user->user_level . "<br/>";
							      echo '<p>User first name:</p> ' . $current_user->user_firstname . "<br/>";
							      echo '<p>User last name:</p> ' . $current_user->user_lastname . "<br/>";
							      echo '<p>User display name:</p> ' . $current_user->display_name . "<br/>";
							      echo '<p>User ID:</p> IDP ' . $current_user->ID . "\n";
							?>
						</div>
						<div class="col-lg-4 profile-right">
							<h3>My Registrations</h3>
							<?php
							$posts = get_posts(array(
								'numberposts'	=> -1,
								'post_type'		=> 'registrees',
								'meta_query' => array(
									array(
										'key' => 'user', // name of custom field
										'value' => get_current_user_id(), // matches exaclty "123", not just 123. This prevents a match for "1234"
										'compare' => '='
									)
								)
							));

							if($posts):
								echo '<ul class="list">';
									foreach( $posts as $post):
										echo '<a href="#">'.$post->post_title.'</a>';
										echo '<p>register on '.$post->post_modified.'</p>';
									endforeach;
								echo '</ul>';
							endif;
							?>
						</div>
					</div>
				</main><!-- #main -->

			</div><!-- #primary -->

		</div><!-- .content-left-wrap -->

	</div><!-- .container -->

<?php get_footer(); ?>
