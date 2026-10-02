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
    $('#select-lang,#select-country').selectize({
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
        if (typeof flatpickr !== 'undefined') {
            flatpickr.localize(window.appLocale === 'id' ? flatpickr.l10ns.id : flatpickr.l10ns.default);
            $(".flatpickr").each(function() {
                const $this = $(this);
                const isBirthDate = $this.attr('name') === 'birth_date' || $this.hasClass('flatpickr-birthdate') || $this.attr('id') === 'birth_date_input';
                const explicitMax = $this.attr('max') || $this.data('max-date');

                $this.flatpickr({
                    dateFormat: "Y-m-d",
                    allowInput: false,
                    altInput: true,
                    altFormat: "d F Y",
                    locale: window.appLocale === 'id' ? 'id' : 'default',
                    disableMobile: true,
                    maxDate: isBirthDate ? "today" : (explicitMax || undefined),
                    defaultDate: $this.val() ? $this.val() : undefined
                });
            });
        }
    });
})(jQuery)