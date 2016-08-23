/* This is used to create and register a user */

jQuery(document).ready( function() {

   jQuery(".btn-set-attended").click( function(e) {
     //PreventDefault
     e.preventDefault();
		  
	  // Field for creatin the user
      attended = jQuery(this).parent().prev().find('select').val();
      attended_at = new Date();
      registree = jQuery(this).attr("data-registree");
      nonce = jQuery(this).attr("data-nonce");
	  // Field for registering the user

      jQuery.ajax({
         type : "post",
         dataType : "json",
         url : myAjax.ajaxurl,
         data : {action: "set_attended", attended : attended, attended_at : attended_at, registree : registree, nonce: nonce},
         success: function(data) {
         }
      }).done(function(data) {
        location.reload();
      })

   })

})
