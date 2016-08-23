jQuery(document).ready( function() {

  jQuery('.save-notes').click(function(){
       var userId = jQuery(this).attr('user-data');
      // var aId = row.attr('reg-data');
      //
      // var attended = row.find('.user-attended input').val();
      //
      // var dob = row.find('.user-dob .update-field').val();
      //
      // var district = row.find('.user-district .update-field').val();
      // var english_level = row.find('.user-englishlevel .update-field').val();
      //   var current_major = row.find('.user-currentmajor .update-field').val();
      //   var current_university = row.find('.user-currentschool .update-field').val();
      //   var interest_level_study = row.find('.user-interestedlevel .update-field').val();
      //   var interested_major = row.find('.user-interestedmajor .update-field').val();
      //   var intake_year = row.find('.user-intakeyear .update-field').val();
      //   var intake_month = row.find('.user-intakemonth .update-field').val();
         var telesale_note = tinyMCE.activeEditor.getContent().replace(/"/g, '&quot;');//row.find('.user-note .update-notes-field').val();
      //
      //   //Extra
      //   var counsellor = row.find('.user-counsellor .update-field').val();
      //   var oscar_id = row.find('.user-oscar .update-field').val();
      //   var gpa = row.find('.user-gpa .update-field').val();
      //   var destination = row.find('.user-destination .update-field').val();
      //   var status = row.find('.user-status .update-field').val();
      //   //var lead = row.find('input[name=lead]:checked', '.user-lead .update-field').val();
      //
      //   var leadSelect = row.find('.user-lead .update-field select').val();
      //
      //  row.find('.user-note .update-notes-field').val(telesale_note);
      //
      //  //console.log(row.find('.user-note .update-notes-field').val());
      //  var d = new Date(attended);
      //
      //  var newDate = moment.tz(d, "Asia/Ho_Chi_Minh").format('ddd MMM Do YYYY HH:mm:ss ZZ');
      //  newDate += 'GMT (SE Asia Standard Time)';



      jQuery.ajax( {
            url : myAjax.ajaxurl,
            type: 'POST',
            data: {
                action  : 'update_telesales', //'u_cb',
                'id':userId,
                // 'district':district,
                // 'english_level':english_level,
                // 'current_major':current_major,
                // 'current_university':current_university,
                // 'interest_level_study':interest_level_study,
                // 'interested_major':interested_major,
                // 'intake_year':intake_year,
                // 'intake_month':intake_month,
                'telesale_note': telesale_note

                // 'counsellor': counsellor,
                // 'oscar_id':oscar_id,
                // 'gpa': gpa,
                // 'destination':destination,
                // 'status':status,
                // 'lead':leadSelect,
                // 'attended':newDate,
                // 'aId':aId
            }
        } )
        .success( function( results ) {
            console.log(results);
            location.reload();
        } )
        .fail( function( data ) {
            console.log( data.responseText );
            console.log( 'Request failed: ' + data.statusText );
        } );
  });

});
