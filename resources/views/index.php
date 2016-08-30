<!-- 
Template Name: Supr - Responsive Admin Template build with Twitter Bootstrap 3.3.4
Version: 4.0.0
Author: SuggeElson
Website: http://www.suggeeelson.com/
Contact: support@suggeelson.com
Follow: www.twitter.com/suggeelson
Like: https://www.facebook.com/pages/SuggeElson/264113463621030
Purchase: http://themeforest.net/item/supr-responsive-bootstrap-3-admin-template/2834580?ref=SuggeElson
License: You must have a valid license purchased only from themeforest (the above link) in order to legally use the theme for your project.
-->
<!DOCTYPE html>
<html lang=en>

<head>
    <meta charset=utf-8>
    <title>Login | Supr Admin Template</title>
    <!-- Mobile specific metas -->
    <meta name=viewport content="width=device-width,initial-scale=1,maximum-scale=1">
    <!-- Force IE9 to render in normal mode -->
    <!--[if IE]><meta http-equiv="x-ua-compatible" content="IE=9" /><![endif]-->
    <meta name=author content="">
    <meta name=description content="">
    <meta name=keywords content="">
    <meta name=application-name content="">
    <!-- Import google fonts - Heading first/ text second -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,700" rel=stylesheet type=text/css>
    <link href="http://fonts.googleapis.com/css?family=Droid+Sans:400,700" rel=stylesheet type=text/css>
    <!-- Css files -->
    <link rel=stylesheet href="css/main.min.css">
    <!-- Fav and touch icons -->
    <link rel=apple-touch-icon-precomposed sizes=144x144 href="http://themes.suggelab.com/supr/img/ico/apple-touch-icon-144-precomposed.png">
    <link rel=apple-touch-icon-precomposed sizes=114x114 href="http://themes.suggelab.com/supr/img/ico/apple-touch-icon-114-precomposed.png">
    <link rel=apple-touch-icon-precomposed sizes=72x72 href="http://themes.suggelab.com/supr/img/ico/apple-touch-icon-72-precomposed.png">
    <link rel=apple-touch-icon-precomposed href="http://themes.suggelab.com/supr/img/ico/apple-touch-icon-57-precomposed.png">
    <link rel=icon href="http://themes.suggelab.com/supr/img/ico/favicon.ico" type=image/png>
    <!-- Windows8 touch icon ( http://www.buildmypinnedsite.com/ )-->
    <meta name=msapplication-TileColor content="#3399cc">

<body class=login-page>
<div id=header class="animated fadeInDown">
    <div class=row>
        <div class=navbar>
            <div class="container text-center"><a class=navbar-brand href="http://themes.suggelab.com/supr/dashboard.html">Supr.<span class=slogan>admin</span></a>
            </div>
        </div>
        <!-- /navbar -->
    </div>
    <!-- End .row -->
</div>
<!-- End #header -->
<!-- Start login container -->
<div class="container login-container">
    <div class="login-panel panel panel-default plain animated bounceIn">
        <!-- Start .panel -->
        <div class=panel-body>
            <form class="form-horizontal mt0" action="index.html" id=login-form role=form>
                <div class=form-group>
                    <div class=col-md-12>
                        <!-- col-md-12 start here -->
                        <label for="">Username:</label>
                    </div>
                    <!-- col-md-12 end here -->
                    <div class=col-lg-12>
                        <div class="input-group input-icon">
                            <input name=username id=username class=form-control value=Administrator placeholder="Enter username ..."> <span class=input-group-addon><i class="icomoon-icon-user s16"></i></span>
                        </div>
                    </div>
                </div>
                <div class=form-group>
                    <div class=col-md-12>
                        <!-- col-md-12 start here -->
                        <label for="">Password:</label>
                    </div>
                    <!-- col-md-12 end here -->
                    <div class=col-lg-12>
                        <div class="input-group input-icon">
                            <input type=password name=password id=password class=form-control value=somepass placeholder="Your password"> <span class=input-group-addon><i class="icomoon-icon-lock s16"></i></span>
                        </div><span class="help-block text-right"><a href="lost-password.html">Forgout password ?</a></span>
                    </div>
                </div>
                <div class="form-group mb0">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-8">
                        <div class=checkbox-custom>
                            <input type=checkbox name=remember id=remember value=option>
                            <label for=remember>Remember me ?</label>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-4 mb25">
                        <button class="btn btn-default pull-right" type=submit>Login</button>
                    </div>
                </div>
            </form>
            <div class=seperator><strong>or</strong>
                <hr>
            </div>
            <div class="social-buttons text-center mt5 mb5"><a href="login.html#" class="btn btn-primary btn-alt mr10">Sign in with <i class="fa fa-facebook s20 ml5 mr0"></i></a>  <a href="login.html#" class="btn btn-danger btn-alt ml10">Sign in with <i class="fa fa-google-plus s20 ml5 mr0"></i></a>
            </div>
        </div>
        <div class="panel-footer gray-lighter-bg">
            <h4 class=text-center><strong>Don`t have an account ?</strong></h4>
            <p class=text-center><a href="register.html" class="btn btn-success">Create account</a>
            </p>
        </div>
    </div>
    <!-- End .panel -->
</div>
<!-- End login container -->
<div class=container>
    <div class=footer>
        <p class=text-center>&copy;2015 Copyright Supr.admin. All right reserved !!!</p>
    </div>
</div>
<!-- Javascripts -->
<!-- Important javascript libs(put in all pages) -->
<script src="<?php echo  asset('js/libs/jquery-2.1.1.min.js') ?>"></script>
<script src="<?php echo  asset('js/libs/jquery-ui-1.10.4.min.js') ?>"></script>
<script src="<?php echo  asset('js/libs/jquery-migrate-1.2.1.min.js') ?>"></script>
<!--[if lt IE 9]>
<script type="text/javascript" src="<?php echo  asset('js/libs/excanvas.min.js') ?>"></script>
<script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<script type="text/javascript" src="<?php echo  asset('js/libs/respond.min.js') ?>"></script>
<![endif]-->
<script src="js/pages/login.js"></script>
<!-- Google Analytics:  -->
