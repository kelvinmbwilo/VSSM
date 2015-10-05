/**
 * Created by kelvin on 1/9/15.
 */
angular.module("vssmApp")
    .run( function($rootScope, $location) {
        // register listener to watch route changes
        $rootScope.$on( "$routeChangeStart", function(event, next, current) {
            Pace.restart()
        });
    })
    .config( function($routeProvider){
        $routeProvider.when("/home",{
            templateUrl: 'views/home.html',
            controller: 'homeCtrl'
        });

        $routeProvider.when("/group",{
            templateUrl: 'views/group.html',
            controller: 'groupCtrl'
        });

        /////////////////*****RECIPIENTS******//////////////////
        $routeProvider.when("/recipient",{
            templateUrl: 'views/recipient/recipient.html',
            controller: 'recipientCtrl'
        });

        $routeProvider.when("/recipient_level",{
            templateUrl: 'views/recipient/recipient_level.html',
            controller: 'recipientLevelCtrl'
        });

        ///////////////////*****USERS******//////////////////
        $routeProvider.when("/users",{
            templateUrl: 'views/users/users.html',
            controller: 'userCtrl'
        });

        $routeProvider.when("/user_roles",{
            templateUrl: 'views/users/user_roles.html',
            controller: 'userRolesCtrl'
        });

        $routeProvider.when("/activities",{
            templateUrl: 'views/basicdata/activities.html',
            controller: 'basicDataCtrl'
        });

         $routeProvider.when("/adjustment_reason",{
            templateUrl: 'views/basicdata/adjustmentReasons.html',
            controller: 'basicDataCtrl'
        });

         $routeProvider.when("/annual_quota",{
            templateUrl: 'views/basicdata/anualQuota.html',
            controller: 'basicDataCtrl'
        });

         $routeProvider.when("/item_min_max",{
            templateUrl: 'views/basicdata/ItemMinMax.html',
            controller: 'basicDataCtrl'
        });

         $routeProvider.when("/manufactures",{
            templateUrl: 'views/basicdata/manufactures.html',
            controller: 'basicDataCtrl'
        });

         $routeProvider.when("/packaging",{
            templateUrl: 'views/basicdata/packaging.html',
            controller: 'basicDataCtrl'
        });

         $routeProvider.when("/sources",{
            templateUrl: 'views/basicdata/sources.html',
            controller: 'basicDataCtrl'
        });

         $routeProvider.when("/stores",{
            templateUrl: 'views/basicdata/stores.html',
            controller: 'basicDataCtrl'
        });

         $routeProvider.when("/transport_modes",{
            templateUrl: 'views/basicdata/transport.html',
            controller: 'basicDataCtrl'
        });

         $routeProvider.when("/vaccine_diluent",{
            templateUrl: 'views/basicdata/vaccine.html',
            controller: 'basicDataCtrl'
        });

        $routeProvider.when("/pre_advice",{
            templateUrl: 'views/pre_advice/pre_advice.html',
            controller: 'basicDataCtrl'
        });

        $routeProvider.when("/recipients",{
            templateUrl: 'views/basicdata/recipient.html',
            controller: 'basicDataCtrl'
        });
        $routeProvider.when("/receive",{
            templateUrl: 'views/receive/receive.html',
            controller: 'receiveCtrl'
        });

        $routeProvider.when("/receive_pre",{
            templateUrl: 'views/receive/pre_advice.html',
            controller: 'preadviceReceiveCtrl'
        });
        $routeProvider.when("/receive_other",{
            templateUrl: 'views/receive/receive_other.html',
            controller: 'preadviceReceiveCtrl'
        });

        $routeProvider.when("/dispatch",{
            templateUrl: 'views/dispatch/dispatch.html',
            controller: 'dispatchCtrl'
        });

        $routeProvider.when("/open_stock",{
            templateUrl: 'views/open_stock/open_stock.html',
            controller: 'openStockCtrl'
        });

        $routeProvider.when("/system_settings",{
            templateUrl: 'views/systemSettings.html',
            controller: 'groupCtrl'
        });

        $routeProvider.when("/stock_items",{
            templateUrl: 'views/summary/stock_items.html',
            controller: 'stockCtrl'
        });

        $routeProvider.when("/dispatched_packeges",{
            templateUrl: 'views/summary/dispatched_items.html',
            controller: 'dispatchedCtrl'
        });

        $routeProvider.when("/expected_packages",{
            templateUrl: 'views/summary/expected_items.html',
            controller: 'expectedCtrl'
        });

        $routeProvider.when("/arrivals_summary",{
            templateUrl: 'views/summary/arrivals_summary.html',
            controller: 'arrivalCtrl'
        });

        $routeProvider.when("/arrival_adjustment",{
            templateUrl: 'views/adjustment/arrival_adjustment.html',
            controller: 'arrivalAdjustmentCtrl'
        });

        $routeProvider.when("/dispatch_adjustment",{
            templateUrl: 'views/adjustment/dispatch_adjustment.html',
            controller: 'dispatchAdjustmentCtrl'
        });

        $routeProvider.when("/move_items",{
            templateUrl: 'views/adjustment/move_items.html',
            controller: 'adjustmentCtrl'
        });

        $routeProvider.when("/stock_adjustment",{
            templateUrl: 'views/adjustment/stock_adjustment.html',
            controller: 'adjustmentCtrl'
        });

        $routeProvider.when("/dispatch_report",{
            templateUrl: 'views/reports/dispatch_report.html',
            controller: 'dispatchReportCtrl'
        });

        $routeProvider.when("/monthly_arrivals",{
            templateUrl: 'views/reports/monthly_arrivals.html',
            controller: 'arrivalReportCtrl'
        });


        $routeProvider.when("/expired_items",{
            templateUrl: 'views/reports/expired_items.html',
            controller: 'reportCtrl'
        });


        $routeProvider.when("/close_to_expiry",{
            templateUrl: 'views/reports/close_to_expiry.html',
            controller: 'reportCtrl'
        });

        $routeProvider.when("/canceled_invoices",{
            templateUrl: 'views/reports/canceled_invoices.html',
            controller: 'cancelInvoiceCtrl'
        });


        $routeProvider.when("/below_minimum",{
            templateUrl: 'views/reports/below_minimum.html',
            controller: 'reportCtrl'
        });


        $routeProvider.when("/above_maximum",{
            templateUrl: 'views/reports/above_maximum.html',
            controller: 'reportCtrl'
        });

        $routeProvider.when("/transit_invoice",{
            templateUrl: 'views/reports/transit_invoice.html',
            controller: 'dispatchedCtrl'
        });



        $routeProvider.otherwise({
            redirectTo: '/home'
        });



});