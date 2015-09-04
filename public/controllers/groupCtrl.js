/**
 * Created by kelvin on 8/14/15.
 */
angular.module("vssmApp")
    .controller("groupCtrl",function ($scope,$http,$mdDialog,$mdToast,$filter) {
        $scope.toastPosition = {
            bottom: true,
            top: false,
            left: false,
            right: true
        };

        $scope.getToastPosition = function() {
            return Object.keys($scope.toastPosition)
                .filter(function(pos) { return $scope.toastPosition[pos]; })
                .join(' ');
        };

        $scope.currentSaving1 = false;
        $scope.saveSettings = function(item){
            $scope.currentSaving = true;
            $http.post("index.php/system_settings", item).success(function (Item) {
                $scope.system_settings = Item;
                $mdToast.show(
                    $mdToast.simple()
                        .content($translate('error.save_success'))
                        .position($scope.getToastPosition())
                        .hideDelay(5000)
                );
                $scope.currentSaving = false;
                $modalInstance.close();
            }).error(function(){
                $mdToast.show(
                    $mdToast.simple()
                        .content($translate('error.save_falure'))
                        .position($scope.getToastPosition())
                        .hideDelay(5000)
                );
                $scope.currentSaving1 = false;
            });
        }
    });