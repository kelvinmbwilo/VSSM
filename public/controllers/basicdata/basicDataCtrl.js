/**
 * Created by kelvin on 8/24/15.
 */

angular.module("vssmApp")
    .controller("basicDataCtrl",function ($scope,$http,$mdDialog,$mdToast,$modal,$translate,$filter) {
        $scope.recipients = [];
        $scope.newItem = {};
        $scope.currentSaving = false;
        $scope.dataFormat = true;

        //prepare years dropdown
        var startyear = new Date().getFullYear();
        $scope.rangetouse = [];
        for(var i=-3;i<16;i++) {
            $scope.rangetouse.push(startyear + i);
        }
        $scope.selectyear = startyear;
        //get All Annual Quotars
        $http.get("index.php/annual_quota").success(function(data){
            $scope.annual_quota1 = data;
        });
        //get packaging_information
        $http.get("index.php/packaging_information").success(function(data){
            $scope.packaging_information = data;
            $scope.packagingInformation =[];
            angular.forEach($scope.packaging_information,function(value){
                value.usename = value.GTIN+" ("+ value.dose_per_vial+" dose_per_vial, "+ value.vials_per_box+" vials_per_box)"
                $scope.packagingInformation.push(value);
            });
        });

        //switch annual quota formats
         $scope.switchFormats = function(val){
             $scope.dataFormat = val;
        };

        //save recipient annual quota
        $scope.getAnnualQuota = function(itemId,recipientId,year){
            var name = "";
            angular.forEach($scope.annual_quota1,function(value){
                if(value.recipient_id == recipientId && value.item_id == itemId && value.year == year){
                    name = value.expected_annual_need;
                }
            });
            return name;
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

        //get vaccine diluents
        $http.get("index.php/diluents").success(function(data){
            $scope.diluents = data;
        });
        //get user_recipients
        $http.get("index.php/user/recipients").success(function(data){
            $scope.userRecipients = data;
        });
        //get pre-shipments
        $http.get("index.php/pre_shipments").success(function(data){
            $scope.pre_shipments = data;
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

        //updating add Model when either vaccine or diluent is selected
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

        //check if dilluent is requiered
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
            $scope.myItem = angular.copy(item);
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

            $scope.newItem = {};
            $scope.dilluentRequired = false;
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
                    value.usename = value.GTIN+" ("+ value.dose_per_vial+" dose_per_vial, "+ value.vials_per_box+" vials_per_box)"
                    $scope.packagingInformation.push(value);
                }
            });
        }
        $scope.dateOptions = {
            formatYear: 'yy',
            startingDay: 1
        };
        $scope.status = {
            opened: false,
            opened1: false,
            opened2: false,
            opened3: false
        };
        $scope.formats = ['dd-MMMM-yyyy', 'yyyy/MM/dd', 'dd.MM.yyyy', 'shortDate'];
        $scope.format = $scope.formats[0];
        $scope.open = function($event) {
            $scope.status.opened = true;
        };
        $scope.open1 = function($event) {
            $scope.status.opened1 = true;
        };
        $scope.open2 = function($event) {
            $scope.status.opened2 = true;
        };
        $scope.open3 = function($event) {
            $scope.status.opened3 = true;
        };

        //fetching the shipment after scan
        $scope.loadPackage = function(package_number){
            angular.forEach($scope.pre_shipments,function(value){
                if(value.package_id == package_number){
                    $scope.newItem.expected_time_of_arrival = value.expected_time_of_arrival;
                    $scope.newItem.source_id = value.source_id;
                    $scope.newItem.total_weight = value.total_weight;
                    $scope.newItem.packed_volume = value.packed_volume;
                }
            })
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
        $scope.cancelupdate = function () {
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