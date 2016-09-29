<!doctype html>
<html class="no-js" lang="">
    <head>
        <!-- Meta -->
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Bali Box</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Icons -->
        <link rel="apple-touch-icon-precomposed" sizes="57x57" href="img/ico/apple-touch-icon-57x57.png" />
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="img/ico/apple-touch-icon-114x114.png" />
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="img/ico/apple-touch-icon-72x72.png" />
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="img/ico/apple-touch-icon-144x144.png" />
        <link rel="apple-touch-icon-precomposed" sizes="60x60" href="img/ico/apple-touch-icon-60x60.png" />
        <link rel="apple-touch-icon-precomposed" sizes="120x120" href="img/ico/apple-touch-icon-120x120.png" />
        <link rel="apple-touch-icon-precomposed" sizes="76x76" href="img/ico/apple-touch-icon-76x76.png" />
        <link rel="apple-touch-icon-precomposed" sizes="152x152" href="img/ico/apple-touch-icon-152x152.png" />
        <link rel="icon" type="image/png" href="img/ico/favicon-196x196.png" sizes="196x196" />
        <link rel="icon" type="image/png" href="img/ico/favicon-96x96.png" sizes="96x96" />
        <link rel="icon" type="image/png" href="img/ico/favicon-32x32.png" sizes="32x32" />
        <link rel="icon" type="image/png" href="img/ico/favicon-16x16.png" sizes="16x16" />
        <link rel="icon" type="image/png" href="img/ico/favicon-128.png" sizes="128x128" />

        <!-- Fonts and icons -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Pacifico|Raleway:400,700|Open+Sans+Condensed:700|Material+Icons">

        <!-- CSS -->
        <link rel="stylesheet" href="css/normalize.css">
        <link href="css/bootstrap.min.css" rel="stylesheet" />
        <link href="css/material-kit.css" rel="stylesheet"/>
        <link rel="stylesheet" href="css/main.css">
        <script src="js/vendor/modernizr-2.8.3.min.js"></script>
    </head>
    <body data-page="home">

        <header>
            <h1>Bali Box</h1>
            <p>Discover the natural beauty of the island of gods.</p>
            <div class="line"></div>
            <div class="checkout">
                <div class="checkout__order">
                    <div class="checkout__order-inner">
                        <h2>Bali Box</h2>
                        <table class="checkout__summary">
                            <thead>
                                <tr>
                                    <th>Your Order</th>
                                    <th>Months</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr><th colspan="3">Total <span class="checkout__total price">$30.00</span></th></tr>
                            </tfoot>
                            <tbody>
                                <tr>
                                    <td>Bali Box subscription</td>
                                    <td>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="checkout-months" id="checkout-months-1" value="1" checked> 1 month
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="checkout-months" id="checkout-months-3" value="3"> 3 months
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="checkout-months" id="checkout-months-6" value="6"> 6 months
                                            </label>
                                        </div>
                                    </td>
                                    <td class="price">$30.00</td>
                                </tr>
                            </tbody>
                        </table><!-- /checkout__summary -->
                        <button class="btn btn-link btn-checkout-back">&laquo; Cancel</button>
                        <button class="btn btn-primary btn-checkout-buy">Checkout</button>
                        <button class="btn-checkout-close btn-checkout-back">&times;</button>
                    </div><!-- /checkout__order-inner -->
                </div><!-- /checkout__order -->
            </div>
        </header>
        
        <div class="wrapper">
        <?php
            if (!isset($_GET['p']))
            {
                include 'home.html';
            }
            else
            {
                $page = $_GET['p'];
                switch ($page) {
                    case 'home':
                        include 'home.html';
                        break;

                    case 'home-dev':
                        include 'home-dev.html';
                        break;
                        
                    case 'checkout-shipping':
                        include 'checkout-shipping.html';
                        break;
                        
                    case 'checkout-payment':
                        include 'checkout-payment.html';
                        break;
                        
                    case 'checkout-result':
                        include 'checkout-result.php';
                        break;

                    default:
                        include 'home.html';
                        break;
                }
            }
        ?>
        </div>
        <div id="html-cache-home" class="hidden"></div>
        <div id="html-cache-home-dev" class="hidden"></div>
        <div id="html-cache-checkout-shipping" class="hidden"></div>
        <div id="html-cache-checkout-payment" class="hidden"></div>

        <!-- JavaScript -->
        <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.12.0.min.js"><\/script>')</script>
        <script src="js/vendor/bootstrap.min.js" type="text/javascript"></script>
        <script src="js/vendor/material.min.js"></script>
        <script src="js/vendor/material-kit.js"></script>
        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
<!--         <script>
            (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            e.src='https://www.google-analytics.com/analytics.js';
            r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
            ga('create','UA-XXXXX-X','auto');ga('send','pageview');
        </script> -->
    </body>
</html>