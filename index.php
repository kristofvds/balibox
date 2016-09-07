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
        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <!-- Place favicon.ico in the root directory -->

        <!--     Fonts and icons     -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />

        <!-- CSS -->
        <link rel="stylesheet" href="css/normalize.css">
        <link href="css/bootstrap.min.css" rel="stylesheet" />
        <link href="css/material-kit.css" rel="stylesheet"/>
        <link rel="stylesheet" href="css/main.css">
        <script src="js/vendor/modernizr-2.8.3.min.js"></script>
    </head>
    <body data-page="home">
        <?php
        /*
        <nav class="navbar navbar-transparent navbar-absolute">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/balibox">Bali Box</a>
                </div>

                <div class="collapse navbar-collapse" id="navigation-example">
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="#about">
                                About
                            </a>
                        </li>
                        <li>
                            <a href="#howitworks">
                                How it works
                            </a>
                        </li>
                        <li>
                            <a href="#subscribe">
                                Subscribe
                            </a>
                        </li>
                        <li>
                            <a href="" target="_blank" class="btn btn-simple btn-white btn-just-icon">
                                <i class="fa fa-twitter"></i>
                            </a>
                        </li>
                        <li>
                            <a href="" target="_blank" class="btn btn-simple btn-white btn-just-icon">
                                <i class="fa fa-facebook-square"></i>
                            </a>
                        </li>
                        <li>
                            <a href="" target="_blank" class="btn btn-simple btn-white btn-just-icon">
                                <i class="fa fa-instagram"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        */
        ?>

        <header>
            <h2>Bali Box</h2>
            <p>Discover the natural beauty of the island of gods.</p>
            <a class="btn btn-primary btn-subscribe">Subscribe</a>
            <div class="checkout">
                <div class="checkout__order">
                    <div class="checkout__order-inner">
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
                        <button class="btn btn-link btn-checkout-back"><i class="fa fa-angle-left"></i> Continue Shopping</button>
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