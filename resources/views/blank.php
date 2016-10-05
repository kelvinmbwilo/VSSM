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
<html ng-app="vssmApp" ng-controller="mainCtrl" class='no-js' xmlns="http://www.w3.org/1999/html" manifest="cache.appcache">
<!--<![endif]-->
<html class='no-js' ng-app="vssmApp" ng-controller="mainCtrl" manifest="cache.appcache">
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
    <meta name=application-name content="VVS">
    <!-- Import google fonts - Heading first/ text second -->
<!--    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,700" rel=stylesheet type=text/css>-->
<!--    <link href="http://fonts.googleapis.com/css?family=Droid+Sans:400,700" rel=stylesheet type=text/css>-->
    <link href="<?php echo  asset('css/kendo.common-material.min.css')  ?>" rel="stylesheet">
    <link href="<?php echo  asset('css/angular-multi-select.css')  ?>" rel="stylesheet">
    <link href="<?php echo  asset('bower_components/angular-material/angular-material.min.css')  ?>" rel="stylesheet">
    <link href="<?php echo  asset('bower_components/angular-multi-select/doc/css/isteven-multi-select.css')  ?>" rel="stylesheet">
    <link href="<?php echo  asset('bower_components/TableTools-master/css/dataTables.tableTools.css')  ?>" rel="stylesheet">
    <link href="<?php echo  asset('jqueryui/css/cupertino/jquery-ui-1.10.4.custom.min.css')  ?>" rel="stylesheet">
    <!-- Css files -->
    <link rel=stylesheet href="<?php echo  asset('css/abn_tree.css') ?>">
    <link rel=stylesheet href="<?php echo  asset('css/main.min.css') ?>">
    <link rel=stylesheet href="<?php echo  asset('bower_components/angular-datatables/dist/plugins/bootstrap/datatables.bootstrap.min.css') ?>">

    <link rel=stylesheet href="<?php echo  asset('barcodeGenerator/barcode.css') ?>">
    <!-- Fav and touch icons -->
    <link rel=icon href="<?php echo  asset('favicon.ico') ?>" type=image/png>
    <!-- Windows8 touch icon ( http://www.buildmypinnedsite.com/ )-->
    <meta name="Vaccines and Supplies Stock Management" content="#3399cc">
    <style>
        #sidebar .sidenav .mainnav ul li a.mymenu {
            position: relative;
            /* font-size: 14px; */
            height: 50px !important;
            float: left;
            width: 100%;
            line-height: 18px !important;
            border-bottom: 1px solid #c4c4c4;
            border-top: 1px solid #fff;
            box-shadow: 0 1px 0 #fff;
            color: #3f3f3f;
            box-sizing: border-box;
            margin-left: 5px;
        }
    </style>
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
            <span style="text-align: center;font-size: 15px;padding-top: 120px" ng-cloak> {{ logedInUser.recipient.name }}</span>

            <ul  class="nav navbar-right" style="margin-top: 4px">
                <li><img class="img-rounded" src="<?php echo asset('img/logo.jpg') ?>" style="width: 70px; height: 60px"></li>
            </ul>

            <ul class="nav navbar-right usernav">
                <li class=dropdown>
                    <a href="#" class=dropdown-toggle data-toggle=dropdown><i class="s16 icomoon-icon-warning text-warning"></i><span class=txt translate="menu.warnings">Warnings <b class=caret></b></span><span class=notification ng-cloak>{{ number_of_notification }}</span></a>
                    <ul class="dropdown-menu right">
                        <li class=menu>
                            <ul class=notif>
                                <li class=header><strong translate="labels.Notifications"></strong ng-cloak> ({{ number_of_notification }}) items</li>
                                <li ng-repeat="item in notification_object">
                                    <a href="./#{{ item.url }}">
                                        <span class=icon><i class="s16 icomoon-icon-calendar"></i></span>
                                        <span class=event>
                                            <span ng-if="item.type == 'near_expired_item'"><span translate="labels.near_expired_item"></span> : {{ item.descr }} <span translate="labels.of_batch_number"></span> {{ item.lot_number }} <span translate="labels.will_expire_at"></span> {{ item.expired_date }} </span>
                                            <span ng-if="item.type == 'above_maximum_value'"><span translate="labels.above_maximum_value"> </span>: {{ item.descr }} <span translate="labels.is_above_maximum_settled_value"></span> <span translate="current_number_of_dose_is"></span> {{ item.max_val }} </span>
                                            <span ng-if="item.type == 'item_below_minimum'"><span translate="labels.item_below_minimum"></span> : {{ item.descr }}<span translate="labels.is_below_minimum_settled_value"></span> <span translate="current_number_of_dose_is"></span> {{ item.amount }} <span translate="labels.and_has_minimum_of"></span> {{ item.min_value  }} </span>
                                            <span ng-if="item.type == 'expired_item'"><span translate="labels.expired_item"></span> : {{ item.descr }} <span translate="labels.of_batch_number"></span> {{ item.lot }} <span translate="labels.has_expired_since"></span> {{ item.expired_date }} </span>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class=dropdown>
                    <a href="blank.html#" class="dropdown-toggle avatar" data-toggle=dropdown><i class="fa fa-user"></i> <span class=txt ng-cloak>{{ logedInUserName }}</span> <b class=caret></b></a>
                    <ul class="dropdown-menu right">
                        <li class=menu style="min-width: 200px;">
                            <ul>
                                <li><a href="./#change_pass"><i class="s16 icomoon-icon-user-plus" translate="menu.reset_password"></i></a></li>
