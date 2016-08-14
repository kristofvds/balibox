$(function() {

    // Smooth scroll
    $('a[href*="#"]:not([href="#"])').click(function() {
        if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
            if (target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top
                }, 1000);
                return false;
            }
        }
    });

    // Barba
    var Homepage = Barba.BaseView.extend({
        namespace: 'homepage',
        onEnter: function() {
            $('.navbar').addClass('navbar-transparent navbar-absolute');
        },
        onLeave: function() {
            $('.navbar').removeClass('navbar-transparent navbar-absolute');
        }
    });
    Homepage.init();
    Barba.Pjax.start();
});