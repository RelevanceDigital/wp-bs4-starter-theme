jQuery(document).ready(function ($) {

    //Menu hover behaviour for touchscreen devices
    // Touch devices
    if (Modernizr.touch) {
        jQuery('.navbar-nav li.dropdown > a').click(function (e) {

            //If a menu item is a parent and a link then prevent default if sub is hidden
            if (!jQuery(this).closest('.navbar-nav li.dropdown').hasClass('show')) {
                e.preventDefault();
                jQuery(this).closest('.navbar-nav li.dropdown').addClass('show');
            }
        });

        jQuery('body').click(function (e) {
            //If there is a click elsewhere then remove the show class from parents
            if (!jQuery(e.target).is('.navbar-nav li.dropdown > a')) {
                jQuery('.navbar-nav li.dropdown').removeClass('show');
            }
        });
    }

});