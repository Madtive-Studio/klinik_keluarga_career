/* Theme Name: Jobya - Responsive Landing Page Template
   Author: Themesdesign
   Version: 1.0.0
   File Description: Main JS file of the template
*/


(function ($) {

    'use strict';

    // Loader 
    $(window).on('load', function() {
        $('#status').fadeOut();
        $('#preloader').delay(350).fadeOut('slow');
        $('body').delay(350).css({
            'overflow': 'visible'
        });
    }); 

    // Selectize
    $('#select-category, #select-lang,#select-country').selectize({
        create: true,
        sortField: {
            field: 'text',
            direction: 'asc'
        },
        dropdownParent: 'body'
    });

    // Checkbox all select
    $("#customCheckAll").click(function() {
        $(".all-select").prop('checked', $(this).prop('checked'));
    });

    // Nice Select
    $('.nice-select').niceSelect();

    // Back to top
    $(window).scroll(function(){
        if ($(this).scrollTop() > 100) {
            $('.back-to-top').fadeIn();
        } else {
            $('.back-to-top').fadeOut();
        }
    }); 

    $('.back-to-top').click(function(){
        $("html, body").animate({ scrollTop: 0 }, 3000);
        return false;
    });

    $(window).ready(function() {
        const today = new Date();

        flatpickr.localize(window.appLocale === 'id' ? flatpickr.l10ns.id : flatpickr.l10ns.default);
        $(".flatpickr").flatpickr({
            dateFormat: "Y-m-d",
            allowInput: true,
            altInput: true,
            altFormat: "d F Y",
            locale: window.appLocale === 'id' ? 'id' : 'default',
            disableMobile: "true",
            defaultDate: today
        });

    });
})(jQuery)