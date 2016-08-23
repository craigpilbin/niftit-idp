<?php
/**
* Template Name: Template Staff Page
*/
get_header(); ?>

<div class="clear"></div>

</header> <!-- / END HOME SECTION  -->

<div id="content" class="site-content">

  <?php
    //Only display the page if the current status is upcoming

    $event = get_posts(array(
      'posts_per_page'	=> 1,
      'post_type'			=> 'offline',
      's' => $_GET['event']
      )
    );
    $status = get_post_meta($event[0]->ID)['status'][0];


    if ($status == 'Upcoming') :?>

 <div class="container container-admin">

   <div class="content-left-wrap col-md-12">

     <div id="primary" class="content-area">

       <main id="main" class="site-main" role="main">
         <?php
             //Limit permissions to contributor AKA Staff
             if ( current_user_can('contributor') || current_user_can( 'manage_options' )) : ?>
               <h1>All Registress: <?php echo $_GET['event']; ?></h1>

               <!-- Search -->
               <div class="col-lg-4 text-right pull-right">
                 <form id="search" class="navbar-form" role="search">
                   <div class="input-group">
                       <input type="text" class="form-control hidden" placeholder="" value="<?php echo $_GET['event']; ?>" name="event">
                     <input type="text" class="form-control" placeholder="Search User" value="<?php if(isset($_GET['user'])) echo $_GET['user']; ?>" name="user">
                     <div class="input-group-btn">
                       <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
                     </div>
                   </div>
                 </form>
               </div>
			   <div class="clearfix"></div>
			   <!-- Add user -->
				<div class="col-lg-4 text-right pull-right">
					<a href="<?php echo get_permalink(253); ?>/?event=<?php echo $_GET['event']; ?>" class="btn btn-success btn-padding"><i class="fa fa-plus"></i> Register as a new user</a>
			   </div>

             <!-- Table -->
             <?php
               $posts = get_posts(array(
                 'posts_per_page'	=> -1,
                 'post_type'			=> 'registrees'
               ));

			   if( $posts ): ?>
               <table id="registrees" class="table tablesorter">
                 <thead>
                   <tr>
                     <th>Event Name</th>
                     <th>City</th>
                     <th>IDP ID</th>
                     <th>Full Name</th>
                     <th>DOB</th>
                     <th>Phone</th>
                     <th>Nearest IDP</th>
                     <th>Email</th>
                     <th>Attended</th>
                     <th>Set Attendance</th>
                     <th>Print</th>
                   </tr>
                 </thead>
                 <tbody>
                     <?php foreach( $posts as $post ):
                         setup_postdata( $post );
                         $id = get_field('event_id'); // Event ID
                         $cities = get_field('city'); //Get cities
                         $user = get_field('user'); //User Data
                         $user_meta = get_user_meta( $user['ID'] ); // User metadata

                         // IF CONTAINS STRING $_GET['user']
                         $contains = true;
                         if(isset($_GET['user']) && $_GET['user'] != '') {

                             $contains = false;

                             if (strpos(strtolower($user['display_name']), strtolower($_GET['user'])) !== false) {
                                               $contains = true;
                                           } elseif (strpos(strtolower($user_meta['phone'][0]), strtolower($_GET['user'])) !== false) {
                                               $contains = true;
                                           } elseif (strpos(strtolower($user['user_email']), strtolower($_GET['user'])) !== false) {
                                               $contains = true;
                                           } else {
                                               $contains = false;
                                           }
                         }
                         if($contains) :?>
                         <tr>
                           <td><a href="<?php the_permalink(); ?>" class="tableexport-ignore"><?php echo getEventTitle($id[0]); ?></a></td>
                           <td><?php foreach ($cities as $city):echo $city->post_title;endforeach;?></td>
                           <td><?php echo $user['ID']; ?></td>
                           <td><?php echo $user['display_name']; ?></td>
                           <td><?php echo esc_html($user_meta['dob'][0]); ?></td>
                           <td><?php echo $user_meta['phone'][0]; ?></td>
                           <td><?php echo $user_meta['nearest_IDP'][0]; ?></td>
                           <td><a href="mail:<?php echo $user['user_email']; ?>"><?php echo $user['user_email']; ?></a></td>
                           <td><?php echo get_field('attended')[0]->post_title; ?><br/><?php echo (get_field('attended_at'))?get_field('attended_at'):''; ?></td>
                           <td>

                                <form class="form">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                               <?php
                                  // Set the city attended
                                 $cities = get_field('city', $id[0]);
                                 $attended = 'a:1:{i:0;s:3:"176";}';
                                 $nonce = wp_create_nonce("set_attended_nonce");

                                 $link = admin_url('admin-ajax.php?action=set_attended&attended='.$attended.'&registree='.$post->ID.'&nonce='.$nonce);

                                 //Build the HTML
                                 setCitiesLSelect($cities, 'select-city', 0);


                               ?>
							   </div>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<a data-attended="<?php echo $attended; ?>" data-registree="<?php echo $post->ID; ?>" data-nonce="<?php echo $nonce; ?>" href="<?php echo $link; ?>" class="btn btn-success btn-set-attended">Set</a>
								</div>
							  </form>
                           </td>
                           <td>
                             <a href="#" class="print-user"><i class="fa fa-print"></i> Print</a>
                                 <div id="printable-<?php echo $user['ID']; ?>" class="printable" style="padding:20px; margin:0;">
                                       <small style="font-size:14pt; margin:0; padding:0;"><?php echo $user['display_name']; ?></small>
                                       <h1 style="font-size:34pt; margin:0; padding:0;">ID:<?php echo $user['ID']; ?></h1>
                                 </div>
                           </td>
                         </tr>
               <?php endif; ?><!-- contains -->

               <?php endforeach; ?>

               </tbody>
             </table>
               <?php wp_reset_postdata(); ?>

             <?php endif; ?>

       <?php else: ?>

         <h1>You do not have sufficient permissions to access this page</h1>

       <?php endif; ?><!-- End limit View for Staff -->

       </main><!-- #main -->

     </div><!-- #primary -->

   </div><!-- .content-left-wrap -->

 <?php else: ?>
   <br/>
   <h1 class="text-center">This event is now past, you cannot access it. Please contact your administrator.</h1>
   <br/>
 <?php endif;?>

 </div><!-- .container -->

<?php get_footer(); ?>
