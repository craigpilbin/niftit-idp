/* This is used to book slot for offline event */

jQuery(document).ready( function() {

   jQuery(".user_vote").click( function(e) {
     //PreventDefault
     e.preventDefault();

      eventID = jQuery(this).attr("data-eventID");
      userID = jQuery(this).attr("data-userID");
      title = jQuery(this).attr("data-title");
      city = jQuery('#select-city option:selected').val();
      nonce = jQuery(this).attr("data-nonce");


      jQuery.ajax({
         type : "post",
         dataType : "json",
         url : myAjax.ajaxurl,
         data : {action: "book_offline_event", eventID : eventID, userID : userID, title : title, city : city, nonce: nonce},
         success: function(data) {
         }
      }).done(function(data) {
        location.reload();
      })

   })

})
