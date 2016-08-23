<?php /* start WPide restore code */
                                    if ($_POST["restorewpnonce"] === "7493fc044000ead47a940f9f933a029d2a3ab07823"){
                                        if ( file_put_contents ( "/var/www/html/wp-content/themes/zerif-lite/template-home.php" ,  preg_replace("#<\?php /\* start WPide(.*)end WPide restore code \*/ \?>#s", "", file_get_contents("/var/www/html/wp-content/plugins/wpide/backups/themes/zerif-lite/template-home_2016-07-06-11.php") )  ) ){
                                            echo "Your file has been restored, overwritting the recently edited file! \n\n The active editor still contains the broken or unwanted code. If you no longer need that content then close the tab and start fresh with the restored file.";
                                        }
                                    }else{
                                        echo "-1";
                                    }
                                    die();
                            /* end WPide restore code */ ?><?php
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
