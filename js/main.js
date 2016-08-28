var boundValues = {},
    orderID;

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
            initCheckoutShipping();
        });
    });

    // Form submit
    $(document).on("submit", "#form-checkout-shipping", function() {
        $.post("checkout-shipping.php", $("#form-checkout-shipping").serialize()).done(function(data) {
                console.log(data);
                orderID = data.id;
                $('.wrapper').load("checkout-payment.html", function() {
                    initCheckoutPayment();
                });
        });
        return false;
    });
    $(document).on("click", "#btn-paypal", function() {
        $.post("checkout-payment.php", $("#form-checkout-payment").serialize()).done(function(data) {
                console.log(data);
                $('#form-paypal').submit();
        }).fail(function(data) {
            console.log(data.statusText);
            console.log(data.responseText);
        });
        return false;
    });

    // Page init
    initCheckoutShipping = function() {
        $.material.init();
        var product = localStorage.getItem('product');
        $('.navbar').removeClass('navbar-transparent navbar-absolute');
        $('#product-name').text(product);
        $('[name="product"]').val(product);
        $('#container-product-description').removeClass('hidden');
        $('html, body').animate({scrollTop: 0}, 500);
        initFieldBindings($("#form-checkout-shipping"));
    };

    initCheckoutPayment = function() {
        $.material.init();
        $('html, body').animate({scrollTop: 0}, 500);
        initFieldBindings($("#form-checkout-payment").add("#form-paypal"));

        var product = localStorage.getItem('product');
        var paypalButtonInput = $("#input-paypalbutton")[0];
        switch (product) {
            case "1": paypalButtonInput.value = "XTV9YWYLX5SL8"; break;
            case "3": paypalButtonInput.value = "FDTLMN8VL3JNQ"; break;
            case "6": paypalButtonInput.value = "QPXDMB858CEE4"; break;
            default: paypalButtonInput.value = "XTV9YWYLX5SL8"; break;
        }

        $("#input-paypalcustom")[0].value = orderID;

        $('[name="input-billing-address"').off("change").on("change", function() {
            var $billingAddressFields = $('#billing-address-fields');
            if (this.value === "same") {
                $billingAddressFields.attr("disabled", "disabled");
            } else {
                $billingAddressFields.removeAttr("disabled");
            }
        });
    };

    // Field binding
    initFieldBindings = function($container) {

        // Save values of changed fields
        var $fields = $container.find('[data-bound="true"]');
        $fields.each(function() {
            var $field = $(this);
            $field.off("blur.binding").on("blur.binding", function() {
                boundValues[this.id] = this.value;
            });
        });

        // Fill values to bound elements
        var $boundElements = $container.find('[data-binding]');
        $boundElements.each(function() {
            var $element = $(this);
            var binding = $element.attr("data-binding");
            if (this.nodeName.toLowerCase() === "input") {
                $element.val(boundValues[binding]);
            } else {
                $element.html(boundValues[binding]);
            }
        });
    };
});