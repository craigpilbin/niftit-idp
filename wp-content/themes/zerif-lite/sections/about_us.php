<section class="about-us" id="aboutus">
	<div class="container">

		<!-- SECTION HEADER -->

		<div class="section-header">

			<!-- SECTION TITLE -->

			<?php

			global $wp_customize;

			$zerif_aboutus_title = get_theme_mod('zerif_aboutus_title',__('About','zerif-lite'));


			?>

		</div><!-- / END SECTION HEADER -->

		<!-- 3 COLUMNS OF ABOUT US-->

		<div class="row">

			<!-- COLUMN 1 - BIG MESSAGE ABOUT THE COMPANY-->

		<?php

			$zerif_aboutus_biglefttitle 	= get_theme_mod('zerif_aboutus_biglefttitle',__('Everything you see here is responsive and mobile-friendly.','zerif-lite'));
			$zerif_aboutus_text 			= get_theme_mod('zerif_aboutus_text','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla nec massa enim. Aliquam viverra at est ullamcorper sollicitudin. Proin a leo sit amet nunc malesuada imperdiet pharetra ut eros.<br><br> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla nec massa enim. Aliquam viverra at est ullamcorper sollicitudin. Proin a leo sit amet nunc malesuada imperdiet pharetra ut eros. <br><br>Mauris vel nunc at ipsum fermentum pellentesque quis ut massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Maecenas non adipiscing massa. Sed ut fringilla sapien. Cras sollicitudin, lectus sed tincidunt cursus, magna lectus vehicula augue, a lobortis dui orci et est.');

				if( !empty($zerif_aboutus_biglefttitle) ):

					echo '<div class="col-lg-8 col-md-8 column">';

						echo '<div data-scrollreveal="enter left after 0s over 1s">';

								//Add Left Title
								$zerif_aboutus_subtitle = get_theme_mod('zerif_aboutus_subtitle',__('Use this section to showcase important details about your business.','zerif-lite'));

								if( !empty($zerif_aboutus_subtitle) ):

									echo '<h2 class="white-text">';

										echo wp_kses_post( $zerif_aboutus_subtitle );

									echo '</h2>';

								elseif ( isset( $wp_customize ) ):

									echo '<div class="white-text section-legend zerif_hidden_if_not_customizer">'.wp_kses_post( $zerif_aboutus_subtitle ).'</div>';

								endif;

							echo wp_kses_post( $zerif_aboutus_biglefttitle );

						echo '</div>';

					echo '</div>';

				endif;

			if( !empty($zerif_aboutus_text) ):

				echo '<div class="col-lg-4 col-md-4 column zerif_about_us_center ' . isset($text_and_skills) . '" data-scrollreveal="enter bottom after 0s over 1s">';

					//Add Title Right
					if( !empty($zerif_aboutus_title) ):
						echo '<h2 class="white-text">'. wp_kses_post( $zerif_aboutus_title ) .'</h2>';
					elseif ( isset( $wp_customize ) ):
						echo '<h2 class="white-text zerif_hidden_if_not_customizer"></h2>';
					endif;

					//Add Content Right
					echo '<p>';

						echo wp_kses_post( $zerif_aboutus_text );

					echo '</p>';

				echo '</div>';

			endif;

			$there_is_skills = '';
			(
				!empty($zerif_aboutus_feature1_title) || !empty($zerif_aboutus_feature1_text) ? $there_is_skills='yes' :
				!empty($zerif_aboutus_feature2_title) || !empty($zerif_aboutus_feature2_text) ? $there_is_skills='yes' :
				!empty($zerif_aboutus_feature3_title) || !empty($zerif_aboutus_feature3_text) ? $there_is_skills='yes' :
				!empty($zerif_aboutus_feature4_title) || !empty($zerif_aboutus_feature4_text) ? $there_is_skills='yes' :
				$there_is_skills='');

			$zerif_aboutus_feature1_nr 	= get_theme_mod('zerif_aboutus_feature1_nr', '80');
			$zerif_aboutus_feature2_nr 	= get_theme_mod('zerif_aboutus_feature2_nr', '91');
			$zerif_aboutus_feature3_nr 	= get_theme_mod('zerif_aboutus_feature3_nr', '88');
			$zerif_aboutus_feature4_nr 	= get_theme_mod('zerif_aboutus_feature4_nr', '95');

			/* COLUMN 1 - SKILS */

			if ( $there_is_skills!='' ) :

		?>

		<?php endif; ?>

	</div> <!-- / END 3 COLUMNS OF ABOUT US-->

	<!-- CLIENTS -->
	<?php
		if(is_active_sidebar( 'sidebar-aboutus' )):
			echo '<div class="our-clients">';
				echo '<h2><span class="section-footer-title">'.__('OUR HAPPY CLIENTS','zerif-lite').'</span></h2>';
			echo '</div>';

			echo '<div class="client-list">';
				echo '<div data-scrollreveal="enter right move 60px after 0.00s over 2.5s">';
				dynamic_sidebar( 'sidebar-aboutus' );
				echo '</div>';
			echo '</div> ';
		endif;
	?>

</div> <!-- / END CONTAINER -->

</section> <!-- END ABOUT US SECTION -->
