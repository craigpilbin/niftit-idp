<?php
/**
 * Template Name: Admin Mass Upload Template
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

          <h1>Mass Upload Tool</h1>

          <form class="form-inline">
              <div>You can only do 2000 records at a time. the time limit for the script is 5 min</div>
              <a href="/wp-content/uploads/2016/08/User-File-Import-Example.xlsx">link to a sample CSV</a>
              <br/>
              <div class="form-group">
                <label for="csvFile">Location of the file</label>
                <select id="csvFile" autocomplete="off">
                  <option value="0" selected="selected">Choose a file</option>

                </select>
                <!-- <input type="text" class="form-control" id="csvLocation" placeholder="NameofFile.csv"> -->
              </div>
              <div class="form-group">
                <label for="">Associated Event</label>
                
                <?php $events = returnAllEvents(); ?>
                <select id="csvEvent" autocomplete="off">
                  
                  <option value="0" selected="selected">Choose an event</option>

                  <!-- LIST ALL EVENTS -->
                  <?php foreach($events as $e) : ?>
                    <option value="<?php echo $e->ID; ?>"><?php echo $e->post_title; ?></option>
                  <?php endforeach; ?>
                  
                </select>
              </div>
              <div class="submit-file">
                <a href="" id="upload-xls" class="btn btn-primary">Import Users</a>
              </div>
          </form>

          <div>
            Status of the mass Upload<br/> list of the errors or emails that are duplicated
          </div>

          <div class="return-results">
            <table>
              <tbody>

              </tbody>
            </table>
          </div>

        <?php endif; ?>
        </main><!-- #main -->

      </div><!-- #primary -->

    </div><!-- .content-left-wrap -->

  </div><!-- .container -->

<?php get_footer(); ?>
