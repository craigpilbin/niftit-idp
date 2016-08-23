jQuery(document).ready(function() {
  jQuery('.print-user').on('click', function(e) {
    e.preventDefault();
    var printID = jQuery(this).next().attr('id');
    jQuery.print("#" + printID, {title: '', iframe: false});

  })

  //invoke the table-sorter
  jQuery("#registrees, #users").tablesorter({sortList: [ [0,0] ]});

  //invoke the table-export
  //jQuery('#users-table-export').on('click', function() {
    /* Defaults */
    jQuery("#registrees, #users, #user-notes").tableExport({
        headings: true,                    // (Boolean), display table headings (th/td elements) in the <thead>
        footers: true,                     // (Boolean), display table footers (th/td elements) in the <tfoot>
        formats: ["xls", "csv"],    // (String[]), filetypes for the export
        fileName: "id",                    // (id, String), filename for the downloaded file
        bootstrap: true,                   // (Boolean), style buttons using bootstrap
        position: "bottom",                 // (top, bottom), position of the caption element relative to table
        ignoreRows: null,                  // (Number, Number[]), row indices to exclude from the exported file
        ignoreCols: [9,10],                   // (Number, Number[]), column indices to exclude from the exported file
        ignoreCSS: ".tableexport-ignore"   // (selector, selector[]), selector(s) to exclude from the exported file
    });
  //});


    jQuery.fn.tableExport.charset = "charset=utf-8";

	jQuery('.print-user').each(function() {
		jQuery(this).on('click', function() {
			var url = window.location.href.split('&user')[0];
			//window.location = url;
		});
	});
});
