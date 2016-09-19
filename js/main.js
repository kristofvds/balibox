$(function() {

    var $document = $(document),
        $body = $('body'),
        $checkout = $('.checkout'),
        $wrapper = $('.wrapper'),
        $loadingButton,
        pages = {
            "home": { isCached: false, content: '' },
            "home-dev": { isCached: false, content: '' },
            "checkout-shipping": { isCached: false, content: '' },
            "checkout-payment": { isCached: false, content: '' }
        };

    if (localStorage.getItem('price') === null) {
        localStorage.setItem('price', '$30.00');
    }

    // Smooth scroll
    $document.on('click', 'a[href*="#"]:not([href="#"])', function() {
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
    $document.on('click', '.btn-subscribe', function() {
        $checkout.addClass('checkout--active');
    });
    $document.on('click', '.btn-checkout-back', function() {
        $checkout.removeClass('checkout--active');
    });
    $document.on('click', '.btn-back', function() {
        $loadingButton = $(this);
        $loadingButton.addClass('btn-loading');
        window.history.back();
    });
    $document.on('click', '.btn-checkout-buy', function() {
        var product = $('[name="checkout-months"]:checked').val();
        localStorage.setItem('product', product);
        $checkout.removeClass('checkout--active');
        loadPage("checkout-shipping", function() {
            initCheckoutShipping();
        });
    });

    // Form submit
    $document.on("submit", "#form-notify-me", function() {
        var $form = $(this);
        $loadingButton = $(this).find('button[type="submit"]');
        $loadingButton.addClass('btn-loading');
        $.post("notify-me.php", $form.serialize()).done(function(data) {
                console.log(data);
                stopLoadingAnimation();
                $form.addClass("hidden");
                $("#notify-me-result").removeClass("hidden");
        });
        return false;
    });
    $document.on("submit", "#form-checkout-shipping", function() {
        $loadingButton = $(this).find('button[type="submit"]');
        $loadingButton.addClass('btn-loading');
        $.post("checkout-shipping.php", $("#form-checkout-shipping").serialize()).done(function(data) {
                console.log(data);
                localStorage.setItem('orderID', data.id);
                loadPage("checkout-payment", function() {
                    initCheckoutPayment();
                });
        });
        return false;
    });
    $document.on("click", "#btn-paypal", function() {
        $loadingButton = $(this);
        $loadingButton.addClass('btn-loading');
        $.post("checkout-payment.php", $("#form-checkout-payment").serialize()).done(function(data) {
                console.log(data);
                $('#form-paypal').submit();
        }).fail(function(data) {
            stopLoadingAnimation();
            console.log(data.statusText);
            console.log(data.responseText);
        });
        return false;
    });

    // Page init
    initHome = function() {

        $('html, body').animate({scrollTop: 0}, 500);
        $body.attr('data-page', 'home-dev'); // todo: home
        if ($body.attr('data-src') !== "cache") {
            $.material.init();
        }

        initCbpScroller();

        $('[name="checkout-months"]').off("change").on("change", function() {
            var price = '$30.00';
            if (this.value === "3") {
                var price = '$84.00';
            } else if (this.value === "6") {
                var price = '$156.00';
            }
            $('.price').html(price);
            localStorage.setItem('price', price);
        });
    };

    initCheckout = function() {
        if ($body.attr('data-src') !== "cache") {
            $.material.init();
        }
        var product = localStorage.getItem('product');
        $('#product-name').text(product);
        $('.price').html(localStorage.getItem('price'));
        $('[name="product"]').val(product);
        $('html, body').animate({scrollTop: 0}, 500);
    };

    initCheckoutShipping = function() {
        initCheckout();
        $body.attr('data-page', 'checkout-shipping');
        var getParams = getSearchParameters();
        if (getParams.p !== 'checkout-shipping') {
            history.pushState(null, null, '?p=checkout-shipping');
        }
        initFieldBindings($("#form-checkout-shipping"));
    };

    initCheckoutPayment = function() {
        initCheckout();
        $body.attr('data-page', 'checkout-payment');
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

        $("#input-paypalcustom")[0].value = localStorage.getItem('orderID');

        $('[name="input-billing-address"]').off("change").on("change", function() {
            var $billingAddressFields = $('#billing-address-fields');
            if (this.value === "same") {
                $billingAddressFields.addClass("hidden");
            } else {
                $billingAddressFields.removeClass("hidden");
            }
        }).trigger("change");

        if (getParams.status === 'payment-canceled') {
            $('#container-payment-canceled').removeClass('hidden');
        }
    };

    // Field binding
    initFieldBindings = function($container) {

        // Save values of changed fields
        var $fields = $container.find('input[data-bound="true"]');
        $fields.each(function() {
            var $field = $(this);
            $field.off("blur.binding").on("blur.binding", function() {
                if (this.type === "checkbox") {
                    localStorage.setItem(this.id, this.checked);
                } else {
                    localStorage.setItem(this.id, this.value);
                }
            });
        });

        // Fill values to bound elements
        var $boundElements = $container.find('[data-binding]');
        $boundElements.each(function() {
            var $element = $(this);
            var binding = $element.attr("data-binding");
            var value = localStorage.getItem(binding);
            if (value.length) {
                if (this.nodeName.toLowerCase() === "input") {
                    if (this.type === "checkbox") {
                        this.checked = value === "true" ? true : false;
                    } else {
                        $element.val(value);
                    }
                } else {
                    $element.html(value);
                }
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

    /**
     * cbpScroller.js v1.0.0
     * http://www.codrops.com
     *
     * Licensed under the MIT license.
     * http://www.opensource.org/licenses/mit-license.php
     * 
     * Copyright 2013, Codrops
     * http://www.codrops.com
     */
    ;( function( window ) {
        
        'use strict';

        var docElem = window.document.documentElement;

        function getViewportH() {
            var client = docElem['clientHeight'],
                inner = window['innerHeight'];
            
            if( client < inner )
                return inner;
            else
                return client;
        }

        function scrollY() {
            return window.pageYOffset || docElem.scrollTop;
        }

        // http://stackoverflow.com/a/5598797/989439
        function getOffset( el ) {
            var offsetTop = 0, offsetLeft = 0;
            do {
                if ( !isNaN( el.offsetTop ) ) {
                    offsetTop += el.offsetTop;
                }
                if ( !isNaN( el.offsetLeft ) ) {
                    offsetLeft += el.offsetLeft;
                }
            } while( el = el.offsetParent )

            return {
                top : offsetTop,
                left : offsetLeft
            }
        }

        function inViewport( el, h ) {
            var elH = el.offsetHeight,
                scrolled = scrollY(),
                viewed = scrolled + getViewportH(),
                elTop = getOffset(el).top,
                elBottom = elTop + elH,
                // if 0, the element is considered in the viewport as soon as it enters.
                // if 1, the element is considered in the viewport only when it's fully inside
                // value in percentage (1 >= h >= 0)
                h = h || 0;

            return (elTop + elH * h) <= viewed && (elBottom) >= scrolled;
        }

        function extend( a, b ) {
            for( var key in b ) { 
                if( b.hasOwnProperty( key ) ) {
                    a[key] = b[key];
                }
            }
            return a;
        }

        function cbpScroller( el, options ) {   
            this.el = el;
            this.options = extend( this.defaults, options );
            this._init();
        }

        cbpScroller.prototype = {
            defaults : {
                // The viewportFactor defines how much of the appearing item has to be visible in order to trigger the animation
                // if we'd use a value of 0, this would mean that it would add the animation class as soon as the item is in the viewport. 
                // If we were to use the value of 1, the animation would only be triggered when we see all of the item in the viewport (100% of it)
                viewportFactor : 0.2
            },
            _init : function() {
                if( Modernizr.touch ) return;
                this.sections = Array.prototype.slice.call( this.el.querySelectorAll( '.cbp-so-section' ) );
                this.didScroll = false;

                var self = this;
                // the sections already shown...
                this.sections.forEach( function( el, i ) {
                    if( !inViewport( el ) ) {
                        //classie.add( el, 'cbp-so-init' );
                        $(el).addClass('cbp-so-init');
                    }
                } );

                var scrollHandler = function() {
                        if( !self.didScroll ) {
                            self.didScroll = true;
                            setTimeout( function() { self._scrollPage(); }, 60 );
                        }
                    },
                    resizeHandler = function() {
                        function delayed() {
                            self._scrollPage();
                            self.resizeTimeout = null;
                        }
                        if ( self.resizeTimeout ) {
                            clearTimeout( self.resizeTimeout );
                        }
                        self.resizeTimeout = setTimeout( delayed, 200 );
                    };

                window.addEventListener( 'scroll', scrollHandler, false );
                window.addEventListener( 'resize', resizeHandler, false );
            },
            _scrollPage : function() {
                var self = this;

                this.sections.forEach( function( el, i ) {
                    if( inViewport( el, self.options.viewportFactor ) ) {
                        //classie.add( el, 'cbp-so-animate' );
                        $(el).addClass('cbp-so-animate');
                    }
                    else {
                        // this add class init if it doesn´t have it. This will ensure that the items initially in the viewport will also animate on scroll
                        //classie.add( el, 'cbp-so-init' );
                        $(el).addClass('cbp-so-init');
                        
                        //classie.remove( el, 'cbp-so-animate' );
                        $(el).removeClass('cbp-so-animate');
                    }
                });
                this.didScroll = false;
            }
        }

        // add to global namespace
        window.cbpScroller = cbpScroller;

    } )( window );

    function initCbpScroller() {
        new cbpScroller( document.getElementById( 'cbp-so-scroller' ) );
    }

    // On hard reload, init page content
    function initGetParams() {
        var getParams = getSearchParameters();
        $body.attr('data-page', getParams.p);
        switch (getParams.p) {
            case 'checkout-shipping': initCheckoutShipping(); break;
            case 'checkout-payment': initCheckoutPayment(); break;
            case 'checkout-result': initCheckout(); break;
            default: initHome(); break;
        }
    }
    initGetParams();

    // On pop state, AJAX load page content and init
    $(window).on('popstate', function() {
        var getParams = getSearchParameters();
        switch (getParams.p) {
            case 'home':
                loadPage("home", function() {
                    initHome();
                });
                break;
            case 'home-dev':
                loadPage("home-dev", function() {
                    initHome();
                });
                break;
            case 'checkout-shipping':
                loadPage("checkout-shipping", function() {
                    initCheckoutShipping();
                });
                break;
            case 'checkout-payment':
                loadPage("checkout-payment", function() {
                    initCheckoutPayment();
                });
                break;
        }
    });

    function loadPage (page, callback) {

        // Cache the current page
        var currentPage = $('body').attr('data-page');
        pages[currentPage] = {
            isCached: true,
            content: $wrapper.html()
        }
        console.log('Cached: ' + currentPage);

        // Load new page
        if (pages[page].isCached) {
            // From cache
            var html = pages[page].content;
            if (typeof html === "undefined" || !html.length) {
                loadPageFromServer(page, callback);
            } else {
                $wrapper.html(html);
                $('body').attr('data-src', 'cache');
                callback();
                stopLoadingAnimation();
                console.log('Loaded from cache: ' + page);
            }
        } else {
            // From server
            loadPageFromServer(page, callback);
        }
    }

    function loadPageFromServer (page, callback) {
        page += ".html";
        $wrapper.load(page, function() {
            $('body').attr('data-src', 'server');
            callback();
            stopLoadingAnimation();
            console.log('Loaded from server: ' + page);
        });
    }

    function stopLoadingAnimation () {
        $('.btn-loading').removeClass('btn-loading');
    }

    // Preload images
    var img = new Image();
    img.src = "/img/ring.svg";

});