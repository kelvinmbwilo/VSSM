/**
 * Created by kelvin on 8/14/15.
 */

angular.module("vssmApp")
    .config(function($translateProvider) {
        $translateProvider.useStaticFilesLoader({
            prefix: 'languages/',
            suffix: '.json'
        });
        $translateProvider.preferredLanguage('enUS');
        $translateProvider.usePostCompiling(true);
    })
    .controller("mainCtrl",function ($rootScope,$scope,$http,$location,$timeout,$translate) {

        //changing language
        $scope.changeLanguage = function (langKey) {
            $translate.use(langKey);
        };
        //setting active link on top menu
        $scope.isActive = function (viewLocation) {
            var active = (viewLocation === $location.path());
            return active;
        };
        $scope.name = "kelvin Mbwilo";
    }).controller("homeCtrl",function ($scope,$mdDialog,$mdToast) {

    });