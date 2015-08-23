/**
 * Created by kelvin on 1/9/15.
 */
angular.module("vssmApp")
    .run( function($rootScope, $location) {
        $rootScope.showLoader = false;
        // register listener to watch route changes
        $rootScope.$on( "$routeChangeStart", function(event, next, current) {
//            $rootScope.showLoader = true;
            Pace.restart()
        });
        // register listener to watch route changes successful

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

    $routeProvider.otherwise({
        redirectTo: '/home'
    });



});