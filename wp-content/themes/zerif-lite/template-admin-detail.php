<?php
/**
 * Template Name: Admin Report Detail Template
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
						if(current_user_can( 'manage_options' )) :
					 ?>

          <h1>Report for event ID <?php echo $_GET['eventID']; ?></h1>

					<!-- Total -->
					<h3>InstitutionID: <?php echo $_GET['InstitutionID']; ?></h3>

					<!-- Institutions -->
					<?php getInterviewsPerInstitutions($_GET['eventID'], $_GET['InstitutionID']); ?>
				<?php endif; ?>

				</main><!-- #main -->

			</div><!-- #primary -->

		</div><!-- .content-left-wrap -->

	</div><!-- .container -->

<?php get_footer(); ?>
