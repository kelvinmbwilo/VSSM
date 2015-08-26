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
    <title>Login | VSSM</title>
    <!-- Mobile specific metas -->
    <meta name=viewport content="width=device-width,initial-scale=1,maximum-scale=1">
    <!-- Force IE9 to render in normal mode -->
    <!--[if IE]><meta http-equiv="x-ua-compatible" content="IE=9" /><![endif]-->
    <meta name=author content="">
    <meta name=description content="">
    <meta name=keywords content="">
    <meta name=application-name content="">
    <!-- Css files -->
    <link rel=stylesheet href="<?php echo  asset('css/main.min.css') ?>">
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
            @if(isset($error))
            <div class="alert alert-danger alert-dismissable" style="padding: 5px">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <strong>{{ $error }}!</strong>
            </div>
            @endif
            <form class="form-horizontal mt0" method="post" action="{{ url('login') }}" id=login-form role=form>
                {!! csrf_field() !!}
                <div class=form-group>
                    <div class=col-md-12>
                        <!-- col-md-12 start here -->
                        <label for="" translate="labels.username">Username:</label>
                    </div>
                    <!-- col-md-12 end here -->
                    <div class=col-lg-12>
                        <div class="input-group input-icon">
                            <input name=username id=username class=form-control  placeholder="Enter username ..."> <span class=input-group-addon><i class="icomoon-icon-user s16"></i></span>
                        </div>
                    </div>
                </div>
                <div class=form-group>
                    <div class=col-md-12>
                        <!-- col-md-12 start here -->
                        <label for="" translate="labels.password">Password:</label>
                    </div>
                    <!-- col-md-12 end here -->
                    <div class=col-lg-12>
                        <div class="input-group input-icon">
                            <input type=password name=password id=password class=form-control placeholder="Your password"> <span class=input-group-addon><i class="icomoon-icon-lock s16"></i></span>
                        </div><span class="help-block text-right"><a href="lost-password.html" translate="labels.forgert_password">Forgot password ?</a></span>
                    </div>
                </div>
                <div class="form-group mb0">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-8">
                        <div class=checkbox-custom>
                            <input type=checkbox name=remember id=remember value=option>
                            <label for=remember translate="labels.remember_me">Remember Me</label>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-4 mb25">
                        <button class="btn btn-default pull-right" type=submit>Login</button>
                    </div>
                </div>
            </form>

        </div>
        <div class="panel-footer gray-lighter-bg">
            <h4 class=text-center><strong></strong></h4>

        </div>
    </div>
    <!-- End .panel -->
</div>
<!-- End login container -->
<div class=container>
    <div class=footer>
        <p class=text-center translate="app.copyright"></p>
    </div>
</div>
<!-- Javascripts -->
<!-- Important javascript libs(put in all pages) -->
<script src=http://code.jquery.com/jquery-2.1.1.min.js></script>
<script>
    window.jQuery || document.write('<script src="http://themes.suggelab.com/supr/assets/js/libs/jquery-2.1.1.min.js">\x3C/script>')
</script>
<script src=http://code.jquery.com/ui/1.10.4/jquery-ui.js></script>
<script>
    window.jQuery || document.write('<script src="http://themes.suggelab.com/supr/assets/js/libs/jquery-ui-1.10.4.min.js">\x3C/script>')
</script>
<!--[if lt IE 9]>
<script type="text/javascript" src="js/libs/excanvas.min.js"></script>
<script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<script type="text/javascript" src="js/libs/respond.min.js"></script>
<![endif]-->
<script src="<?php echo  asset('js/pages/login.js') ?>"></script>
<!-- Google Analytics:  -->
