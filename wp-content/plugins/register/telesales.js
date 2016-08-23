jQuery(document).ready(function(){

	jQuery('td.edit-controls .edit').click(function(){

	    var editButton = jQuery(this);
	    var row = editButton.closest('tr');

	    var userId = row.attr('user-data');
	    var aId = row.attr('reg-data');


	    if(row.find('.update-field').is(':visible')){
	      	editButton.find('.done-txt').hide();
	      	editButton.find('.edit-txt').show();

	      	viewMode(row);
	    } else {
	      	editButton.find('.done-txt').show();
	      	editButton.find('.edit-txt').hide();

	      	editMode(row);
	    }


	    row.find('.update-control').unbind('click').bind('click', function(){

		    var cell = jQuery(this).parent();

		    // SET ID FOR USER OR REGISTREE
		    var fieldType = cell.attr('field-type');
		    var id = fieldType === 'meta' ? userId : aId;

		    // GET FIELD VALUES
		    var fieldName = cell.attr('field-data');
		    var fieldValue = cell.find('.update-field').val();

		    // IF VALUE IS A DATE
		    var validateValue = true;

		    if(cell.hasClass('user-attended')){

		        if(fieldValue.indexOf('(SE Asia Standard Time)') === -1){
		          	var date_regex = /^(0[1-9]|1[0-2])\/(0[1-9]|1\d|2\d|3[01])\/(19|20)\d{2}$/ ;
		          	if(!(date_regex.test(fieldValue))){
		            	validateValue = false;
		          	} else {
		            	fieldValue = new Date(fieldValue);
		            	fieldValue = moment.tz(fieldValue, "Asia/Ho_Chi_Minh").format('ddd MMM Do YYYY HH:mm:ss ZZ');
		            	fieldValue += 'GMT (SE Asia Standard Time)';
		          	}
		       	}
	      	}

	      	if(validateValue){

		        // DISABLE BUTTONS
		        cell.find('.update-control').hide();
		        cell.find('.updating').show();

		        var action = fieldType == 'meta' ? 'update_a_meta_field' : 'update_an_acf_field';

		        // AJAX
		        jQuery.ajax( {
		          url : myAjax.ajaxurl,
		          type: 'POST',
		          data: {
		              action  : action,
		              'id':id,
		              'field_name':fieldName,
		              'field_value':fieldValue,
		              'uid':userId
		          }
		        } )
		        .success( function( results ) {
		          console.log( 'User Meta Updated!', results);

		          // UPDATE THE FIELD
		          cell.find('.update-field').attr('value', fieldValue);

		          // UPDATE THE CITY WITH THE POST TITLE NOT ID
		          if(cell.hasClass('attended-place')){
		            cell.find('option').each(function(){
		              if(jQuery(this).val() === fieldValue){
		                cell.find('.field-default').text(jQuery(this).text());
		              }
		            });
		          } else {
		            cell.find('.field-default').text(fieldValue);
		          }

		          // UPDATE THE MODIFIED FIELD
		          console.log(row.find('.user-last-modified .field-def'));
		          row.find('.user-last-modified .field-def').empty().text(results);

		          // CLEANUP
		          cell.find('.update-control').show();
		          cell.find('.updating').hide();

		        } )
		        .fail( function( data ) {
		            console.log( data.responseText );
		            console.log( 'Request failed: ' + data.statusText );
		        } );

	      	} else {
	        	// NOT A VALID DATE
	        	alert("Not a valid dat, try : MM/DD/YYYY");
	      	}

	    });


	    function editMode(e){
	      e.find('.field-default').hide();
	      e.find('.update-field').show();
	      e.find('.update-control').show();
	      e.find('.helper').show();
	    }

	    function viewMode(e){
	      e.find('.field-default').show();
	      e.find('.update-field').hide();
	      e.find('.update-control').hide();
	      e.find('.helper').hide();
	      e.find('.updating').hide();
	    }


	});

	jQuery('#download-attendees, #download-xls').click(function(e){
	  	
	    e.preventDefault();

	    var self = jQuery(this);
	    var eventID = self.attr('event-data');
	    var html = '';

	    self.addClass('disabled');

	    jQuery.ajax( {
	        url : myAjax.ajaxurl,
	        type: 'POST',
	        data: {
	            action  : 'export_attendees',
	            'eventID':eventID              }
	        } )
	        .success( function( results ) {
	            console.log( 'User Meta Updated!', results);

	            self.removeClass('disabled');

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
});