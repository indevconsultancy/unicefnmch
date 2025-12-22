$(document).ready(function() {
    $(".navExpandButton").click(OnClickExpandNav);
    $(".navCollapseButton").click(OnClickCollapseNav);
    
    $("#homeNavLink").hover(HideSubNavs);
    $("#featuresNavLink").hover(HideSubNavs);
    $("#pricingNavLink").hover(HideSubNavs);
    $("#resourcesLink").click(ShowResourceSubNav).hover(ShowResourceSubNav);
    $("#aboutLink").click(ShowAboutSubNav).hover(ShowAboutSubNav);
    $("#resourcesSubNav").hover(null, HideSubNavs);
    $("#aboutSubNav").hover(null, HideSubNavs);
    RenderInlineSVGs();
});

function OnClickExpandNav()
{
    $(".mainNav").show();
    $(".navExpandButton").hide();
    $(".navCollapseButton").show();
}

function OnClickCollapseNav() {
    $(".mainNav").hide();
    $(".navExpandButton").show();
    $(".navCollapseButton").hide();    
}

function ShowResourceSubNav() {
    $("#aboutSubNav").hide();
    $("#resourcesSubNav").show();
}

function ShowAboutSubNav()
{
    $("#resourcesSubNav").hide();
    $("#aboutSubNav").show();
}

function HideSubNavs()
{
    $("#resourcesSubNav").hide();
    $("#aboutSubNav").hide();
}

//Renders all imgs with the class svginline into an inline svg so we can use css to colorize the svg
function RenderInlineSVGs() {
    jQuery('img.svginline').each(function () {
        var $img = jQuery(this);
        var imgID = $img.attr('id');
        var imgClass = $img.attr('class');
        var imgURL = $img.attr('src');

        jQuery.get(imgURL, function (data) {
            // Get the SVG tag, ignore the rest
            var $svg = jQuery(data).find('svg');

            // Add replaced image's ID to the new SVG
            if (typeof imgID !== 'undefined') {
                $svg = $svg.attr('id', imgID);
            }
            // Add replaced image's classes to the new SVG
            if (typeof imgClass !== 'undefined') {
                $svg = $svg.attr('class', imgClass + ' replaced-svg');
            }

            // Remove any invalid XML tags as per http://validator.w3.org
            $svg = $svg.removeAttr('xmlns:a');

            // Replace image with new SVG
            $img.replaceWith($svg);

        }, 'xml');

    });
}