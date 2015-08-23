<!DOCTYPE html>

<!-- * Created by Kelvin Mbwilo.-->
<!-- * User: kelvin-->
<!-- * Date: 8/19/15-->
<!-- * Time: 1:45 PM-->


<html lang="en" ng-app="bahariApp" ng-controller="mainCtrl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title translate="app.tittle"></title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo  asset('bower_components/angular-material/angular-material.min.css')  ?>" rel="stylesheet">
    <link href="<?php echo  asset('bootstrap/css/bootstrap.css')  ?>" rel="stylesheet">
    <link href="<?php echo  asset('bootstrap/css/bootstrap-theme.css')  ?>" rel="stylesheet">
    <link href="<?php echo  asset('bower_components/datatables/media/css/jquery.dataTables.min.css')  ?>" rel="stylesheet">
    <link href="<?php echo  asset('bower_components/datatables/media/css/dataTables.bootstrap.min.css')  ?>" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo  asset('css/justified-nav.css')  ?>" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="<?php echo  asset('js/ie8-responsive-file-warning.js')  ?>"></script><![endif]-->
    <script src="<?php echo  asset('js/ie-emulation-modes-warning.js')  ?>"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<div class="container">

    <!-- The justified navigation menu is meant for single line per list item.
         Multiple lines will require custom code not provided by Bootstrap. -->
    <div class="masthead">
        <h3 class="text-muted">BAHARI SACCOS GROUP</h3>
        <div class="row">
            <button class="pull-right" ng-click="changeLanguage('deDE')" translate="BUTTON_TEXT_DE"></button>
            <button class="pull-right" ng-click="changeLanguage('enUS')" translate="BUTTON_TEXT_EN"></button>
        </div>
        <nav>
            <ul class="nav nav-justified">
                <li ng-class="{ active: isActive('/home') }"><a href="#home" translate="menu.home"></a></li>
                <li ng-class="{ active: isActive('/group') }"><a href="#group" translate="menu.group"></a></li>
            </ul>
        </nav>
    </div>

    <div ng-view style="min-height: 400px"></div>

    <!-- Site footer -->
    <footer class="footer">
        <p>&copy; Company 2014</p>
    </footer>

</div> <!-- /container -->


<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="<?php echo  asset('js/jquery.min.js')  ?>"></script>
<script src="<?php echo  asset('bower_components/datatables/media/js/jquery.dataTables.js')  ?>"></script>
<script src="<?php echo  asset('bower_components/angular/angular.min.js')  ?>"></script>
<script src="<?php echo  asset('bower_components/angular-bootstrap/ui-bootstrap.min.js')  ?>"></script>
<script src="<?php echo  asset('js/angular-route.js')  ?>"></script>
<script src="<?php echo  asset('js/angular-resource.js')  ?>"></script>
<script src="<?php echo  asset('bower_components/angular-animate/angular-animate.min.js')  ?>"></script>
<script src="<?php echo  asset('bower_components/angular-aria/angular-aria.min.js')  ?>"></script>
<script src="<?php echo  asset('bower_components/angular-material/angular-material.min.js')  ?>"></script>
<script src="<?php echo  asset('bower_components/angular-datatables/dist/angular-datatables.min.js')  ?>"></script>
<script src="<?php echo  asset('bower_components/angular-translate/angular-translate.min.js')  ?>"></script>
<script src="<?php echo  asset('bower_components/angular-translate-loader-static-files/angular-translate-loader-static-files.min.js')  ?>"></script>
<script src="<?php echo  asset('bower_components/angular-translate-loader-url/angular-translate-loader-url.min.js')  ?>"></script>
<script src="<?php echo  asset('bower_components/angular-sanitize/angular-sanitize.min.js')  ?>"></script>
<script>
    var mainModule = angular.module('bahariApp', ["ngRoute","ui.bootstrap",'ngAnimate','ngMaterial',"datatables",'pascalprecht.translate','ngSanitize']);
</script>
<script src="<?php echo  asset('js/routes.js')  ?>"></script>
<script src="<?php echo  asset('controllers/homeCtrl.js')  ?>"></script>
<script src="<?php echo  asset('controllers/groupCtrl.js')  ?>"></script>
<script src="<?php echo  asset('bootstrap/js/bootstrap.min.js')  ?>"></script>
<script src="<?php echo  asset('js/ie10-viewport-bug-workaround.js')  ?>"></script>
</body>
</html>
