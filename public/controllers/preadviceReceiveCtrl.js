/**
 * Created by kelvin on 9/9/15.
 */

angular.module("vssmApp")
    .controller("preadviceReceiveCtrl",function ($scope,$http,$mdDialog,$mdToast,$modal,$translate,$filter) {
        //var barcode structure = ]d20118901415009581171601311030124A14
        //{"lot_number":"30124A14","expiry":"160131","gtin":"18901415009581"}
        $(document).ready(function(){
            $("#arrival_report_no").focus();
        })

        $scope.fetchBasicData();
        $scope.barcode = {};
        $scope.newItem = {};
        $scope.newItem.items = [];
        $scope.oneItem = {};
        $scope.hasItems = false;
        $scope.data_ready = false;
        $scope.item_found = true;
        $scope.newItem.main_currency = $scope.main_currency;
        $scope.showSummary = false;
        $scope.prepareItems = function(str){
            $scope.item_found = false;
            var batch_no = "";
            if(str.length > 10){
                $scope.barcode.lot_number = str.substring(29);
                $scope.barcode.expiry = str.substring(21,27)
                $scope.barcode.gtin = str.substring(5,19);
                if($scope.barcode.lot_number){
                    angular.forEach($scope.newItem.items,function(value){
                        if(value.lot_number == $scope.barcode.lot_number)  {
                            $scope.showAEdit(value);
                            $scope.item_found = true;
                            $scope.barcode ={};
                        }
                    });
                }
            }else{
                angular.forEach($scope.newItem.items,function(value){
                    if(value.lot_number == str)  {
                        $scope.showAEdit(value);
                        $scope.item_found = true;
                    }
                });
            }
        }

        //loading basic data
        //get pre-shipments
        $http.get("index.php/pending_shipments").success(function(data){
            $scope.pre_shipments = data;
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
                    value.usename = value.dose_per_vial+" dose_per_vial, "+ value.vials_per_box+" vials_per_box"
                    $scope.packagingInformation.push(value);
                }
            });
        }

        //get transport_mode
        $http.get("index.php/pending_expected_packages").success(function(data){
            $scope.expected_packages = data;
        });

        //fetching the shipment after scan
        $scope.errorMessage = false;
        $scope.loadPackage = function(package_number){
            $scope.data_ready = false;
            var packages = [];
            var counter = 0;
            angular.forEach($scope.pre_shipments,function(value){
                if(value.status == "pending"){
                    if(value.package_id == package_number){
                        var itemm = {};
                        $scope.newItem.arrival_date = value.expected_time_of_arrival;
                        $scope.newItem.source_id = value.source_id;
                        $scope.newItem.total_weight = value.total_weight;
                        $scope.newItem.packed_volume = value.packed_volume;
                        $scope.newItem.main_currency = $scope.system_settings.main_currency;
                        itemm.lot_number = value.lot_number;
                        itemm.expired_date = value.expired_date;
                        itemm.doses = value.number_of_doses;
                        itemm.prod_date = value.manufacture_date;
                        angular.forEach($scope.packaging_information,function(val){
                            if(val.id == value.packaging_id){
                                itemm.packaging_id = val.id;
                                itemm.packaging = val.usename;
                                itemm.dose_vial = val.dose_per_vial;
                                itemm.vials = value.number_of_doses/val.dose_per_vial;
                                itemm.boxes = itemm.vials/val.vials_per_box;
                                itemm.vials_per_box = val.vials_per_box;
                                itemm.cm_per_dose = val.cm_per_dose;
                                itemm.total_volume = value.number_of_doses*val.cm_per_dose*0.001;
                                itemm.item_id = val.vaccine.id;
                                itemm.item = val.vaccine.name;
                                itemm.vaccineStore = val.vaccine.storage_type;
                                itemm.manufacture_id = val.manufacture.id;
                                itemm.manufacture = val.manufacture.name;
                                itemm.number_as_expected = 'yes';
                                itemm.physical_damage = 'no';
                            }
                        });
                        $scope.newItem.items.push(itemm);
                        counter++;
                    }
                }
            });
            if(counter == 0){
                $scope.errorMessage = true;
            }else{
                $scope.errorMessage = false;
                $scope.data_ready = true;
                $("#batch_no").focus();
            }
        }

        $scope.loadPackage1 = function(package_number){

            $scope.data_ready = false;
            var packages = [];
            var counter = 0;
            angular.forEach($scope.expected_packages,function(value){
                console.log(value.voucher_number+"==" +package_number)
                if(value.receiving_status == 'pending'){
                    if(value.voucher_number == package_number){
                        $scope.newItem.arrival_date = new Date();
                        $scope.newItem.source_id = value.source_id;
                        $scope.newItem.main_currency = $scope.system_settings.main_currency;
                        angular.forEach(value.items,function(valu){
                            var itemm = {};
                            itemm.lot_number = valu.batch_number;
                            itemm.expired_date = valu.expiry_date;
                            itemm.doses = valu.amount;
                            itemm.u_price = valu.unit_price;
                            itemm.activity = valu.activity;
                            itemm.t_price = valu.unit_price * valu.amount;
                            angular.forEach($scope.packaging_information,function(val){
                                if(val.id == valu.packaging_id){
                                    itemm.packaging_id = val.id;
                                    itemm.packaging = val.usename;
                                    itemm.dose_vial = val.dose_per_vial;
                                    itemm.vials = valu.amount/val.dose_per_vial;
                                    itemm.boxes = itemm.vials/val.vials_per_box;
                                    itemm.vials_per_box = val.vials_per_box;
                                    itemm.cm_per_dose = val.cm_per_dose;
                                    itemm.total_volume = valu.amount*val.cm_per_dose*0.001;
                                    itemm.item_id = val.vaccine.id;
                                    itemm.item = val.vaccine.name;
                                    itemm.vaccineStore = val.vaccine.storage_type;
                                    itemm.manufacture_id = val.manufacture.id;
                                    itemm.manufacture = val.manufacture.name;
                                    itemm.number_as_expected = 'yes';
                                    itemm.physical_damage = 'no';
                                }
                            });
                            $scope.newItem.items.push(itemm);
                        });


                        counter++;
                    }
                }
            });
            if(counter == 0){
                $scope.errorMessage = true;
            }else{
                $scope.errorMessage = false;
                $scope.data_ready = true;
                $("#batch_no").focus();
            }
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

        //adding Item to list
        $scope.showAEdit = function(item){

            $scope.oneItem = item;
            $scope.editing = true;
            var modalInstance = $modal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'views/receive/one_preAdvice.html',
                scope: $scope,
                controller: 'ReceiveModalInstanceCtrl',
                size: "lg",
                "backdrop":"static"
            });
        }

        $scope.dateOptions = {
            formatYear: 'yy',
            startingDay: 1
        };
        $scope.status = {
            opened: false,
            opened2: false,
            opened3: false
        }
        $scope.formats = ['dd-MMMM-yyyy', 'yyyy/MM/dd', 'dd MMM yyyy', 'shortDate'];
        $scope.format = $scope.formats[2];
        $scope.open = function($event) {
            $scope.status.opened = true;
        };
        var $translate = $filter('translate');
        //updating an Item
        $scope.currentSaving = false;
        $scope.saveArrival = function(item,type){
            $scope.currentSaving = true;
            item.from_type = type;
            $http.post("index.php/pre_receive/", item).success(function (d) {
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
            $scope.data_ready = false;
            $scope.item_found = true;
            $scope.newItem = {};
            $scope.newItem.items = [];
            $scope.oneItem = {};
            $scope.showSummary = false;
            $scope.newItem.arrival_date = new Date();
        }

        $scope.checksave = false;
        $scope.cansave = function(){
            var i =0;
            angular.forEach($scope.newItem.items,function(value){
                if(value.hasOwnProperty('u_price') && value.hasOwnProperty('store_id') && value.hasOwnProperty('activity')){
                    i++;
                }
            })
            if(i == $scope.newItem.items.length){
                $scope.checksave = true;
                return true;
            }else{
                $scope.checksave = false;
                return false;
            }
        }

    });