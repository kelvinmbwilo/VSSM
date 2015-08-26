/**
 * Created by kelvin on 8/24/15.
 */
angular.module("vssmApp")
    .controller("userRolesCtrl",function ($scope,$http,$mdDialog,$mdToast,$modal,$translate,$filter) {
        $scope.userroles = [];
        $scope.currentSaving = false;
        $http.get("index.php/user_roles").success(function(data){
            $scope.userroles = data;
        });

        $scope.showAEdit = function(item){
            $scope.myItem = item;
            $modal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'views/users/updateRoles.html',
                scope: $scope,
                controller: 'UserRolesModalInstanceCtrl',
                size: "lg",
                "backdrop":"static"
            });
        }

        $scope.showAdd = function(){
            var modalInstance = $modal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'views/users/addRoles.html',
                scope: $scope,
                controller: 'UserRolesModalInstanceCtrl',
                size: "lg",
                "backdrop":"static"
            });
        }

        $scope.cancelEditting = function(){
            $scope.showEditAdd = false;
            $scope.currentEditting = false;
            $scope.currentKaya = {};
        }

        $scope.cancelAdding = function(){
            $scope.showEditAdd = false;
            $scope.currentKaya = {};
        }


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
        var $translate = $filter('translate');
        $scope.deletedItem = [];
        $scope.deletingItem = [];
        $scope.showConfirm = function(ev,id) {
            var confirm = $mdDialog.confirm()
                .title($translate('labels.confirm_delete'))
                .content($translate('labels.irreversible_warning'))
                .ariaLabel('Lucky day')
                .ok($translate('help.delete'))
                .cancel($translate('labels.cancel'))
                .targetEvent(ev);
            $mdDialog.show(confirm).then(function() {
                $scope.deletingItem[id] = true;
                $http.post("index.php/delete/user_roles/"+id).success(function (newVal) {
                    $scope.deletedItem[id] = true;
                    $mdToast.show(
                        $mdToast.simple()
                            .content($translate('error.delete_success'))
                            .position($scope.getToastPosition())
                            .hideDelay(3000)
                    );
                }).error(function(){
                    $scope.deletingItem[id] = false;
                    $mdToast.show(
                        $mdToast.simple()
                            .content($translate('error.delete_falure'))
                            .position($scope.getToastPosition())
                            .hideDelay(5000)
                    );
                });
            }, function() {

            });
        };
    }).controller('UserRolesModalInstanceCtrl', function ($scope, $modalInstance,$http,$mdDialog,$mdToast,$filter) {
        var $translate = $filter('translate');

        $scope.ok = function () {
            $modalInstance.close();
        };
        $scope.currentSaving = false;
        $scope.save = function(item){

            $scope.currentSaving = true;
            $http.post("index.php/user_roles", item).success(function (Item) {
                $scope.userroles.push(Item);
                $scope.newItem = {};
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
                $scope.currentSaving = false;
                $modalInstance.close();
            });
        }

        //updating an Item
        $scope.currentUpdating = false;
        $scope.update = function(item){
            $scope.currentUpdating = true;
            $http.post("index.php/user_roles/"+item.id, item).success(function (newItem) {
                for (var i = 0; i < $scope.userroles.length; i++) {
                    if ($scope.userroles[i].id == newItem.id) {
                        $scope.userroles[i] = newItem;
                        break;
                    }
                }
                $mdToast.show(
                    $mdToast.simple()
                        .content($translate('error.update_success'))
                        .position($scope.getToastPosition())
                        .hideDelay(5000)
                );
                $scope.currentUpdating = false;
                $modalInstance.close();
            }).error(function(){
                $mdToast.show(
                    $mdToast.simple()
                        .content($translate('error.update_falure'))
                        .position($scope.getToastPosition())
                        .hideDelay(5000)
                );
                $scope.currentUpdating = false;
                $modalInstance.close();
            })

        }
        $scope.cancel = function () {
            $modalInstance.dismiss('cancel');
        };
    });