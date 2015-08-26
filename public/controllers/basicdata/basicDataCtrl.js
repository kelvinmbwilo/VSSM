/**
 * Created by kelvin on 8/24/15.
 */

angular.module("vssmApp")
    .controller("basicDataCtrl",function ($scope,$http,$mdDialog,$mdToast,$modal,$translate,$filter) {
        $scope.recipients = [];
        $scope.currentSaving = false;
        //get Activities
        $http.get("index.php/activities").success(function(data){
            $scope.activities = data;
        });
        //get packaging_information
        $http.get("index.php/packaging_information").success(function(data){
            $scope.packaging_information = data;
        });

        //get vaccines
        $http.get("index.php/vaccines").success(function(data){
            $scope.vaccines = data;
        });

        //get items_min_max
        $http.get("index.php/items_min_max").success(function(data){
            $scope.items_min_max = data;
        });

        //get sources
        $http.get("index.php/sources").success(function(data){
            $scope.sources = data;
        });

        //get stores
        $http.get("index.php/stores").success(function(data){
            $scope.stores = data;
        });

        //get transport_mode
        $http.get("index.php/transport_mode").success(function(data){
            $scope.transport_mode = data;
        });

        //get adjustment_reasons
        $http.get("index.php/adjustment_reasons").success(function(data){
            $scope.adjustment_reasons = data;
        });

        //get manufactures
        $http.get("index.php/manufactures").success(function(data){
            $scope.manufactures = data;
        });

        //get annual_quota
        $http.get("index.php/annual_quota").success(function(data){
            $scope.annual_quota = data;
        });

        $scope.getRecipients = function(order){
            $http.get("index.php/recipient_levels/recipients/"+order).success(function(data){
                return data
            });
        }
        $scope.showAEdit = function(item,view){
            $scope.myItem = item;
            $modal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'views/basicdata/'+view+'.html',
                scope: $scope,
                controller: 'BasicModalInstanceCtrl',
                size: "lg",
                "backdrop":"static"
            });
        }

        $scope.showAdd = function(view){
            var modalInstance = $modal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'views/basicdata/'+view+'.html',
                scope: $scope,
                controller: 'BasicModalInstanceCtrl',
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
                $http.post("index.php/delete/recipient_levels/"+id).success(function (newVal) {
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
    }).controller('BasicModalInstanceCtrl', function ($scope, $modalInstance,$http,$mdDialog,$mdToast,$filter) {
        var $translate = $filter('translate');

        $scope.ok = function () {
            $modalInstance.close();
        };
        $scope.currentSaving = false;
        $scope.save = function(item,route,items){
            $scope.currentSaving = true;
            $http.post("index.php/"+route, item).success(function (Item) {
                items.push(Item);
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
        $scope.update = function(item,route,items){
            $scope.currentUpdating = true;
            $http.post("index.php/"+route+"/"+item.id, item).success(function (newItem) {
                for (var i = 0; i < items.length; i++) {
                    if (items[i].id == newItem.id) {
                        items[i] = newItem;
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