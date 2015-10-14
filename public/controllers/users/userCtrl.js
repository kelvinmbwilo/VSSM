/**
 * Created by kelvin on 8/24/15.
 */
angular.module("vssmApp")
    .controller("userCtrl",function ($scope,$http,$mdDialog,$mdToast,$modal,$translate,$filter) {
        $scope.newItem = {};
        $scope.myItem = {};
        $scope.systemusers = [];
        $scope.recipients  = [];
        $scope.userroles   = [];
        $scope.currentSaving = false;
        //get Users

        $scope.fetchBasicData();
        $http.get("index.php/users").success(function(data){
            $scope.systemusers = data;
        });

        //get recipient levels
        $http.get("index.php/recipient_levels").success(function(data){
            $scope.recipients = data;
        });

        //get recipient levels
        $scope.getUserRecipient = function(id){
            $http.get("index.php/recipient/"+id).success(function(data){
                return data;
            });
        }


        //get user roles
        $http.get("index.php/user_roles").success(function(data){
            $scope.userroles = data;
        });

        //get recipient of a level
        $scope.getRecipients = function(id){
            //getting the recipients level2
            $http.get("index.php/getRrecipients/"+id).success(function(data){
                $scope.data.recipientsLevel2 = data;
            });
        }

        $scope.showAEdit = function(item){
            $scope.myItem = angular.copy(item);
            $modal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'views/users/updateUsers.html',
                scope: $scope,
                controller: 'UserModalInstanceCtrl',
                size: "lg",
                "backdrop":"static"
            });
        }

        $scope.showLogs = function(item){
            $http.get("index.php/userlogs/"+item.id).success(function(data){
                $scope.data.logItems = data;
            });
            $scope.logItem = item;
            $scope.currUsername = item.first_name +" "+item.last_name
            $modal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'views/users/logs.html',
                scope: $scope,
                controller: 'UserModalInstanceCtrl',
                size: "lg",
                "backdrop":"static"
            });
        }

        $scope.showAdd = function(){
            var modalInstance = $modal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'views/users/addUsers.html',
                scope: $scope,
                controller: 'UserModalInstanceCtrl',
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
                $http.post("index.php/delete/users/"+id).success(function (newVal) {
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

        $scope.getRootChildren = function(id){
            var child = [];
            var rootId = id;
            angular.forEach($scope.data.recipientsLevel2,function(value){
                if(value.parent_id == rootId){
                    var label = value.name;
                    var children = ['loading...'];
                    var id = value.id;
                    var level = 2;
                    child.push({label:label,children:children,id:id,level:level,onSelect: function(branch){
                        //getting all wards for this district

                        var branch =branch;
                        branch.children = [];
                        $scope.listLevel3(branch);
                        angular.forEach($scope.data.recipientsLevel3,function(value1){
                            if(value1.parent_id == branch.id){
                                var label = value1.name;
                                var children = ['loading...'];
                                var id = value1.id;
                                var level = 3;
                                branch.children.push({label:label,children:children,id:id,level:level,noLeaf: true,onSelect: function(branch){
                                    //getting all villages for this ward
                                    var branch =branch;
                                    $scope.listLevel4(branch);
                                    branch.children = [];
                                    angular.forEach($scope.data.recipientsLevel4,function(value2){
                                        if(value2.parent_id == branch.id){
                                            var label = value2.name;
                                            var children = [];
                                            var id = value2.id;
                                            var level = 4;
                                            branch.children.push({label:label,children:children,id:id,level:level,noLeaf: false,onSelect: function(branch){
                                                $scope.listLevel5(branch)
                                            }
                                            });
                                        }
                                    })

                                }});
                            }
                        })

                    }});
                }

            });
            return child;
        }
        $scope.data.orgUnit = [];
        $http.get("index.php/recipients/1").success(function(data){
            $scope.data.recipientsLevel1 = data;
            $scope.data.orgUnit = [{
                label: $scope.data.recipientsLevel1[0].name,
                children: $scope.getRootChildren($scope.data.recipientsLevel1[0].id),
                id: $scope.data.recipientsLevel1[0].id,
                level:1,
                onSelect: function(branch){
                    $scope.newItem.recipient_id = branch.id;
                }
            }];
        });

        $scope.my_tree = tree = {};
        $scope.my_tree_handler = function(branch) {
            switch(branch.level){
                case 1:
                    $scope.listLevel2(branch);
                    break;

            }
        }

        $scope.listLevel2 = function(branch){
            $scope.newItem.recipient_id = branch.id;
        }
        $scope.listLevel3 = function(branch){
            $scope.newItem.recipient_id = branch.id;
        }
        $scope.listLevel4 = function(branch){
            $scope.newItem.recipient_id = branch.id;
        }
        $scope.listLevel5 = function(branch){
            $scope.newItem.recipient_id = branch.id;
        }

        //check if password do match
        $scope.passcheck = false;
        $scope.passwordNoMatch = function(){
            if($scope.newItem.password){
                if($scope.newItem.password != "" && $scope.newItem.re_password && $scope.newItem.re_password != ""){
                    if($scope.newItem.password == $scope.newItem.re_password){
                        $scope.passcheck = false;
                    }else{
                        $scope.passcheck = true;
                    }
                }else{
                    $scope.passcheck = false;
                }
            }
        }
        $scope.currentSaving = false;
        $scope.updateUserPassword = function(user){
            $scope.currentSaving = true;
            $http.post("index.php/password/"+$scope.logedInUser.id, user).success(function (ret) {
                if(ret == "success"){
                    $mdToast.show(
                        $mdToast.simple()
                            .content($translate('error.save_success'))
                            .position($scope.getToastPosition())
                            .hideDelay(3000)
                    );
                }else{
                    $mdToast.show(
                        $mdToast.simple()
                            .content($translate('error.save_falure'))
                            .position($scope.getToastPosition())
                            .hideDelay(5000)
                    );
                }

                $scope.currentSaving = false;
                $scope.newItem = {};
            }).error(function(){
                $mdToast.show(
                    $mdToast.simple()
                        .content($translate('error.save_falure'))
                        .position($scope.getToastPosition())
                        .hideDelay(5000)
                );
                $scope.currentSaving = false;
                $scope.newItem = {};
            })

        }


    }).controller('UserModalInstanceCtrl', function ($scope, $modalInstance,$http,$mdDialog,$mdToast,$filter) {
        var $translate = $filter('translate');

        $scope.ok = function () {
            $modalInstance.close();
        };
        $scope.currentSaving = false;
        $scope.save = function(item){

            $scope.currentSaving = true;
            $http.post("index.php/users", item).success(function (Item) {
                $scope.systemusers.push(Item);
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
            $http.post("index.php/users/"+item.id, item).success(function (newItem) {
                for (var i = 0; i < $scope.systemusers.length; i++) {
                    if ($scope.systemusers[i].id == newItem.id) {
                        $scope.systemusers[i] = newItem;
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