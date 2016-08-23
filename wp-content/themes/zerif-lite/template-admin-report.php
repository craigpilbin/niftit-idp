<?php
/**
 * Template Name: Admin Report Template
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
					<h3>Total Attendeees: <?php echo getAttendeePerCity($_GET['eventID'], 0, false); ?></h3>

					<ul class="list list-inline">
						<li class="col-lg-3"><a href="#" id="download-attendees" event-data="<?php echo $_GET['eventID']; ?>" class="btn btn-lg btn-primary">Download Attendees</a></li>
						<li class="col-lg-3"><a href="#" id="download-reportb" event-data="<?php echo $_GET['eventID']; ?>" class="btn btn-lg btn-primary">Download Report B</a></li>
						<li class="col-lg-3"><a href="/mass-upload" id="mass-upload-link" class="btn btn-lg btn-info">Mass Upload</a></li>
					</ul>
					<!-- Report per City -->
					<h3>Report per cities</h3>
					<ul class="list list-inline list-unstyled">
						<li>
							<div class="card bg-success text-center">
								<h5>Hanoi</h5>
								<h4><?php echo getAttendeePerCity($_GET['eventID'], 175, true); ?></h4>
							</div>
						</li>
						<li>
							<div class="card bg-danger text-center">
								<h5>HCMC</h5>
								<h4><?php echo getAttendeePerCity($_GET['eventID'], 176, true); ?></h4>
							</div>
						</li>
						<li>
							<div class="card bg-warning text-center">
								<h5>Danang</h5>
								<h4><?php echo getAttendeePerCity($_GET['eventID'], 174, true); ?></h4>
							</div>
						</li>
						<li>
							<div class="card bg-info text-center">
								<h5>Can Tho</h5>
								<h4><?php echo getAttendeePerCity($_GET['eventID'], 173, true); ?></h4>
							</div>
						</li>
					</ul>

					<!-- Total -->
					<h3>Total Interviews: <?php echo getInterviewsTotal($_GET['eventID'], 0, false); ?></h3>


					<!-- Institutions -->
					<?php getInterviewsCountPerInstitutions($_GET['eventID']); ?>

					<div class="export-table hidden"></div>
				<?php endif; ?>
				</main><!-- #main -->

			</div><!-- #primary -->

		</div><!-- .content-left-wrap -->

	</div><!-- .container -->

	<script type="text/javascript">

		jQuery('.page-template-template-admin-report .export-table-btn').click(function(e){
			e.preventDefault();

			var self = jQuery(this);
			var instituionID = self.attr('inst-data');
			var eventID = self.attr('event-data');
			var html = '';

			jQuery.ajax( {
              url : myAjax.ajaxurl,
              type: 'POST',
              data: {
                  action  : 'export_table',
                  'eventID':eventID,
                  'instituionID':instituionID
              }
            } )
            .success( function( results ) {
              console.log( 'User Meta Updated!', results);

              exportTable(results);

            } )
            .fail( function( data ) {
                console.log( data.responseText );
                console.log( 'Request failed: ' + data.statusText );
            } );
		});

		jQuery('#download-attendees').click(function(e){

			e.preventDefault();

			var self = jQuery(this);
			var eventID = self.attr('event-data');
			var html = '';

			jQuery.ajax( {
              url : myAjax.ajaxurl,
              type: 'POST',
              data: {
                  action  : 'export_attendees',
                  'eventID':eventID              }
            } )
            .success( function( results ) {
              console.log( 'User Meta Updated!', results);

              exportTable(results);

            } )
            .fail( function( data ) {
                console.log( data.responseText );
                console.log( 'Request failed: ' + data.statusText );
            } );

		});

		jQuery('#download-reportb').click(function(e){

			e.preventDefault();

			var self = jQuery(this);
			var eventID = self.attr('event-data');
			var html = '';

			jQuery.ajax( {
              url : myAjax.ajaxurl,
              type: 'POST',
              data: {
                  action  : 'export_report_b',
                  'eventID':eventID              }
            } )
            .success( function( results ) {
              console.log( 'User Meta Updated!', results);

              exportTable(results);

            } )
            .fail( function( data ) {
                console.log( data.responseText );
                console.log( 'Request failed: ' + data.statusText );
            } );

		});

		function exportTable(html){

			jQuery('.export-table').empty().html(html);
			var table = jQuery('.export-table').find('table');

			table.tableExport({
          		fileName: "attendees"
        	});

			table.find('.btn-default.xls').click();

		}

	</script>

<?php get_footer(); ?>
