function initializeJS() {



    //tool tips

    jQuery('.tooltips').tooltip();



    //popovers

    jQuery('.popovers').popover();



    //custom scrollbar

        //for html

    jQuery("html").niceScroll({styler:"fb",cursorcolor:"#007AFF", cursorwidth: '6', cursorborderradius: '10px', background: '#F7F7F7', cursorborder: '', zindex: '1000'});

        //for sidebar

    jQuery("#sidebar").niceScroll({styler:"fb",cursorcolor:"#007AFF", cursorwidth: '3', cursorborderradius: '10px', background: '#F7F7F7', cursorborder: ''});

        // for scroll panel

    jQuery(".scroll-panel").niceScroll({styler:"fb",cursorcolor:"#007AFF", cursorwidth: '3', cursorborderradius: '10px', background: '#F7F7F7', cursorborder: ''});

    

    //sidebar dropdown menu

    jQuery('#sidebar .sub-menu > a').click(function () {

        var last = jQuery('.sub-menu.open', jQuery('#sidebar'));        

        //jQuery('.menu-arrow').removeClass('arrow_carrot-right');

        jQuery('.sub', last).slideUp(200);
		jQuery(last).removeClass('open');
		jQuery(last).find('a').removeClass('active');
		jQuery(last).find('a span.menu-arrow').removeClass('arrow_carrot-down');
		jQuery(last).find('a span.menu-arrow').addClass('arrow_carrot-right');
        var sub = jQuery(this).next();
		var subb = jQuery(this).closest('.sub-menu');

        if (sub.is(":visible")) {
			subb.removeClass('open');
			jQuery(this).removeClass('active');
            jQuery(this).find('span.menu-arrow').addClass('arrow_carrot-right');  
			jQuery(this).find('span.menu-arrow').removeClass('arrow_carrot-down');          

            sub.slideUp(200);

        } else {
			
			subb.addClass('open');
			jQuery(this).addClass('active'); 
           jQuery(this).find('span.menu-arrow').addClass('arrow_carrot-down');
		   jQuery(this).find('span.menu-arrow').removeClass('arrow_carrot-right');              

            sub.slideDown(200);

        }

        var o = (jQuery(this).offset());

        diff = 200 - o.top;

        if(diff>0)

            jQuery("#sidebar").scrollTo("-="+Math.abs(diff),500);

        else

            jQuery("#sidebar").scrollTo("+="+Math.abs(diff),500);

    });



}
