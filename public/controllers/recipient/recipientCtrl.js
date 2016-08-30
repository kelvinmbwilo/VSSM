/**
 * Created by kelvin on 8/24/15.
 */
angular.module("vssmApp")
    .controller("recipientCtrl",function ($scope,$http,$mdDialog,$mdToast,$modal,$translate,$filter) {
//getting the recipients level2
        $http.get("index.php/recipients/2").success(function(data){
            $scope.data.recipientsLevel2 = data;
        });


        //getting the recipients level3
        $http.get("index.php/recipients/3").success(function(data){
            $scope.data.recipientsLevel3 = data;
        });

        //getting the recipients level4
        $http.get("index.php/recipients/4").success(function(data){
            $scope.data.recipientsLevel4 = data;
        });

        $scope.fetchBasicData();
        $scope.activeRecip = true;
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
//                                                $scope.listItems  = $scope.distributionList(branch.id);
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
                level:1
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
            $scope.data.currentOrgUnit = branch.label;
            $scope.data.currentOrgUnitId = branch.id;
            $scope.data.currentLevel = branch.level;
            $scope.data.selectedOrgUnit = branch.children;
            $scope.data.currentRecipients = [];
            $scope.activeRecip = false;
            angular.forEach($scope.data.recipientsLevel2,function(value1){
                if(value1.parent_id == branch.id){
                    $scope.data.currentRecipients.push(value1);

                }
            });
        }
        $scope.listLevel3 = function(branch){
            $scope.data.currentOrgUnit = branch.label;
            $scope.data.currentOrgUnitId = branch.id;
            $scope.data.currentLevel = branch.level;
            $scope.data.selectedOrgUnit = branch.children;
            $scope.data.currentRecipients = [];
            $scope.activeRecip = false;
            angular.forEach($scope.data.recipientsLevel3,function(value1){
                if(value1.parent_id == branch.id){
                    $scope.data.currentRecipients.push(value1);

                }
            });
        }
        $scope.listLevel4 = function(branch){
            $scope.data.currentOrgUnit = branch.label;
            $scope.data.currentOrgUnitId = branch.id;
            $scope.data.currentLevel = branch.level;
            $scope.data.selectedOrgUnit = branch.children;
            $scope.data.currentRecipients = [];
            $scope.activeRecip = false;
            angular.forEach($scope.data.recipientsLevel4,function(value1){
                if(value1.parent_id == branch.id){
                    $scope.data.currentRecipients.push(value1);

                }
            });
        }

        $scope.showAEdit = function(item){
            $scope.myItem = angular.copy(item);
            $modal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'views/recipient/updateRecipient.html',
                scope: $scope,
                controller: 'RecipientModalInstanceCtrl',
                size: "lg",
                "backdrop":"static"
            });
        }

        $scope.showAdd = function(){
            var modalInstance = $modal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'views/recipient/addRecipient.html',
                scope: $scope,
                controller: 'RecipientModalInstanceCtrl',
                size: "lg",
                "backdrop":"static"
            });
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

        //deleting Items
        var $translate = $filter('translate');
        $scope.deletedItem = [];
        $scope.deletingItem = [];
        $scope.showConfirm = function(ev,item) {
            var id = item.id;
            var confirm = $mdDialog.confirm()
                .title($translate('labels.confirm_delete'))
                .content($translate('labels.irreversible_warning'))
                .ariaLabel('Lucky day')
                .ok($translate('help.delete'))
                .cancel($translate('labels.cancel'))
                .targetEvent(ev);
            $mdDialog.show(confirm).then(function() {
                $scope.deletingItem[id] = true;
                $http.post("index.php/delete/recipients/"+id).success(function (newVal) {
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
    }).controller('RecipientModalInstanceCtrl', function ($scope, $modalInstance,$http,$mdDialog,$mdToast,$filter) {
        var $translate = $filter('translate');

        $scope.ok = function () {
            $modalInstance.close();
        };
        $scope.currentSaving = false;
        $scope.save = function(item){
            var level = parseInt($scope.data.currentLevel)+1;
            item.level = level;
            item.parent_id = $scope.data.currentOrgUnitId;
            $scope.currentSaving = true;
            $http.post("index.php/recipients", item).success(function (Item) {
                $scope.data.currentRecipients.push(Item);
                var b;
                b = tree.get_selected_branch();
                tree.add_branch(b, {
                    label: Item.name,
                    level: b.level+1,
                    id:Item.id,
                    children:[],
                    noLeaf: true,
                    onSelect: function(branch){
                        switch (branch.level){
                            case 1:
                            $scope.listLevel2(branch);
                            break;
                            case 2:
                            $scope.listLevel3(branch);
                            break;
                            case 3:
                            $scope.listLevel4(branch);
                            break;

                        }
                    }
                });
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
            $http.post("index.php/recipients/"+item.id, item).success(function (newItem) {
                for (var i = 0; i < $scope.data.currentRecipients.length; i++) {
                    if ($scope.data.currentRecipients[i].id == newItem.id) {
                        $scope.data.currentRecipients[i] = newItem;
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