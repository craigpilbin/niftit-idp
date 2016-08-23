/* This is used to book slot for offline event */

jQuery(document).ready( function() {

   jQuery("#self-create").click( function(e) {

     	//PreventDefault
     	e.preventDefault();
	  	eventName = jQuery(this).attr('data-eventName');
	  	eventID = jQuery(this).attr('data-eventID');

	  	fullname = jQuery('#self-fullname').val();
	  	email = jQuery('#self-email').val();
	  	phone = jQuery('#self-phone').val();
	  	dob = jQuery('#self-DOB').val();
	  	nearestIDP = jQuery('#select-nearestIDP').val();
		
		
      	nonce = jQuery(this).attr("data-nonce");

	  	if(!validateEmail(email)){
	  		jQuery('#self-register-form').find('#self-email').css({ 'border': '1px solid red' });
	  	} else {
	  		jQuery('#self-register-form').find('#self-email').css({ 'border': '1px solid #ccc' });
	  	}

	  	if(!validateTel(phone)){
	  		jQuery('#self-register-form').find('#self-phone').css({ 'border': '1px solid red' });
	  	} else {
	  		jQuery('#self-register-form').find('#self-phone').css({ 'border': '1px solid #ccc' });
	  	}
		
		if(!validateDOB(dob)){
	  		jQuery('#self-register-form').find('#self-DOB').css({ 'border': '1px solid red' });
	  	} else {
	  		jQuery('#self-register-form').find('#self-DOB').css({ 'border': '1px solid #ccc' });
	  	}
		

	  	if(validateEmail(email) && validateTel(phone) && validateDOB(dob)){
	      	jQuery.ajax({
	         	type : "post",
	         	dataType : "json",
	         	url : myAjax.ajaxurl,
	         	data : {action: "create_user", eventName : eventName, eventID : eventID, fullname : fullname, email : email, phone : phone, dob : dob, nearestIDP : nearestIDP, nonce: nonce},
	         	success: function(data) {

	         	},
			 	error: function (error) {
				 	console.log(error);
			 	}
	      	}).always(function(data) {
				window.location.href = '/self-register/?event=' + eventName + '&user=' + email;
	      	})
	    }

    function validateEmail(email) {
  			var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  			return re.test(email);
		}

	function validateTel(tel){
		var phoneNum = tel.replace(/[^\d]/g, '');
		return phoneNum.length > 6 && phoneNum.length < 12 ? true : false;
	}
	
	function validateDOB(dob) {
		var reg = /^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/g;
		if(dob.match(reg)) {
			return true;
		} else {
			return false;
		}
	}

  })

})
