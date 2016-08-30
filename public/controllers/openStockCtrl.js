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

        $scope.fetchBasicData();
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
                            $scope.oneItem.packaging_id     = value.id;
                            $scope.oneItem.packaging        = value.usename;
                            $scope.oneItem.dose_vial        = value.dose_per_vial;
                            $scope.oneItem.doses            = value.dose_per_vial * value.vials_per_box;
                            $scope.oneItem.vials_per_box    = value.vials_per_box;
                            $scope.oneItem.cm_per_dose      = value.cm_per_dose;
                            $scope.oneItem.item_id          = value.vaccine.id;
                            $scope.oneItem.item_type        = value.vaccine.type;
                            $scope.oneItem.diluent_id       = value.vaccine.diluent_id;
                            $scope.oneItem.require_diluent  = value.vaccine.require_diluent;
                            $scope.oneItem.item             = value.vaccine.name;
                            $scope.vaccineStore             = value.vaccine.storage_type;
                            $scope.oneItem.manufacture_id   = value.manufacture.id;
                            $scope.oneItem.manufacture      = value.manufacture.name;
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
                            $scope.oneItem.item_type = value.vaccine.type;
                            $scope.oneItem.diluent_id = value.vaccine.diluent_id;
                            $scope.oneItem.require_diluent = value.vaccine.require_diluent;
                            $scope.oneItem.item = value.vaccine.name;
                            $scope.vaccineStore = value.vaccine.storage_type;
                            $scope.oneItem.manufacture_id = value.manufacture.id;
                            $scope.oneItem.manufacture = value.manufacture.name;
                        }
                    });
                }

                $scope.newItem.arrival_date = new Date();

                $scope.minDate = new Date();

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
                            value.usename = value.dose_per_vial+" dose_per_vial, "+ value.vials_per_box+" vials_per_box"
                            $scope.packagingInformation.push(value);
                        }
                    });
                }

        //adding Item to list
        $scope.showAdd = function(view){

            $scope.oneItem = {};
            $scope.editing = false;
            $scope.dilluentRequired = false;
            var modalInstance = $modal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'views/receive/oneItem.html',
                scope: $scope,
                controller: 'OpenModalInstanceCtrl',
                size: "lg",
                "backdrop":"static"
            });
        }

        //adding Item to list
        $scope.showAEdit = function(item){

            $scope.oneItem = item;
            $scope.editing = true;
            var modalInstance = $modal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'views/receive/oneItem.html',
                scope: $scope,
                controller: 'OpenModalInstanceCtrl',
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
                        delete $scope.newItem.items[$scope.newItem.items.indexOf(item)];
                        if($scope.newItem.items.length == 0){
                            $scope.hasItems = false;
                        }else{
                            $scope.hasItems = true;
                        }
                    }, function() {

                    });
                };
                //updating an Item
                $scope.currentSaving = false;
                $scope.saveArrival = function(item){
                    $scope.currentSaving = true;
                    $http.post("index.php/open/", item).success(function (d) {
                        $scope.showSummary = true;
                        $scope.newItem.reference = d;
                        $mdToast.show(
                            $mdToast.simple()
                                .content($translate('error.stock_added_successfull'))
                                .position($scope.getToastPosition())
                                .hideDelay(5000)
                        );
                        $scope.currentSaving = false;

                    }).error(function(){
                        $mdToast.show(
                            $mdToast.simple()
                                .content($translate('error.stock_added_falure'))
                                .position($scope.getToastPosition())
                                .hideDelay(5000)
                        );
                        $scope.currentSaving = false;
                    })

                }

        $scope.cancelDispatch = function(){
            $scope.barcode = {};
            $scope.newItem = {};
            $scope.newItem.items = [];
            $scope.oneItem = {};
            $scope.hasItems = false;
            $scope.showSummary = false;
            $scope.newItem.arrival_date = new Date();
        }
        //setting store type
        $scope.setStoreType = function(id){
            angular.forEach($scope.stores,function(value){
                if(value.id == id){
                    $scope.oneItem.store_type = value.store_type;
                    $scope.oneItem.used = (value.used_volume/value.net_volume)*100;
                    $scope.oneItem.used1 = ((value.net_volume - value.used_volume)*1000)/$scope.oneItem.cm_per_dose;
                }
            });
        }

        $scope.checkMate = function(){
            var match = true;
            angular.forEach($scope.newItem.items,function(value){
                if(value.hasMatch == false){
                    match = false;
                }
            });
            return match;
        }
        //check if all vaccines has diliuents
        $scope.checkVaccineDiluent = function(){
            var item = {};
            angular.forEach($scope.newItem.items,function(value){

                    value.hasMatch = false;
                    if(value.item_type == 'diluent'){
                        angular.forEach($scope.newItem.items,function(val){
                            if(val.diluent_id == value.item_id){
                                value.hasMatch = true;
                            }
                        })
                    }else if(value.item_type == 'vaccine'){
                        if(value.require_diluent == 'no'){
                            value.hasMatch = true;
                        }else{
                        angular.forEach($scope.newItem.items,function(val){
                            if(value.diluent_id == val.item_id){
                                value.hasMatch = true;
                            }
                        })
                        }
                    }


            });
        }


    }).controller('OpenModalInstanceCtrl', function ($scope, $modalInstance,$http,$mdDialog,$mdToast,$filter) {
                var $translate = $filter('translate');

                $scope.ok = function () {
                    $modalInstance.close();
                };

                $scope.saveOneItem = function(item){
                    item.total_volume = item.cm_per_dose * item.doses * 0.001;
                    item.vials = item.doses / item.dose_vial;
                    $scope.newItem.items.push(item);
                    $scope.hasItems = true;
                    $scope.oneItem = {};
                    $scope.checkVaccineDiluent();
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