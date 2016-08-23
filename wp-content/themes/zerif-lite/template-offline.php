<?php
/**
 * Template Name: Offline Events List Page
 */

$we_have_post = false;

if(isset($_POST['destination']) && $_POST['destination'] != 'All'){
	$destination = $_POST['destination'];
	$we_have_post = true;
}
if(isset($_POST['major']) && $_POST['major'] != 'All'){
	$major = $_POST['major'];
	$we_have_post = true;
}
if(isset($_POST['education_level']) && $_POST['education_level'] != 'All'){
	$education_level = $_POST['education_level'];
	$we_have_post = true;
}

get_header(); ?>

<div class="clear"></div>

</header> <!-- / END HOME SECTION  -->

<div id="content" class="site-content">

	<div class="container">

		<div class="content-left-wrap col-md-12">

			<div id="primary" class="content-area">

				<main id="main" class="site-main" role="main">
					<div class="row select-section">
						<form action="/offline/" method="post">
							<div class="col-lg-2">
								<div class="casing">
								<em class="placeholder">Destinations</em>
									<?php
										$field = get_field_object(get_acf_key('destination'));
										$values = $field['choices'];
										//Set values for Destionation
										setHTMLSelect($values, 'select-active-event ',1, 'destination');
									 ?>
							 	</div>
							</div>
							<div class="col-lg-2">
								<div class="casing">
								<em class="placeholder">Majors</em>
								<?php
									$field = get_field_object(get_acf_key('major'));
									$values = $field['choices'];
									//Set the values for Majors
									setHTMLSelect($values, 'select-active-event',1, 'major');
								 ?>
							 </div>
							</div>
							<div class="col-lg-2">
								<div class="casing">
								<em class="placeholder">Education Level</em>
								<?php
									$field = get_field_object(get_acf_key('education_level'));
									$values = $field['choices'];
									//Set the values for Education Level
									setHTMLSelect($values, 'select-active-event',1, 'education_level');
								 ?>
							 	</div>
							</div>
							<div class="col-lg-2">
								<a class="btn btn-success">
									<input type="submit" value="Submit">
								</a>
							</div>
						</form>
						<div class="col-lg-4">
							<form id="search" class="navbar-form" role="search">
								<div class="input-group">
									<input type="text" class="form-control" placeholder="Search Offline Event" value="<?php echo $_GET['event']; ?>" name="event">
									<div class="input-group-btn">
										<button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
									</div>
								</div>
							</form>
						</div>
					</div>
					<?php

					$posts = get_posts(array(
						'numberposts'	=> -1,
						'post_type'		=> 'offline',
						'exclude' => array(121,124,125)
					));

					if( $posts ): ?>
    						<ul class="list list-unstyled">
    						<?php foreach( $posts as $post): // variable must be called $post (IMPORTANT) ?>
    							<?php setup_postdata($post); ?>

    							<?php 
    								$dest = get_field('destination', $post->ID);
    								$maj = get_field('major', $post->ID);
    								$edu = get_field('education_level', $post->ID);

    								$dest_match = false;
    								$maj_match = false;
    								$edu_match = false;

    								if($destination && isInArray($dest, $destination)){
    									$dest_match = true;
    								} elseif($destination && !isInArray($dest, $destination)){
    									$dest_match = false;
    								} elseif(!$destination){
    									$dest_match = true;
    								}

    								if($major && isInArray($maj, $major)){
    									$maj_match = true;
    								} elseif($major && !isInArray($maj, $major)){
    									$maj_match = false;
    								} elseif(!$major){
    									$maj_match = true;
    								}

    								if($education_level && isInArray($edu, $education_level)){
    									$edu_match = true;
    								} elseif($education_level && !isInArray($edu, $education_level)){
    									$edu_match = false;
    								} elseif(!$education_level){
    									$edu_match = true;
    								}

    							if(!$we_have_post || $dest_match && $maj_match && $edu_match) : ?>

	    							<li class="col-md-4">
    									<div class="panel panel-default grid-holder <?php echo checkEvent(get_field('status')); ?>">
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
    
    											<a href="<?php the_permalink(); ?>" class="btn btn-default center-block">Click to see more</a>
    										</div>
    									</div>
	    							</li>
    							<?php endif; ?>
    						<?php endforeach; ?>
    						</ul>
    						<?php wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly ?>
					<?php endif; ?>



				</main><!-- #main -->

			</div><!-- #primary -->

		</div><!-- .content-left-wrap -->

	</div><!-- .container -->

	<style type="text/css">
		.page-template-template-offline .btn-success input {
			background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
		    margin: 0;
		    padding: 0;
		}
	</style>

<?php get_footer(); ?>