<!--                                <li><a href="change_pass"><i class="s16 icomoon-icon-lock" translate="menu.reset_password"></i></a></li>-->
                                <li><a href="./index.php/logout"><i class="s16 fa fa-sign-out" translate="menu.logout"></i></a></li>
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
                    <li ng-if="hasRole(userRoles,'see_dashboard')"><a href="./#home" class="dashboard11"><i class="s16 icomoon-icon-screen-2"></i><span class=txt translate="menu.dashboard"></span></a></li>
                    <li>
                        <a href="./#alarms" ng-class="{ active1: isActive('/alarms') }"><i class="s16 icomoon-icon-warning"></i><span class=txt translate="menu.warnings"></span><span class=notification ng-cloak>{{ number_of_notification }}</span></a>
                        <ul class=sub>
                            <li ng-if="hasRole(userRoles,'see_closetoexpiry')">
                                <a href="./#close_to_expiry" ng-class="{ active1: isActive('/close_to_expiry') }" class="mymenu">
                                        <i class="icomoon-icon-arrow-right-3"></i>
                                        <span class=txt translate="menu.close_to_expiry"></span>
                                </a>
                            </li>
                            <li ng-if="hasRole(userRoles,'see_belowminimum')">
                                <a href="./#below_minimum" ng-class="{ active1: isActive('/below_minimum') }" class="mymenu">
                                    <i class="icomoon-icon-arrow-right-3"></i>
                                    <span class=txt translate="menu.below_minimum"></span>
                                </a>
                            </li>
                           <li ng-if="hasRole(userRoles,'see_aboveminimum')">
                               <a href="./#above_maximum" ng-class="{ active1: isActive('/above_maximum') }" class="mymenu">
                                   <i class="icomoon-icon-arrow-right-3"></i>
                                   <span class=txt translate="menu.above_maximum"></span>
                               </a>
                           </li>
                        </ul>
                    </li>
                    <li>
                        <a href="blank.html#"  ng-class="{ active: isActive('/receive') || isActive('/receive_pre') }"><i class="s16 icomoon-icon-cart-add"></i><span class=txt translate="menu.arrival"></span></a>
                        <ul class=sub>
                            <li ng-if="userRecipientLevel == 1 && hasRole(userRoles,'see_vaccinediluent')"><a href="./#receive" ng-class="{ active1: isActive('/receive') }" class="mymenu">
                                <i class="icomoon-icon-arrow-right-3"></i>
                                <span class=txt translate="menu.vaccine_diluent"></span></a></li>
                            <li ng-if="userRecipientLevel != 1 && hasRole(userRoles,'see_vaccinediluent')"><a href="./#receive_other" ng-class="{ active1: isActive('/receive_other') }" class="mymenu">
                                <i class="icomoon-icon-arrow-right-3"></i>
                                <span class=txt translate="menu.vaccine_diluent"></span></a></li>
                            <li ng-if="userRecipientLevel == 1 && hasRole(userRoles,'see_preadvice')"><a href="./#receive_pre" ng-class="{ active1: isActive('/receive_pre') }" class="mymenu"><i class="icomoon-icon-arrow-right-3"></i>
                                <span class=txt translate="menu.from_pre_advice"></span></a></li>
