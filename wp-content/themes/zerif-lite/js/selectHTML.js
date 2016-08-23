jQuery(document).ready(function() {
    jQuery(function() {
      jQuery(".casing").click(function() {
        jQuery(this).children(".placeholder").addClass("move");
      });
    });
    
    /*========== Grid Height Fix =========*/
    var maxHeight = -1;
    jQuery('.grid-holder').each(function() {
        if (jQuery(this).height() > maxHeight) {
            maxHeight = jQuery(this).height();
        }
    });
    jQuery('.grid-holder').height(maxHeight);
 });



