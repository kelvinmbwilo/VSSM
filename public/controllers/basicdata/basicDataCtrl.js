/**
 * Created by kelvin on 8/24/15.
 */

angular.module("vssmApp")
    .controller("basicDataCtrl",function ($scope,$http,$mdDialog,$mdToast,$modal,$translate,$filter) {
        $scope.recipients = [];
        $scope.newItem = {};
        $scope.currentSaving = false;
        $scope.dataFormat = true;
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

        //switch annual quota formats
         $scope.switchFormats = function(val){
             $scope.dataFormat = val;
        };
        $http.get("index.php/annual_quota").success(function(data){
            $scope.annual_quota = data;
//            angular.forEach(data,function(value){
//                $scope.data.recipient_annual_quota[value.recipient_id] = [];
//                $scope.data.recipient_annual_quota[value.recipient_id][value.item_id] = value;
//            })
        });

        //save recipient annual quota
        $scope.getAnnualQuota = function(itemId,recipientId){
            var name = "";
            angular.forEach($scope.annual_quota,function(value){
                if(value.recipient_id == recipientId && value.item_id == itemId){
                    name = value.expected_annual_need;
                }
            });
            return name;
        }
        //save recipient annual quota
        $scope.pullAnnualQuota = function(recipientId,itemId){
            angular.forEach($scope.annual_quota,function(value){
                if(value.recipient_id == recipientId && value.item_id == itemId){
                    $scope.newItem.expected_annual_need = value.expected_annual_need;
                }
            });
        }

        //change formulas to use with min max
        $scope.active11 = "btn-info";
        $scope.useFormular1 = true;
        $scope.changeFormula  = function(val){
            $scope.newItem = {};
            if(val == 1){
                $scope.active11 = "btn-info";
                $scope.active12 = "btn-default";
                $scope.active13 = "btn-default";
                $scope.useFormular1 = true;
                $scope.useFormular2 = false;
                $scope.useFormular3 = false;

            }if(val == 2){
                $scope.active11 = "btn-default";
                $scope.active12 = "btn-info";
                $scope.active13 = "btn-default";
                $scope.useFormular1 = false;
                $scope.useFormular2 = true;
                $scope.useFormular3 = false;
            }if(val == 3){
                $scope.active11 = "btn-default";
                $scope.active12 = "btn-default";
                $scope.active13 = "btn-info";
                $scope.useFormular1 = false;
                $scope.useFormular2 = false;
                $scope.useFormular3 = true;
            }
        }

        //get annual_quota
        $http.get("index.php/diluents").success(function(data){
            $scope.diluents = data;
        });
        //get user_recipients
        $http.get("index.php/user/recipients").success(function(data){
            $scope.userRecipients = data;
        });
        //update Diluent list
        $scope.updatedDiluents = function(){
            $http.get("index.php/diluents").success(function(data){
                $scope.diluents = data;
            });
        }
        $scope.getDiluentName = function(id){
            var name = "";
            angular.forEach($scope.vaccines,function(value){
                if(value.id == id){
                    name =  value.name;
                }
            });
            return name;
        }
        //updating add Model
        $scope.isVaccine = true;
        $scope.changeVaccine = function(val){
            if(val == 'vaccine'){
                $scope.isVaccine = true;
            }else if(val == 'diluent'){
                $scope.isVaccine = false;
                delete $scope.newItem['diluent_id'];
                $scope.newItem.require_diluent = "no";
            }else{
                $scope.isVaccine = false;
            }
        }

        $scope.dilluentRequired = false;
        $scope.changeRequire = function(val){
            if(val == 'yes'){
                $scope.dilluentRequired = true;
                $scope.updatedDiluents();
            }else {
                $scope.dilluentRequired = false;
                delete $scope.newItem['diluent_id'];
            }
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
        $scope.showConfirm = function(ev,id,route) {
            var confirm = $mdDialog.confirm()
                .title($translate('labels.confirm_delete'))
                .content($translate('labels.irreversible_warning'))
                .ariaLabel('Lucky day')
                .ok($translate('help.delete'))
                .cancel($translate('labels.cancel'))
                .targetEvent(ev);
            $mdDialog.show(confirm).then(function() {
                $scope.deletingItem[id] = true;
                $http.post("index.php/delete/"+route+"/"+id).success(function (newVal) {
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


        //preshipments specifics
        $scope.updatePackaging = function(itemId){
            $scope.packagingInformation =[];
            angular.forEach($scope.packaging_information,function(value){
                if(value.vaccine_id == itemId){
                    value.usename = value.GTIN+" ("+ value.dose_per_vial+" x "+ value.vials_per_box+")"
                    $scope.packagingInformation.push(value);
                }
            });
        }

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

function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [year, month, day].join('-');
}