<!--                            <li><a href="./#open_stock" ng-class="{ active1: isActive('/open_stock') }"><i class="s16 icomoon-icon-arrow-right-3"></i><span class=txt translate="menu.open_stock"></span></a></li>-->
<!--                             <li><a href="charts-other.html"><i class="s16 icomoon-icon-arrow-right-3"></i><span class=txt>Other charts</span></a></li>-->
                        </ul>
                    </li>
                    <li>
                        <a href="blank.html#"><i class="s16 icomoon-icon-cart-remove"></i><span class=txt translate="menu.dispatch"></span></a>
                        <ul class=sub>
                            <li ng-if="hasRole(userRoles,'see_dispach')" ><a href="./#dispatch"  ng-class="{ active1: isActive('/dispatch') }" class="mymenu">
                                <i class="icomoon-icon-arrow-right-3"></i>
                                <span class=txt translate="menu.dispatch"></span></a></li>
<!--                            <li ng-if="userRecipientLevel == 3 && hasRole(userRoles,'see_dispach')" ><a href="./#dispatch_mark"  ng-class="{ active1: isActive('/dispatch') }"><i class="s16 icomoon-icon-arrow-right-3"></i><span class=txt translate="menu.dispatch"></span></a></li>-->
<!--                            <li><a href="./#requests" ng-class="{ active1: isActive('/requests') }"><i class="s16 icomoon-icon-arrow-right-3"></i><span class=txt translate="menu.requests"></span></a></li>-->
                        </ul>
                    </li>
                    <li>
                        <a href="blank.html#"><i class="s16 icomoon-icon-tab"></i><span class=txt translate="menu.summary"></span></a>
                        <ul class=sub>
                            <li ng-if="hasRole(userRoles,'see_expectedpackages')"><a href="./#expected_packages" ng-class="{ active1: isActive('/expected_packages') }" class="mymenu">
                                <i class="icomoon-icon-arrow-right-3"></i>
                                <span class=txt translate="menu.expected_packages"></span></a></li>
                            <li ng-if="hasRole(userRoles,'see_arrivalsummary')" ><a href="./#arrivals_summary" ng-class="{ active1: isActive('/arrivals_summary') }" class="mymenu">
                                <i class="icomoon-icon-arrow-right-3"></i>
                                <span class=txt translate="menu.arrivals"></span></a></li>
                            <li ng-if="hasRole(userRoles,'see_dispatchedpackage')" ><a href="./#dispatched_packeges" ng-class="{ active1: isActive('/dispatched_packeges') }" class="mymenu">
                                <i class="icomoon-icon-arrow-right-3"></i>
                                <span class=txt translate="menu.dispatched_packaged"></span></a></li>
                            <li ng-if="hasRole(userRoles,'see_stockitems')" ><a href="./#stock_items" ng-class="{ active1: isActive('/stock_items') }" class="mymenu">
                                <i class="icomoon-icon-arrow-right-3"></i>
                                <span class=txt translate="menu.stock_items"></span></a></li>                            
                        </ul>
                    </li>
                    <li>
                        <a href="blank.html#"><i class="s16 icomoon-icon-equalizer-2"></i><span class=txt translate="menu.adjustment">Adjustment</span></a>
                        <ul class=sub>
                            <li ng-if="hasRole(userRoles,'see_arivalcancellation')" ><a href="./#arrival_adjustment" ng-class="{ active1: isActive('/arrival_adjustment') }" class="mymenu">
                                <i class="icomoon-icon-arrow-right-3"></i>
                                <span class=txt translate="menu.arrival_adjustment"></span></a></li>
                            <li ng-if="hasRole(userRoles,'see_dispatchadjustment')" ><a href="./#dispatch_adjustment" ng-class="{ active1: isActive('/dispatch_adjustment') }" class="mymenu">
                                <i class="icomoon-icon-arrow-right-3"></i>
                                <span class=txt translate="menu.dispatch_adjustment"></span></a></li>
                            <li ng-if="hasRole(userRoles,'see_stockadjustment')" ><a href="./#stock_adjustment" ng-class="{ active1: isActive('/stock_adjustment') }" class="mymenu">
                                <i class="icomoon-icon-arrow-right-3"></i>
                                <span class=txt translate="menu.stock_adjustment"></span></a></li>
                            <li ng-if="hasRole(userRoles,'see_moveitems')" ><a href="./#move_items" ng-class="{ active1: isActive('/move_items') }" class="mymenu">
                            <i class="icomoon-icon-arrow-right-3"></i>
                            <span class=txt translate="menu.move_items"></span></a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="blank.html#"><i class="s16 icomoon-icon-stats-up"></i><span class=txt translate="menu.reports"></span></a>
                        <ul class=sub>
                             <li ng-if="hasRole(userRoles,'see_arrivalreport')" ><a href="./#monthly_arrivals"  ng-class="{ active1: isActive('/monthly_arrivals') }" class="mymenu">
                                <i class="icomoon-icon-arrow-right-3"></i>
                                <span class=txt translate="menu.monthly_arrivals"></span></a></li>
                            <li ng-if="hasRole(userRoles,'see_dispatchreprt')" ><a href="./#dispatch_report"  ng-class="{ active1: isActive('/dispatch_report') }" class="mymenu">
                                <i class="icomoon-icon-arrow-right-3"></i>
                                <span class=txt translate="menu.monthly_dispatch"></span></a></li>
                            <li ng-if="hasRole(userRoles,'see_itemmovedreport')"><a href="./#stock_status" ng-class="{ active1: isActive('/stock_status') }" class="mymenu">
                                <i class="icomoon-icon-arrow-right-3"></i>
                                <span class=txt translate="menu.stock_items"></span></a></li>
                            <li ng-if="hasRole(userRoles,'see_intransityinvertory')"><a href="./#transit_invoice" ng-class="{ active1: isActive('/transit_invoice') }" class="mymenu">
                                <i class="icomoon-icon-arrow-right-3"></i>
                                <span class=txt translate="menu.transit_invoice"></span></a></li>
                            <li ng-if="hasRole(userRoles,'see_canceledinvoice')" ><a href="./#canceled_invoices" ng-class="{ active1: isActive('/canceled_invoices') }" class="mymenu">
                                <i class="icomoon-icon-arrow-right-3"></i>
                                <span class=txt translate="menu.canceled_invoices"></span></a></li>
                            <li ng-if="hasRole(userRoles,'see_adjustmentreport')"><a href="./#adjustmentreport" ng-class="{ active1: isActive('/adjustmentreport') }" class="mymenu">
                                <i class="icomoon-icon-arrow-right-3"></i>
                                <span class=txt translate="menu.adjustmentreport"></span></a></li>
                            <li ng-if="hasRole(userRoles,'see_itemmovedreport')"><a href="./#itemmovedreport" ng-class="{ active1: isActive('/itemmovedreport') }" class="mymenu">
                                <i class="icomoon-icon-arrow-right-3"></i>
                                <span class=txt translate="menu.itemmovedreport"></span></a></li>
                            <li ng-if="hasRole(userRoles,'see_expireditems')" ><a href="./#expired_items"  ng-class="{ active1: isActive('/expired_items') }" class="mymenu">
                                <i class="icomoon-icon-arrow-right-3"></i>
                                <span class=txt translate="menu.expired_items"></span> <span class="badge badge-info">{{ number_expired_items }}</span></a></li>
                        </ul>
                    </li>
                    <li ng-if="hasRole(userRoles,'see_stockopening')" ><a href="./#open_stock" ng-class="{ active1: isActive('/open_stock') }"><i class="s16 icomoon-icon-folder-open-2"></i><span class=txt translate="menu.open_stock"></span></a></li>
                    <li ng-if="hasRole(userRoles,'see_preadvicenotice')" ><a href="./#pre_advice" ng-class="{ active1: isActive('/pre_advice') }"><i class="s16 icomoon-icon-alarm"></i><span class=txt translate="menu.pre_advice"></span></a></li>
                    <li>
                        <a href="blank.html#"><i class="s16 icomoon-icon-table-2"></i><span class=txt translate="menu.basic_data">Basic Data</span></a>
                        <ul class=sub>
                            <li ng-if="hasRole(userRoles,'see_vaccine')" ><a href="./#vaccine_diluent" ng-class="{ active1: isActive('/vaccine_diluent') }" class="mymenu">
                                <i class="icomoon-icon-arrow-right-3"></i>
                                <span class=txt translate="menu.vaccine_diluent"></span></a></li>
                            <li ng-if="hasRole(userRoles,'see_packaging')" ><a href="./#packaging" ng-class="{ active1: isActive('/packaging') }" class="mymenu">
                            <i class="icomoon-icon-arrow-right-3"></i><span class=txt translate="menu.GTIN_lookup"></span></a></li>
                            <li ng-if="hasRole(userRoles,'see_itemsmaxmin')" ><a href="./#item_min_max" ng-class="{ active1: isActive('/item_min_max') }" class="mymenu">
                                <i class="icomoon-icon-arrow-right-3"></i>
                                <span class=txt translate="menu.items_max_min"></span></a></li>
                            <li ng-if="hasRole(userRoles,'see_sources')" ><a href="./#sources" ng-class="{ active1: isActive('/sources') }" class="mymenu">
                                <i class="icomoon-icon-arrow-right-3"></i>
                                <span class=txt translate="menu.sources"></span></a></li>
                            <li ng-if="hasRole(userRoles,'see_stores')" ><a href="./#stores" ng-class="{ active1: isActive('/stores') }" class="mymenu">
                                <i class="icomoon-icon-arrow-right-3"></i>
                                <span class=txt translate="menu.stores"></span></a></li>
                            <li ng-if="hasRole(userRoles,'see_transportmode')" ><a href="./#transport_modes" ng-class="{ active1: isActive('/transport_modes') }" class="mymenu">
                                <i class="icomoon-icon-arrow-right-3"></i>
                                <span class=txt translate="menu.transport_mode"></span></a></li>
                            <li ng-if="hasRole(userRoles,'see_adjustmentreason')" ><a href="./#adjustment_reason" ng-class="{ active1: isActive('/adjustment_reason') }" class="mymenu">
                                <i class="icomoon-icon-arrow-right-3"></i>
                                <span class=txt translate="menu.adjustment_reasons"></span></a></li>
                            <li ng-if="hasRole(userRoles,'see_activities')" ><a href="./#activities" ng-class="{ active1: isActive('/activities') }" class="mymenu">
                                <i class="icomoon-icon-arrow-right-3"></i>
                                <span class=txt translate="menu.activities"></span></a></li>
                            <li ng-if="hasRole(userRoles,'see_manufactures')" ><a href="./#manufactures" ng-class="{ active1: isActive('/manufactures') }" class="mymenu">
                                <i class="icomoon-icon-arrow-right-3"></i>
                                <span class=txt translate="menu.manufactures"></span></a></li>
                            <li ng-if="hasRole(userRoles,'see_annualqouta')" ><a href="./#annual_quota" ng-class="{ active1: isActive('/annual_quota') }" class="mymenu">
                                <i class="icomoon-icon-arrow-right-3"></i>
                                <span class=txt translate="menu.annual_quota"></span></a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="blank.html#" ng-class="{ active: isActive('/recipient') || isActive('/recipient_level') }">
                            <i class="s16 icomoon-icon-location-3"></i>
                            <span class=txt translate="menu.recipients"></span></a>
                        <ul class=sub>
                            <li ng-if="hasRole(userRoles,'see_basicrecipients')" ><a href="./#recipients" class="mymenu">
                                <i class="icomoon-icon-arrow-right-3"></i>
                                <span class=txt translate="menu.recipients"></span></a></li>
                            <li ng-if="hasRole(userRoles,'see_recipients')" ><a href="./#recipient" ng-class="{ active1: isActive('/recipient') }" class="mymenu">
                                <i class="icomoon-icon-arrow-right-3"></i>
                                <span class=txt translate="menu.recipients_tree"></span></a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="blank.html#" ng-class="{ active: isActive('/users') || isActive('/user_roles') }"><i class="s16 icomoon-icon-users-2"></i><span class=txt translate="menu.user_management"></span></a>
                        <ul class=sub>
                            <li ng-if="hasRole(userRoles,'see_users')" ><a href="./#users" ng-class="{ active1: isActive('/users') }" class="mymenu">
                                <i class="icomoon-icon-arrow-right-3"></i>
                                <span class=txt translate="menu.users"></span></a></li>
                            <li ng-if="hasRole(userRoles,'see_userroles')" ><a href="./#user_roles" ng-class="{ active1: isActive('/user_roles') }" class="mymenu">
                                <i class="icomoon-icon-arrow-right-3"></i>
                                <span class=txt translate="menu.user_roles"></span></a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="blank.html#" ng-class="{ active: isActive('/system_settings') }"><i class="s16 icomoon-icon-cog-2"></i><span class=txt translate="menu.configuration"></span></a>
                        <ul class=sub>
                            <li ng-if="hasRole(userRoles,'see_configuration')" ><a href="./#system_settings" ng-class="{ active1: isActive('/system_settings') }" class="mymenu" >
                                <i class="icomoon-icon-arrow-right-3"></i>
                                <span class=txt translate="menu.system_settings"></span></a></li>
                            <li ng-if="hasRole(userRoles,'see_recipientslevels')" ><a href="./#recipient_level" ng-class="{ active1: isActive('/recipient_level') }" class="mymenu">
                                <i class="icomoon-icon-arrow-right-3"></i>
                                <span class=txt translate="menu.recipients_levels"></span></a></li>
                        </ul>
                    </li>

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
<script>
    window.paceOptions = {
        restartOnPushState: false,
        ajax: false, // disabled
        document: false, // disabled
        eventLag: false, // disabled
        elements: {
            selectors: ['.contentwrapper']
        }
    };
