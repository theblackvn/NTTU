$(document).ready(function(){
	toggledMenu();
	slideMulti();
        $('#contactForm').validate({
        rules: {
            name: 'required',
            phone: {
                required: true,
                number: true,
                minlength: 10,
                maxlength: 11
            },
            subject: 'required',
            'content-mail': 'required',
            email: {
                required: true,
                email: true
            },
        },
        errorPlacement: function(label, element) {
            label.addClass('c-red');
            label.insertAfter(element);
        },
    });
});

function toggledMenu(){
$('body').on('click', '#hnav-trigger, #menu-trigger', function (e) {
    e.preventDefault();
    var x = $(this).data('trigger');

    $(x).toggleClass('toggled');

    if (x == '#hnav') {

        $elem = '#hnav';
        $elem2 = '#hnav-trigger';

        if (!$('#menu').hasClass('toggled')) {
            $('body').toggleClass('sidebar-toggled');
        }
        else {
            $('#menu').removeClass('toggled');
        }
    }

    if (x == '#menu') {
        $elem = '#menu';
        $elem2 = '#menu-trigger';

        $('#menu-trigger').removeClass('open');

        if (!$('#hnav').hasClass('toggled')) {
            $('body').toggleClass('sidebar-toggled');
        }
        else {
            $('#hnav').removeClass('toggled');
        }
    }
    //When clicking outside
    if ($('body').hasClass('sidebar-toggled')) {
         $(document).on('click', function (e) {
            if (($(e.target).closest($elem).length === 0) && ($(e.target).closest($elem2).length === 0)) {
                setTimeout(function () {
                    $($elem).removeClass('toggled');
                    $('body').removeClass('sidebar-toggled');
                });
            }
        });
    }
})
};
function slideMulti(){
    console.log($('.multi-slide .slide').width());
    $('.multi-slide').bxSlider({
        slideWidth: $('.multi-slide .slide').width(),
        minSlides: 1,
        maxSlides: 3,
        moveSlides: 1,
        slideMargin: 0,
        pager: false,
        nextText: '<i class="fa fa-angle-right" aria-hidden="true"></i>',
        prevText: '<i class="fa fa-angle-left" aria-hidden="true"></i>'
    });
}