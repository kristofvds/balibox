$(function() {

    // Smooth scroll
    $(document).on('click', 'a[href*="#"]:not([href="#"])', function() {
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
    $(document).on('click', '[data-target="checkout"]', function() {
        var product = $(this).attr('data-product');
        localStorage.setItem('product', product);
        $('.wrapper').load("checkout-shipping.html", function() {
            initCheckoutShipping();
        });
    });

    $(document).on('click', '.btn-back', function() {
        window.history.back();
    });

    // Form submit
    $(document).on("submit", "#form-checkout-shipping", function() {
        $.post("checkout-shipping.php", $("#form-checkout-shipping").serialize()).done(function(data) {
                console.log(data);
                localStorage.setItem('orderID', data.id);
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

    initHome = function() {
        $.material.init();
        $('.navbar').addClass('navbar-transparent navbar-absolute');
        $('html, body').animate({scrollTop: 0}, 500);
    };

    initCheckout = function() {
        $.material.init();
        var product = localStorage.getItem('product');
        $('.navbar').removeClass('navbar-transparent navbar-absolute');
        $('#product-name').text(product);
        $('[name="product"]').val(product);
        $('#container-product-description').removeClass('hidden');
        $('html, body').animate({scrollTop: 0}, 500);
    };

    initCheckoutShipping = function() {
        initCheckout();
        var getParams = getSearchParameters();
        if (getParams.p !== 'checkout-payment') {
            history.pushState(null, null, '?p=home');
            history.pushState(null, null, '?p=checkout-shipping');
        }
        initFieldBindings($("#form-checkout-shipping"));
    };

    initCheckoutPayment = function() {
        initCheckout();

        var getParams = getSearchParameters();
        if (getParams.p !== 'checkout-payment') {
            history.pushState(null, null, '?p=checkout-payment');
        }

        initFieldBindings($("#form-checkout-payment").add("#form-paypal"));

        var product = localStorage.getItem('product');
        var paypalButtonInput = $("#input-paypalbutton")[0];
        switch (product) {
            case "1": paypalButtonInput.value = "XTV9YWYLX5SL8"; break;
            case "3": paypalButtonInput.value = "FDTLMN8VL3JNQ"; break;
            case "6": paypalButtonInput.value = "QPXDMB858CEE4"; break;
            default: paypalButtonInput.value = "XTV9YWYLX5SL8"; break;
        }

        $("#input-paypalcustom")[0].value = localStorage.getItem('orderID');;

        $('[name="input-billing-address"').off("change").on("change", function() {
            var $billingAddressFields = $('#billing-address-fields');
            if (this.value === "same") {
                $billingAddressFields.addClass("hidden");
            } else {
                $billingAddressFields.removeClass("hidden");
            }
        });

        if (getParams.status === 'payment-canceled') {
            $('#container-payment-canceled').removeClass('hidden');
        }
    };

    // Field binding
    initFieldBindings = function($container) {

        // Save values of changed fields
        var $fields = $container.find('[data-bound="true"]');
        $fields.each(function() {
            var $field = $(this);
            $field.off("blur.binding").on("blur.binding", function() {
                localStorage.setItem(this.id, this.value);
            });
        });

        // Fill values to bound elements
        var $boundElements = $container.find('[data-binding]');
        $boundElements.each(function() {
            var $element = $(this);
            var binding = $element.attr("data-binding");
            if (this.nodeName.toLowerCase() === "input") {
                $element.val(localStorage.getItem(binding));
            } else {
                $element.html(localStorage.getItem(binding));
            }
        });
    };

    // Read GET params from URL
    function getSearchParameters() {
          var prmstr = window.location.search.substr(1);
          return prmstr != null && prmstr != "" ? transformToAssocArray(prmstr) : {};
    }
    function transformToAssocArray( prmstr ) {
        var params = {};
        var prmarr = prmstr.split("&");
        for ( var i = 0; i < prmarr.length; i++) {
            var tmparr = prmarr[i].split("=");
            params[tmparr[0]] = decodeURIComponent(tmparr[1]);
        }
        return params;
    }

    // On hard reload, init page content
    function initGetParams() {
        var getParams = getSearchParameters();
        switch (getParams.p) {
            case 'checkout-shipping': initCheckoutShipping(); break;
            case 'checkout-payment': initCheckoutPayment(); break;
            case 'checkout-result': initCheckout(); break;
        }
    }
    initGetParams();

    // On pop state, AJAX load page content and init
    $(window).on('popstate', function() {
        var getParams = getSearchParameters();
        switch (getParams.p) {
            case 'home':
                $('.wrapper').load("home.html", function() {
                    initHome();
                });
                break;
            case 'checkout-shipping':
                $('.wrapper').load("checkout-shipping.html", function() {
                    initCheckoutShipping();
                });
                break;
            case 'checkout-payment':
                $('.wrapper').load("checkout-payment.html", function() {
                    initCheckoutPayment();
                });
                break;
        }
    });

});