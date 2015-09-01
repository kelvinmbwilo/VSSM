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


        $routeProvider.otherwise({
            redirectTo: '/home'
        });



});