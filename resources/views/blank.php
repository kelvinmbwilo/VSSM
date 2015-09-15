<?php
if(Auth::guest()){

    echo \Illuminate\Support\Facades\Redirect::to("login");
}
else{
?>
<!DOCTYPE html><!--[if lt IE 8]>
<meta name="csrf-token" content="<?php echo csrf_token() ?>" />
<html class="no-js lt-ie8" ng-app="bahariApp" ng-controller="mainCtrl">
<![endif]--><!--[if gt IE 8]><!-->
<html class='no-js' xmlns="http://www.w3.org/1999/html">
<!--<![endif]-->
<html class='no-js' ng-app="vssmApp" ng-controller="mainCtrl">
<head>
    <meta charset=utf-8>
    <title translate="app.tittle"></title>
    <!-- Mobile specific metas -->
    <meta name=viewport content="width=device-width,initial-scale=1,maximum-scale=1">
    <!-- Force IE9 to render in normal mode --><!--[if IE]>
    <meta http-equiv="x-ua-compatible" content="IE=9" />
    <![endif]-->
    <meta name=author content="Kelvi Mbwilo">
    <meta name=description content="Vaccines and Supplies Stock Management">
    <meta name=keywords content="Vaccine,Supply,Stock">
    <meta name=application-name content="VSSM">
    <!-- Import google fonts - Heading first/ text second -->
<!--    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,700" rel=stylesheet type=text/css>-->
<!--    <link href="http://fonts.googleapis.com/css?family=Droid+Sans:400,700" rel=stylesheet type=text/css>-->
    <link href="<?php echo  asset('css/kendo.common-material.min.css')  ?>" rel="stylesheet">
    <link href="<?php echo  asset('bower_components/angular-material/angular-material.min.css')  ?>" rel="stylesheet">
    <!-- Css files -->
    <link rel=stylesheet href="<?php echo  asset('css/abn_tree.css') ?>">
    <link rel=stylesheet href="<?php echo  asset('css/main.min.css') ?>">
    <link rel=stylesheet href="<?php echo  asset('bower_components/angular-datatables/dist/plugins/bootstrap/datatables.bootstrap.min.css') ?>">

    <!--    <link rel=stylesheet href="--><?php //echo  asset('css/pace.css') ?><!--">-->
    <!-- Fav and touch icons -->
    <link rel=icon href="<?php echo  asset('favicon.ico') ?>" type=image/png>
    <!-- Windows8 touch icon ( http://www.buildmypinnedsite.com/ )-->
    <meta name="Vaccines and Supplies Stock Management" content="#3399cc">

<body>
<!--<div id="load-screen" ng-if="showLoader"></div>-->
<!--[if lt IE 9]>
<p class="browsehappy" translate="error.oldBrowserError">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]--><!-- .#header -->
<div id=header>

    <nav class="navbar navbar-default" role=navigation>
        <div class=navbar-header style="margin-top: 4px"><a class=navbar-brand href="./#dashboard">
                <img class="img-rounded" src="<?php echo asset('img/logo1.jpg') ?>" style="width: 70px; height: 60px">
        </a></div>
        <div id=navbar-no-collapse class=navbar-no-collapse>
            <ul class="nav navbar-nav">
                <li>
                    <!--Sidebar collapse button--><a href="blank.html#" class="collapseBtn leftbar"><i class="s16 minia-icon-list-3"></i></a>
                </li>

            </ul>
            <span class="text-center hidden-sm hidden-xs" style="text-align: center;font-size: 29px;margin-top: 120px;margin-left: 19%" translate="app.title"> </span>
            <span style="text-align: center;font-size: 15px;padding-top: 120px"> {{ logedInUser.recipient.name }}</span>

            <ul  class="nav navbar-right" style="margin-top: 4px">
                <li><img class="img-rounded" src="<?php echo asset('img/logo.jpg') ?>" style="width: 70px; height: 60px"></li>
            </ul>

            <ul class="nav navbar-right usernav">
                <li class=dropdown>
                    <a href="#" class=dropdown-toggle data-toggle=dropdown><i class="s16 icomoon-icon-warning text-warning"></i><span class=txt translate="menu.warnings">Warnings <b class=caret></b></span><span class=notification>8</span></a>
                    <ul class="dropdown-menu right">
                        <li class=menu>
                            <ul class=notif>
                                <li class=header><strong>Notifications</strong> (3) items</li>
                                <li><a href="blank.html#"><span class=icon><i class="s16 icomoon-icon-user-plus"></i></span> <span class=event>1 User is registred</span></a></li>
                                <li><a href="blank.html#"><span class=icon><i class="s16 icomoon-icon-bubble-3"></i></span> <span class=event>Jony add 1 comment</span></a></li>
                                <li><a href="blank.html#"><span class=icon><i class="s16 icomoon-icon-new"></i></span> <span class=event>admin Julia added post with a long description</span></a></li>
                                <li class=view-all><a href="blank.html#">View all notifications <i class="s16 fa fa-angle-double-right"></i></a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class=dropdown>
                    <a href="blank.html#" class="dropdown-toggle avatar" data-toggle=dropdown><i class="fa fa-user"></i> <span class=txt>{{ logedInUserName }}</span> <b class=caret></b></a>
                    <ul class="dropdown-menu right">
                        <li class=menu style="min-width: 200px;">
                            <ul>
                                <li><a href="blank.html#"><i class="s16 icomoon-icon-user-plus" translate="menu.edit_profile"></i></a></li>
                                <li><a href="blank.html#"><i class="s16 icomoon-icon-lock" translate="menu.reset_password"></i></a></li>
                                <li><a href="<?php echo url('logout') ?>"><i class="s16 fa fa-sign-out" translate="menu.logout"></i></a></li>
                                <li class="seperator"></li>
                                <li>
                                    <button class="btn-info pull-left" ng-click="changeLanguage('enUS')"><img style="height: 16px;width: 16px" src="<?php echo asset('img/en.png') ?>"> En</button>
                                    <button class="btn-info pull-right" ng-click="changeLanguage('deDE')"> <img style="height: 16px;width: 16px" src="<?php echo asset('img/sp.jpg') ?>">SP </button> </li>
                                
                            </ul>
                        </li>
                    </ul>
                </li>
<!--                <li><a href="login.html"><i class="s16 icomoon-icon-exit"></i><span class=txt>Logout</span></a></li>-->

            </ul>
        </div>
        <!-- /.nav-collapse -->
    </nav>
    <!-- /navbar -->
</div>
<!-- / #header -->
<div id=wrapper>
<!-- #wrapper --><!--Sidebar background-->
<div id=sidebarbg class="hidden-lg hidden-md hidden-sm hidden-xs"></div>
<!--Sidebar content-->
<div id=sidebar style="margin-top: 20px;padding-top: 0px" class="page-sidebar hidden-lg hidden-md hidden-sm hidden-xs">

<!-- End search --><!-- Start .sidebar-inner -->
<div class=sidebar-inner>
    <!-- Start .sidebar-scrollarea -->
    <div class=sidebar-scrollarea>
        <div class=sidenav>
            <div class="sidebar-widget mb0">
                <h6 class="title mb0" translate="labels.navigation"></h6>
            </div>
            <!-- End .sidenav-widget -->
            <div class=mainnav>
                <ul>
                    <li><a href="./#home" class="dashboard11"><i class="s16 icomoon-icon-screen-2"></i><span class=txt translate="menu.dashboard"></span></a></li>
                    <li><a href="./#alarms" ng-class="{ active1: isActive('/alarms') }"><i class="s16 icomoon-icon-warning"></i><span class=txt translate="menu.warnings"></span><span class="notification red">8</span></a></li>
                    <li>
                        <a href="blank.html#"  ng-class="{ active: isActive('/receive') || isActive('/receive_pre') }"><i class="s16 icomoon-icon-cart-add"></i><span class=txt translate="menu.arrival"></span></a>
                        <ul class=sub>
                            <li ng-if="userRecipientLevel == 1"><a href="./#receive" ng-class="{ active1: isActive('/receive') }"><i class="s16 icomoon-icon-arrow-right-3"></i><span class=txt translate="menu.vaccine_diluent"></span></a></li>
                            <li ng-if="userRecipientLevel != 1"><a href="./#receive_other" ng-class="{ active1: isActive('/receive_other') }"><i class="s16 icomoon-icon-arrow-right-3"></i><span class=txt translate="menu.vaccine_diluent"></span></a></li>
                            <li ng-if="userRecipientLevel == 1"><a href="./#receive_pre" ng-class="{ active1: isActive('/receive_pre') }"><i class="s16 icomoon-icon-arrow-right-3"></i><span class=txt translate="menu.from_pre_advice"></span></a></li>
<!--                            <li><a href="./#open_stock" ng-class="{ active1: isActive('/open_stock') }"><i class="s16 icomoon-icon-arrow-right-3"></i><span class=txt translate="menu.open_stock"></span></a></li>-->
<!--                             <li><a href="charts-other.html"><i class="s16 icomoon-icon-arrow-right-3"></i><span class=txt>Other charts</span></a></li>-->
                        </ul>
                    </li>
                    <li>
                        <a href="blank.html#"><i class="s16 icomoon-icon-cart-remove"></i><span class=txt translate="menu.dispatch"></span></a>
                        <ul class=sub>
                            <li><a href="./#dispatch"  ng-class="{ active1: isActive('/dispatch') }"><i class="s16 icomoon-icon-arrow-right-3"></i><span class=txt translate="menu.dispatch"></span></a></li>
<!--                            <li><a href="./#requests" ng-class="{ active1: isActive('/requests') }"><i class="s16 icomoon-icon-arrow-right-3"></i><span class=txt translate="menu.requests"></span></a></li>-->
                        </ul>
                    </li>
                    <li>
                        <a href="blank.html#"><i class="s16 icomoon-icon-tab"></i><span class=txt translate="menu.summary"></span></a>
                        <ul class=sub>
                            <li><a href="./#expected_packages"><i class="s16 icomoon-icon-arrow-right-3"></i><span class=txt translate="menu.expected_packages"></span></a></li>
                            <li><a href="./#dispatched_packeges"><i class="s16 icomoon-icon-arrow-right-3"></i><span class=txt translate="menu.dispatched_packaged"></span></a></li>
                            <li><a href="./#stock_items"><i class="s16 icomoon-icon-arrow-right-3"></i><span class=txt translate="menu.stock_items"></span></a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="blank.html#"><i class="s16 icomoon-icon-equalizer-2"></i><span class=txt translate="menu.adjustment">Adjustment</span></a>
                        <ul class=sub>
                            <li><a href="./#arrival_adjustment" ng-class="{ active1: isActive('/arrival_adjustment') }"><i class="s16 icomoon-icon-arrow-right-3"></i><span class=txt translate="menu.arrival_adjustment"></span></a></li>
                            <li><a href="./#dispatch_adjustment" ng-class="{ active1: isActive('/dispatch_adjustment') }"><i class="s16 icomoon-icon-arrow-right-3"></i><span class=txt translate="menu.dispatch_adjustment"></span></a></li>
                            <li><a href="./#stock_adjustment" ng-class="{ active1: isActive('/stock_adjustment') }"><i class="s16 icomoon-icon-arrow-right-3"></i><span class=txt translate="menu.stock_adjustment"></span></a></li>
                            <li><a href="./#move_items" ng-class="{ active1: isActive('/move_items') }"><i class="s16 icomoon-icon-arrow-right-3"></i><span class=txt translate="menu.move_items"></span></a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="blank.html#"><i class="s16 icomoon-icon-stats-up"></i><span class=txt translate="menu.reports"></span></a>
                        <ul class=sub>
                            <li><a href="./#"><i class="s16 icomoon-icon-arrow-right-3"></i><span class=txt translate="menu.sample_report"></span></a></li>
                            <li><a href="./#"><i class="s16 icomoon-icon-arrow-right-3"></i><span class=txt translate="menu.sample_report"></span></a></li>
                            <li><a href="./#"><i class="s16 icomoon-icon-arrow-right-3"></i><span class=txt translate="menu.sample_report"></span></a></li>
                            <li><a href="./#"><i class="s16 icomoon-icon-arrow-right-3"></i><span class=txt translate="menu.sample_report"></span></a></li>
                        </ul>
                    </li>
                    <li><a href="./#open_stock" ng-class="{ active1: isActive('/open_stock') }"><i class="s16 icomoon-icon-folder-open-2"></i><span class=txt translate="menu.open_stock"></span></a></li>
                    <li><a href="./#pre_advice" ng-class="{ active1: isActive('/pre_advice') }"><i class="s16 icomoon-icon-alarm"></i><span class=txt translate="menu.pre_advice"></span></a></li>
                    <li>
                        <a href="blank.html#"><i class="s16 icomoon-icon-table-2"></i><span class=txt translate="menu.basic_data">Basic Data</span></a>
                        <ul class=sub>
                            <li><a href="./#vaccine_diluent" ng-class="{ active1: isActive('/vaccine_diluent') }"><i class="s16 icomoon-icon-arrow-right-3"></i><span class=txt translate="menu.vaccine_diluent"></span></a></li>
                            <li><a href="./#packaging" ng-class="{ active1: isActive('/packaging') }"><i class="s16 icomoon-icon-arrow-right-3"></i><span class=txt translate="menu.GTIN_lookup"></span></a></li>
                            <li><a href="./#item_min_max" ng-class="{ active1: isActive('/item_min_max') }"><i class="s16 icomoon-icon-arrow-right-3"></i><span class=txt translate="menu.items_max_min"></span></a></li>
                            <li><a href="./#sources" ng-class="{ active1: isActive('/sources') }"><i class="s16 icomoon-icon-arrow-right-3"></i><span class=txt translate="menu.sources"></span></a></li>
                            <li><a href="./#stores" ng-class="{ active1: isActive('/stores') }"><i class="s16 icomoon-icon-arrow-right-3"></i><span class=txt translate="menu.stores"></span></a></li>
                            <li><a href="./#transport_modes" ng-class="{ active1: isActive('/transport_modes') }"><i class="s16 icomoon-icon-arrow-right-3"></i><span class=txt translate="menu.transport_mode"></span></a></li>
                            <li><a href="./#adjustment_reason" ng-class="{ active1: isActive('/adjustment_reason') }"><i class="s16 icomoon-icon-arrow-right-3"></i><span class=txt translate="menu.adjustment_reasons"></span></a></li>
                            <li><a href="./#activities" ng-class="{ active1: isActive('/activities') }"><i class="s16 icomoon-icon-arrow-right-3"></i><span class=txt translate="menu.activities"></span></a></li>
                            <li><a href="./#manufactures" ng-class="{ active1: isActive('/manufactures') }"><i class="s16 icomoon-icon-arrow-right-3"></i><span class=txt translate="menu.manufactures"></span></a></li>
                            <li><a href="./#"><i class="s16 icomoon-icon-arrow-right-3"></i><span class=txt translate="menu.recipients"></span></a></li>
                            <li><a href="./#annual_quota" ng-class="{ active1: isActive('/annual_quota') }"><i class="s16 icomoon-icon-arrow-right-3"></i><span class=txt translate="menu.annual_quota"></span></a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="blank.html#" ng-class="{ active: isActive('/recipient') || isActive('/recipient_level') }"><i class="s16 icomoon-icon-location-3"></i><span class=txt translate="menu.recipients"></span></a>
                        <ul class=sub>
                            <li><a href="./#recipient" ng-class="{ active1: isActive('/recipient') }"><i class="s16 icomoon-icon-arrow-right-3"></i><span class=txt translate="menu.recipients"></span></a></li>
                            <li><a href="./#recipient_level" ng-class="{ active1: isActive('/recipient_level') }"><i class="s16 icomoon-icon-arrow-right-3"></i><span class=txt translate="menu.recipients_levels"></span></a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="blank.html#" ng-class="{ active: isActive('/users') || isActive('/user_roles') }"><i class="s16 icomoon-icon-users-2"></i><span class=txt translate="menu.user_management"></span></a>
                        <ul class=sub>
                            <li><a href="./#users" ng-class="{ active1: isActive('/users') }"><i class="s16 icomoon-icon-arrow-right-3"></i><span class=txt translate="menu.users"></span></a></li>
                            <li><a href="./#user_roles" ng-class="{ active1: isActive('/user_roles') }"><i class="s16 icomoon-icon-arrow-right-3"></i><span class=txt translate="menu.user_roles"></span></a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="blank.html#"><i class="s16 icomoon-icon-cog-2"></i><span class=txt translate="menu.configuration"></span></a>
                        <ul class=sub>
                            <li><a href="./#system_settings" ng-class="{ active1: isActive('/system_settings') }" ><i class="s16 icomoon-icon-arrow-right-3"></i><span class=txt translate="menu.system_settings"></span></a></li>
                            <!--                            <li><a href="maps-vector.html"><i class="s16 icomoon-icon-arrow-right-3"></i><span class=txt>Vector maps</span></a></li>-->
                        </ul>
                    </li>
<!--                    <li><a href="widgets.html"><i class="s16 icomoon-icon-users-2"></i><span class=txt translate="menu.user_management"></span></a></li>-->

                </ul>
            </div>
        </div>

    </div>
    <!-- End .sidebar-scrollarea -->
</div>
<!-- End .sidebar-inner -->
</div>

<div id=content class="page-content clearfix" style="margin-right: 0px">

    <div ng-view></div>
    <!-- End contentwrapper -->
</div>
<!-- End #content -->
<div id=footer class="clearfix sidebar-page right-sidebar-page" style="margin-right: 0px">
    <!-- Start #footer  -->
    <p class=pull-left translate="app.copyright"></p>
    <p class=pull-right><a href="#" class=mr5><i class="s16 icomoon-icon-question"></i> <span translate="app.help"></span> </a> | <a href="#" class="ml5 mr25" translate="app.user_manual"></a></p>
</div>
<!-- End #footer  -->
</div>
<!-- / #wrapper --><!-- Back to top -->
<!-- Javascripts --><!-- Load pace first --><script src="<?php echo  asset('plugins/core/pace/pace.min.js') ?>"></script>
<!-- Important javascript libs(put in all pages) -->

<script src="<?php echo  asset('js/libs/jquery-2.1.1.min.js') ?>"></script>
<script src="<?php echo  asset('js/libs/jquery-ui-1.10.4.min.js') ?>"></script>
<script src="<?php echo  asset('js/libs/jquery-migrate-1.2.1.min.js') ?>"></script>
<!--[if lt IE 9]>
<script type="text/javascript" src="<?php echo  asset('js/libs/excanvas.min.js') ?>"></script>
<script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<script type="text/javascript" src="<?php echo  asset('js/libs/respond.min.js') ?>"></script>
<![endif]-->

<script type="text/javascript" charset="utf-8" src="<?php echo  asset('jspdf/build/pdf.js')  ?>"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo  asset('jspdf/build/pdf.worker.js')  ?>"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo  asset('jspdf/web/compatibility.js')  ?>"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo  asset('jspdf/jspdf.js')  ?>"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo  asset('jspdf/jquery.base64.js')  ?>"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo  asset('jspdf/tableExport.js')  ?>"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo  asset('jspdf/jspdf/libs/base64.js')  ?>"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo  asset('jspdf/jspdf/libs/sprintf.js')  ?>"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo  asset('jspdf/jspdf.plugin.htmltable.js')  ?>"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo  asset('jspdf/html2canvas.js')  ?>"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo  asset('jspdf/jspdf.debug.js')  ?>"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo  asset('jspdf/jspdf.plugin.addhtml.js')  ?>"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo  asset('jspdf/jspdf.plugin.cell.js')  ?>"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo  asset('jspdf/jspdf.plugin.split_text_to_size.js')  ?>"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo  asset('jspdf/jspdf.plugin.standard_fonts_metrics.js')  ?>"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo  asset('jspdf/jspdf.plugin.autotable.js')  ?>"></script>
<script src="<?php echo  asset('bower_components/datatables/media/js/jquery.dataTables.js')  ?>"></script>
<script src="<?php echo  asset('bower_components/angular/angular.min.js')  ?>"></script>
<script src="<?php echo  asset('bower_components/angular-bootstrap/ui-bootstrap.min.js')  ?>"></script>
<script src="<?php echo  asset('bower_components/angular-bootstrap/ui-bootstrap-tpls.min.js')  ?>"></script>
<script src="<?php echo  asset('js/angular-route.js')  ?>"></script>
<script src="<?php echo  asset('js/angular-resource.js')  ?>"></script>
<script src="<?php echo asset('bower_components/highcharts-ng/src/highcharts-custom.js') ?>"></script>
<script src="<?php echo asset('bower_components/highcharts-ng/src/highcharts-ng.js') ?>"></script>
<script src="<?php echo  asset('bower_components/angular-animate/angular-animate.min.js')  ?>"></script>
<script src="<?php echo  asset('bower_components/angular-aria/angular-aria.min.js')  ?>"></script>
<script src="<?php echo  asset('bower_components/angular-material/angular-material.min.js')  ?>"></script>
<script src="<?php echo  asset('js/abn_tree_directive.js')  ?>"></script>
<script src="<?php echo  asset('bower_components/angular-datatables/dist/angular-datatables.min.js')  ?>"></script>
<script src="<?php echo  asset('bower_components/angular-datatables/dist/plugins/bootstrap/angular-datatables.bootstrap.min.js')  ?>"></script>
<script src="<?php echo  asset('bower_components/angular-datatables/dist/plugins/colvis/angular-datatables.colvis.min.js')  ?>"></script>
<script src="<?php echo  asset('bower_components/angular-datatables/dist/plugins/tabletools/angular-datatables.tabletools.min.js')  ?>"></script>
<script src="<?php echo  asset('bower_components/angular-translate/angular-translate.min.js')  ?>"></script>
<script src="<?php echo  asset('bower_components/angular-translate-loader-static-files/angular-translate-loader-static-files.min.js')  ?>"></script>
<script src="<?php echo  asset('bower_components/angular-translate-loader-url/angular-translate-loader-url.min.js')  ?>"></script>
<script src="<?php echo  asset('bower_components/angular-sanitize/angular-sanitize.min.js')  ?>"></script>
<script src="<?php echo  asset('bower_components/angular-ui-date/src/date.js')  ?>"></script>
<script src="<?php echo  asset('js/kendo.all.min.js')  ?>"></script>
<script>
    var mainModule = angular.module('vssmApp', ["ngRoute","ui.bootstrap",'ngAnimate','ngMaterial',"datatables",'datatables.bootstrap','datatables.colvis','datatables.tabletools','pascalprecht.translate','ngSanitize','angularBootstrapNavTree','ui.date','kendo.directives','highcharts-ng']);
</script>
<script src="<?php echo  asset('js/routes.js')  ?>"></script>

//angularJs Controllers
<script src="<?php echo  asset('controllers/homeCtrl.js')  ?>"></script>
<script src="<?php echo  asset('controllers/groupCtrl.js')  ?>"></script>
<script src="<?php echo  asset('controllers/receiveCtrl.js')  ?>"></script>
<script src="<?php echo  asset('controllers/dispatch.js')  ?>"></script>
<script src="<?php echo  asset('controllers/openStockCtrl.js')  ?>"></script>
<script src="<?php echo  asset('controllers/summaryCtrl.js')  ?>"></script>
<script src="<?php echo  asset('controllers/preadviceReceiveCtrl.js')  ?>"></script>
<script src="<?php echo  asset('controllers/adjustmentCtrl.js')  ?>"></script>
<script src="<?php echo  asset('controllers/recipient/recipientCtrl.js')  ?>"></script>
<script src="<?php echo  asset('controllers/recipient/recipientLevelCtrl.js')  ?>"></script>
<script src="<?php echo  asset('controllers/users/userCtrl.js')  ?>"></script>
<script src="<?php echo  asset('controllers/users/userRolesCtrl.js')  ?>"></script>
<script src="<?php echo  asset('controllers/basicdata/basicDataCtrl.js')  ?>"></script>
<!--<script src="--><?php //echo  asset('bootstrap/js/bootstrap.min.js')  ?><!--"></script>-->
<script src="<?php echo  asset('js/ie10-viewport-bug-workaround.js')  ?>"></script>
<script src="<?php echo  asset('js/pages/blank.js') ?>"></script>
<script>
    $(document).ready(function(){
        $(".dashboard11").removeClass("active");
    })
</script>
<?php } ?>