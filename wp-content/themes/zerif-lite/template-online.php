<?php
/**
 * Template Name: Online Events List Page
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
						<div class="col-lg-6">
							<!-- Search -->
							<form class="navbar-form navbar-left" role="search">
							    <div class="form-group">
							        <input type="text" class="form-control" placeholder="Search">
							    </div>
							    <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
							</form>
						</div>
						<div class="col-lg-2 pull-right">
							<!-- Filter -->
							<?php
								$field = get_field_object(get_acf_key('active_event'));
								$values = $field['choices'];
								setHTMLSelect($values, 'select-active-event', 1);
							 ?>
						</div>
					</div>
					<div class="row">
						<?php
						//Get all online post
						$posts = get_posts(array(
							'numberposts'	=> -1,
							'post_type'		=> 'online'
						));

						if( $posts ): ?>
							<ul class="list list-unstyled">
							<?php foreach( $posts as $post): // variable must be called $post (IMPORTANT) ?>
								<?php setup_postdata($post); ?>
								<!-- online template -->
								<li class="col-md-3">
									<div class="panel panel-default <?php echo checkEvent(get_field('status')); ?>">
											<div class="panel-heading">
												<h3 class="panel-title"><?php the_title(); ?></h3>
											</div>
											<div class="panel-body">
												<?php setDetailsEvent(get_field('status')); ?>
											</div>
										</div>
								</li>
							<?php endforeach; ?>
							</ul>

							<?php wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly ?>
						<?php endif; ?>
					</div>

					<script type="text/javascript">
					//@TODO: replace this to a proper function enqueue
					jQuery(function($) {
						// Code that uses jQuery's $ can follow here.

						var setFilterURL = function(id, field) {
							$(id).on('change', function() {
								var url = '<?php home_url(add_query_arg(array(),$wp->request)); ?>';
								var selected = $(this).find('option:selected').text();

								if(selected !='All') {
									url += '?' + field + '=' + selected;
								}

								window.location.replace( url );
							})
						}

						setFilterURL('#select-active-event','active_event');
					});

					</script>


				</main><!-- #main -->

			</div><!-- #primary -->

		</div><!-- .content-left-wrap -->

	</div><!-- .container -->

<?php get_footer(); ?>