</script>
<script src="<?php echo  asset('plugins/core/pace/pace.min.js') ?>"></script>
<!-- Important javascript libs(put in all pages) -->

<script src="<?php echo  asset('js/libs/jquery-2.1.1.min.js') ?>"></script>
<script src="<?php echo  asset('jqueryui/js/jquery-ui-1.10.4.custom.js') ?>"></script>
<script src="<?php echo  asset('js/libs/jquery-migrate-1.2.1.min.js') ?>"></script>
<!--[if lt IE 9]>
<script type="text/javascript" src="<?php echo  asset('js/libs/excanvas.min.js') ?>"></script>
<script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<script type="text/javascript" src="<?php echo  asset('js/libs/respond.min.js') ?>"></script>
<script src="http://code.angularjs.org/1.0.8/i18n/angular-locale_fr-fr.js"></script>
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


<script src="<?php echo  asset('barcodeGenerator/barcodeGenerator.js')  ?>"></script>
<script src="<?php echo  asset('barcodeGenerator/pdfmake.min.js')  ?>"></script>
<script src="<?php echo  asset('barcodeGenerator/vfs_fonts.js')  ?>"></script>
<script src="<?php echo  asset('barcodeGenerator/html2canvas.js')  ?>"></script>

<script src="<?php echo asset('bower_components/highcharts-ng/src/highcharts-custom.js') ?>"></script>
<script src="<?php echo asset('bower_components/highcharts-ng/src/highcharts-ng.js') ?>"></script>
<script src="<?php echo  asset('bower_components/angular-animate/angular-animate.min.js')  ?>"></script>
<script src="<?php echo  asset('bower_components/angular-aria/angular-aria.min.js')  ?>"></script>
<script src="<?php echo  asset('bower_components/angular-material/angular-material.min.js')  ?>"></script>
<script src="<?php echo  asset('js/abn_tree_directive.js')  ?>"></script>
<script src="<?php echo  asset('js/angular-multi-select.js')  ?>"></script>
<script src="<?php echo  asset('bower_components/TableTools-master/js/dataTables.tableTools.js')  ?>"></script>
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
<script src="<?php echo  asset('bower_components/angular-multi-select/doc/js/libs/isteven-multi-select.js')  ?>"></script>
<script>
    var mainModule = angular.module('vssmApp', ["ngRoute","ui.bootstrap",'ngAnimate','ngMaterial',"datatables",'datatables.bootstrap','datatables.colvis','datatables.tabletools','pascalprecht.translate','ngSanitize','angularBootstrapNavTree','ui.date','highcharts-ng','isteven-multi-select','multi-select','barcodeGenerator']);
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
<script src="<?php echo  asset('controllers/reports/arrivalReportCtrl.js')  ?>"></script>
<script src="<?php echo  asset('controllers/reports/dispatchReportCtrl.js')  ?>"></script>
<script src="<?php echo  asset('controllers/reports/reportCtrl.js')  ?>"></script>
<script src="<?php echo  asset('controllers/reports/reportCtrl.js')  ?>"></script>
<script src="<?php echo  asset('controllers/reports/expiredItemsCtrl.js')  ?>"></script>
<script src="<?php echo  asset('controllers/reports/adjustmentReportCtrl.js')  ?>"></script>
<script src="<?php echo  asset('controllers/reports/itemMovedReportCtrl.js')  ?>"></script>
<script src="<?php echo  asset('controllers/reports/stockStatusCtrl.js')  ?>"></script>
<!--<script src="--><?php //echo  asset('bootstrap/js/bootstrap.min.js')  ?><!--"></script>-->
<script src="<?php echo  asset('js/ie10-viewport-bug-workaround.js')  ?>"></script>
<script src="<?php echo  asset('js/pages/blank.js') ?>"></script>
<script>
    $(document).ready(function(){
        $(".dashboard11").removeClass("active");
    })
</script>
<?php } ?>
