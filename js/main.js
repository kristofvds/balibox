$(function() {

    // Smooth scroll
    $('a[href*="#"]:not([href="#"])').click(function() {
        if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
            if (target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top
                }, 500);
                return false;
            }
        }
    });

    // Navigation
    $('[data-target="checkout"]').click(function() {
        var product = $(this).attr('data-product');
        localStorage.setItem('product', product);
        $('.wrapper').load("checkout-shipping.html", function() {
            $('.navbar').removeClass('navbar-transparent navbar-absolute');
            $('#product-name').text(product);
            $('[name="product"]').val(product);
            $('#container-product-description').removeClass('hidden');
        });
    });

    // Form submit
    $(document).on("submit", "#form-checkout-shipping", function() {
        $.post("checkout-shipping.php", $("#form-checkout-shipping").serialize(), function(data) {
            console.log(data);
        });
        return false;
    });
});