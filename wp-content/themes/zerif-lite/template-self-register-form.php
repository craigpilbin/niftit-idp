<?php
/**
* Template Name: Self Register Form Page
*/
get_header(); ?>



<div class="clear"></div>

</header> <!-- / END HOME SECTION  -->

<div id="content" class="site-content">

 <div class="container">

   <div class="content-left-wrap col-md-12">

     <div id="primary" class="content-area">

       <main id="main" class="site-main" role="main">
               <h1>Self Registration Form: <?php echo $_GET['event']; ?></h1>

				<form id="self-register-form">
				  <div class="form-group">
					<label for="exampleInputText">Full Name*</label>
					<input type="text" class="form-control" id="self-fullname" required>
				  </div>
				  <div class="form-group">
					<label for="exampleInputText">Phone Number*</label>
					<input type="tel" class="form-control" id="self-phone" required>
				  </div>
				  <div class="form-group">
					<label for="exampleInputEmail1">Email address*</label>
					<input type="email" class="form-control" id="self-email" placeholder="Email" required>
				  </div>
				  <div class="form-group">
					<label for="exampleInputDOB">DOB*</label>
					<input type="text" class="form-control" id="self-DOB" placeholder="DD/MM/YYYY" required>
					<span>Please use the DD/MM/YYYY format</span>
				  </div>
				  <div class="form-group">
					<label for="select-nearestIDP">Nearest IDP*</label>
					<select id="select-nearestIDP" class="form-control">
						<option value="HCM District 1">HCM District 1</option>
						<option value="HCM District 5">HCM District 5</option>
						<option value="Can Tho">Can Tho</option>
						<option value="Da Nang">Da Nang</option>
						<option value="Ha Noi 53A Le Van Huu">Ha Noi 53A Le Van Huu</option>
						<option value="Ha Noi 15 - 17 Ngoc Khanh">Ha Noi 15 - 17 Ngoc Khanh</option>
					</select>
				  </div>
				  <?php
					//Gather the information to send
					$fullname = '';
					$phone ='';
					$email = '';
					$eventName = $_REQUEST["event"];
					$eventID = query_posts(array(
						'post_type' => 'offline',
						'showposts' => 1,
						'meta_query' => array(
								'key' => 'post_title', // name of custom field
								'value' => '"' . $eventName . '"', // matches exaclty "123", not just 123. This prevents a match for "1234"
								'compare' => 'LIKE'
						)
					));

					$nonce = wp_create_nonce("create_user_nonce");

					$link = admin_url('admin-ajax.php?action=create_user
										&eventName='.$eventName.'
										&eventID='.$eventID[0]->ID.'
										&fullname='.$fullname.'
										&phone='.$phone.'
										&dob='.$dob.'
										&nearestIDP='.$nearestIDP.'
										&email='.$email.'
										&nonce='.$nonce);

					echo '<a data-eventName="' . $eventName . '" data-eventID="'. $eventID[0]->ID .'" data-fullname="' . $fullname . '" data-phone="' . $phone . '" data-dob="' . $dob . '" data-nearestIDP="' . $nearestIDP . '" data-email="' . $email . '" data-nonce="' . $nonce . '" href="' . $link . '" id="self-create" class="btn btn-success"><i class="fa fa-plus"></i> Create User</a>';

				  ?>

				</form>

       </main><!-- #main -->

     </div><!-- #primary -->

   </div><!-- .content-left-wrap -->

 </div><!-- .container -->


<?php get_footer(); ?>
