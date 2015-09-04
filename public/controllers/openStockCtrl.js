/**
 * Created by kelvin on 9/4/15.
 */
angular.module("vssmApp")
    .controller("openStockCtrl",function ($scope,$http,$mdDialog,$mdToast,$modal,$translate,$filter) {
        //var barcode structure = ]d20118901415009581171601311030124A14
        //{"lot_number":"30124A14","expiry":"160131","gtin":"18901415009581"}
        $scope.barcode = {};
        $scope.newItem = {};
        $scope.newItem.items = [];
        $scope.oneItem = {};
        $scope.hasItems = false;
        $scope.prepareItems = function(str){
            $scope.barcode ={};
            $scope.barcode.lot_number = str.substring(29);
            $scope.barcode.expiry = str.substring(21,27)
            $scope.barcode.gtin = str.substring(5,19);
            if($scope.barcode.lot_number){$scope.oneItem.lot_number = $scope.barcode.lot_number}
            if($scope.barcode.expiry){
                year  = $scope.barcode.expiry.substring(0,2) - 0;
                month = $scope.barcode.expiry.substring(2,4) - 1;
                day   = $scope.barcode.expiry.substring(4,6) - 0;
                (year < 70) ? year += 2000: year += 1900;
                $scope.oneItem.expired_date = new Date(year,month,day);}
//            if($scope.barcode.gtin){$scope.oneItem.packaging_id = $scope.barcode.gtin}
            angular.forEach($scope.packaging_information,function(value){
                if(value.GTIN == $scope.barcode.gtin){
                    $scope.oneItem.packaging_id = value.id;
                    $scope.oneItem.packaging = value.usename;
                    $scope.oneItem.dose_vial = value.dose_per_vial;
                    $scope.oneItem.vials_per_box = value.vials_per_box;
                    $scope.oneItem.cm_per_dose = value.cm_per_dose;
                    $scope.oneItem.item_id = value.vaccine.id;
                    $scope.oneItem.item = value.vaccine.name;
                    $scope.vaccineStore = value.vaccine.storage_type;
                    $scope.oneItem.manufacture_id = value.manufacture.id;
                    $scope.oneItem.manufacture = value.manufacture.name;
                }
            });
        }

        $scope.getVaccineInfo = function(id){
            angular.forEach($scope.packaging_information,function(value){
                if(value.id == id){
                    $scope.oneItem.packaging_id = value.id;
                    $scope.oneItem.packaging = value.usename;
                    $scope.oneItem.dose_vial = value.dose_per_vial;
                    $scope.oneItem.vials_per_box = value.vials_per_box;
                    $scope.oneItem.cm_per_dose = value.cm_per_dose;
                    $scope.oneItem.item_id = value.vaccine.id;
                    $scope.oneItem.item = value.vaccine.name;
                    $scope.vaccineStore = value.vaccine.storage_type;
                    $scope.oneItem.manufacture_id = value.manufacture.id;
                    $scope.oneItem.manufacture = value.manufacture.name;
                }
            });
        }

        $scope.newItem.arrival_date = new Date();
        //managing the date picker
        $scope.dateOptions = {
            formatYear: 'yy',
            startingDay: 1
        };
        $scope.status = {
            opened: false,
            opened2: false,
            opened3: false
        }
        $scope.formats = ['dd-MMMM-yyyy', 'yyyy/MM/dd', 'dd.MM.yyyy', 'shortDate'];
        $scope.format = $scope.formats[0];
        $scope.open = function($event) {
            $scope.status.opened = true;
        };
        $scope.open3 = function($event) {
            $scope.status.opened3 = true;
        };
        $scope.open2 = function($event) {
            $scope.status.opened2 = true;
        };
        $scope.minDate = new Date();

        //getting basic data
        //get Activities
        $http.get("index.php/activities").success(function(data){
            $scope.activities = data;
        });
        //get packaging_information
        $http.get("index.php/packaging_information").success(function(data){
            $scope.packaging_information = data;
            $scope.packagingInformation =[];
            angular.forEach($scope.packaging_information,function(value){
                value.usename = value.GTIN+" ("+ value.dose_per_vial+" x "+ value.vials_per_box+")"
                $scope.packagingInformation.push(value);
            });
        });

        //preshipments specifics
        $scope.updatePackaging = function(itemId){
            $scope.packagingInformation =[];
            angular.forEach($scope.vaccines,function(value1){
                if(value1.id == itemId){
                    $scope.vaccineStore = value1.storage_type;
                }
            });
            angular.forEach($scope.packaging_information,function(value){
                if(value.vaccine_id == itemId){
                    value.usename = value.GTIN+" ("+ value.dose_per_vial+" x "+ value.vials_per_box+")"
                    $scope.packagingInformation.push(value);
                }
            });
        }

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

        $http.get("index.php/annual_quota").success(function(data){
            $scope.annual_quota = data;
        });

        //adding Item to list
        $scope.showAdd = function(view){

            $scope.oneItem = {};
            $scope.dilluentRequired = false;
            var modalInstance = $modal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'views/open_stock/oneItem.html',
                scope: $scope,
                controller: 'ReceiveModalInstanceCtrl',
                size: "lg",
                "backdrop":"static"
            });
        }

        $scope.getTotal = function(value,value1){
            $scope.oneItem.t_price = value*value1;
        }
        $scope.getTotalUnit = function(value,value1,value2){
            $scope.oneItem.t_price = value*value1;
            $scope.oneItem.u_price = value2/value1;
        }
        $scope.getUnit = function(value,value1){
            $scope.oneItem.u_price = value/value1;
        }

        $scope.updateStores = function(vaccineId){
            var vaccine = "";
            angular.forEach($scope.vaccines,function(value1){
                if(value1.id == vaccineId){
                    vaccine = value1.storage_type;
                }
            });
            angular.forEach($scope.stores,function(value){

                if(value.store_type == vaccine){
                    ;
                }
            });
        }

        $scope.getActivityName = function(id){
            var name = "";
            angular.forEach($scope.sources,function(value){
                if(value.id == id){
                    name = value.name;
                }
            });
            return name;
        }
        $scope.getStoreName = function(id){
            var name = "";
            angular.forEach($scope.stores,function(value){
                if(value.id == id){
                    name = value.name;
                }
            });
            return name;
        }

        var $translate = $filter('translate');
        $scope.showConfirm = function(ev,item) {
            var confirm = $mdDialog.confirm()
                .title($translate('labels.confirm_delete'))
                .content($translate('labels.irreversible_warning'))
                .ariaLabel('Lucky day')
                .ok($translate('help.delete'))
                .cancel($translate('labels.cancel'))
                .targetEvent(ev);
            $mdDialog.show(confirm).then(function() {
                $scope.newItem.items.splice(item);
                if($scope.newItem.items.length == 0){

                }
            }, function() {

            });
        };
    }).controller('ReceiveModalInstanceCtrl', function ($scope, $modalInstance,$http,$mdDialog,$mdToast,$filter) {
        var $translate = $filter('translate');

        $scope.ok = function () {
            $modalInstance.close();
        };

        $scope.saveOneItem = function(item){
            item.total_volume = item.cm_per_dose * item.doses;
            item.vials = item.doses / item.dose_vial;
            $scope.newItem.items.push(item);
            $scope.hasItems = true;
            $modalInstance.close();
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