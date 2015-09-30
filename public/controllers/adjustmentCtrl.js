/**
 * Created by kelvin on 9/15/15.
 */

angular.module("vssmApp")
    .controller("adjustmentCtrl",function ($scope,$http,$mdDialog,$mdToast,$modal,$translate,$filter) {

        $scope.newItem = {};
        //get Arrivals
        $http.get("index.php/arrivals").success(function(data){
            $scope.arrivals = [];
            angular.forEach(data,function(value){
                var name = (value.from_source)?"from"+value.from_source.name+' #':' ';
                value.name = name+value.arrival_report_number+' , '+value.arrival_date
                $scope.arrivals.push(value);
            });
        });

        $scope.getMovedVolume =function(item){

        }


        $scope.firstItem = {};
        $scope.getItem = function(id){
            console.log(id);
            angular.forEach($scope.arrivals,function(v){
               if(v.id == id){
                   $scope.firstItem.source_id = v.source_id;
                   $scope.firstItem.freight_cost = v.freight_cost;
                   $scope.firstItem.notes = v.notes;
                   $scope.firstItem.arrival_id = v.id;
                   $scope.arrivalItems = [];
                   angular.forEach(v.arrival_items,function(value){
                       angular.forEach($scope.packaging_information,function(val){
                           if(val.id == value.packaging_id){
                               value.packaging = val;
                           }
                       });
                       value.name = value.packaging.vaccine.name+" "+value.lot_number
                       $scope.arrivalItems.push(value);
                   });
               }
            });

        }


        $scope.storeB = [];
        $scope.getStoreItems = function(id){
            $scope.storeB = [];
            $scope.selectedStore1 =null;
            $scope.selectedStoreItems1 =null;
            angular.forEach($scope.stores,function(value1){
                if(value1.id == id){
                    $scope.newItem.status = 'one'
                    $scope.selectedStore = value1;
                }else{
                    $scope.storeB.push(value1)
                }
            });
            $http.get("index.php/store/stock/"+id).success(function(data){
                angular.forEach(data,function(value){
                    angular.forEach($scope.packaging_information,function(val){
                        if(val.id == value.packaging_id){
                            value.packaging = val;
                        }
                    });
                    value.name = value.packaging.vaccine.name+" "+value.lot_number+", "+value.expiry_date+ ", "+value.amount+"doses";
                });
                $scope.selectedStoreItems =  data;
            });
        }
        $scope.validVolume = true;
        $scope.changeVolume = function(val){
            var vol = val * $scope.cm_per_dose* 0.001;
            if($scope.selectedStore1){
                if(($scope.selectedStore1.net_volume - $scope.selectedStore1.used_volume) > vol){
                    $scope.validVolume = true;
                }else{
                    $scope.validVolume = false;
                }
            }

        }

        $scope.storeMovedItem = function(id){
            angular.forEach($scope.selectedStoreItems,function(value){
                if(value.id == id){
                    $scope.maximum = value.amount;
                    $scope.cm_per_dose = value.packaging.cm_per_dose;
                }
            });
        }

        $scope.getStoreItems1 = function(id){
            angular.forEach($scope.stores,function(value1){
                if(value1.id == id){
                    $scope.selectedStore1 = value1;
                }
            });
            $http.get("index.php/store/stock/"+id).success(function(data){
                angular.forEach(data,function(value){
                    angular.forEach($scope.packaging_information,function(val){
                        if(val.id == value.packaging_id){
                            value.packaging = val;
                        }
                    });
                    value.name = value.packaging.vaccine.name+" "+value.lot_number+ ", "+value.amount+"doses";
                });
                $scope.selectedStoreItems1 =  data;
            });
        }

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

        //get stock_items
        $http.get("index.php/stock_items").success(function(data){
            $scope.stock_items = data;
            angular.forEach($scope.stock_items,function(value){
                value.vaccine = $scope.assignValue($scope.vaccines,value.vaccine_id);
                value.packaging = $scope.assignValue($scope.packaging_information,value.packaging_id);
                value.store = $scope.assignValue($scope.stores,value.store_id);
                value.name = value.vaccine.name +" , "+ value.lot_number+" , "+value.store.name+", "+value.amount+" "+$translate('labels.doses')+", "+value.expiry_date
//                $scope.packagingInformation.push(value);
            });
        });
        $scope.newItem.changeStock = 'no';
        $scope.getStockInfo = function(id){
            angular.forEach($scope.stock_items,function(value){
                if(value.id == id){
                    $scope.maxValue = value.amount;
                    $scope.newItem.packaging_id = value.packaging_id;
                    $scope.newItem.expired_date = value.expiry_date;
                    $scope.newItem.lot_number = value.lot_number;
                    $scope.newItem.u_price = value.unit_price;
                    $scope.newItem.store_id = value.store_id;
                    $scope.newItem.activity_id = value.activity_id;
                    $scope.newItem.packaging = value.packaging.dose_per_vial;
                    $scope.newItem.dose_vial = value.packaging.dose_per_vial;
                    $scope.newItem.vials_per_box = value.packaging.vials_per_box;
                    $scope.newItem.cm_per_dose = value.packaging.cm_per_dose;
                    $scope.newItem.item_id = value.vaccine.id;
                    $scope.newItem.source_id = value.source_id;
                    $scope.newItem.item = value.vaccine.name;
                    $scope.newItem.manufacture_id = value.packaging.manufacture_id;
                }
            })
        }

        //fetching the shipment after scan
        $scope.errorMessage = false;
        $scope.oldItem = {};
        $scope.setItem = function(id){
            angular.forEach($scope.arrivalItems,function(value){
                if(value.id == id){
                        var itemm = {};
                        $scope.oldItem.lot_number = value.lot_number;
                        $scope.oldItem.expired_date = value.expiry_date;
                        $scope.oldItem.t_price = value.total_price;
                        $scope.oldItem.doses = value.number_received;
                        $scope.oldItem.activity = value.activity_id;
                        $scope.newItem.id = value.id;
                    }
            });
        }

        $scope.cancel = function(){
            $scope.newItem.item = null
            $scope.oldItem={};
        }

        $scope.cancel1 = function(){
            $scope.newItem.stock_id = null
            $scope.newItem={};
        }

        var $translate = $filter('translate');
        //updating an Item
        $scope.currentSaving = false;
        $scope.saveAdjust = function(item){
            $scope.currentSaving = true;
            $http.post("index.php/arrival_adjust/", item).success(function (newItem) {
                $mdToast.show(
                    $mdToast.simple()
                        .content($translate('error.stock_adjusted_successfull'))
                        .position($scope.getToastPosition())
                        .hideDelay(5000)
                );
                $scope.currentSaving = false;
                $scope.newItem.item = null;
                $scope.oldItem={};

                //get Arrivals
                $http.get("index.php/arrivals").success(function(data){
                    $scope.arrivals = [];
                    angular.forEach(data,function(value){
                        value.name = "rom: "+value.from_source.name+' #'+value.arrival_report_number+' , '+value.arrival_date
                        $scope.arrivals.push(value);
                    });
                });
            }).error(function(){
                $mdToast.show(
                    $mdToast.simple()
                        .content($translate('error.stock_adjusted_falure'))
                        .position($scope.getToastPosition())
                        .hideDelay(5000)
                );
                $scope.currentSaving = false;
            })

        }


        $scope.currentSaving3 = false;
        $scope.saveStockAdjust = function(item){
            $scope.currentSaving2 = true;
            $http.post("index.php/stock_adjust/", item).success(function (newItem) {
                $mdToast.show(
                    $mdToast.simple()
                        .content($translate('error.stock_adjusted_successfull'))
                        .position($scope.getToastPosition())
                        .hideDelay(5000)
                );
                $scope.currentSaving3 = false;
                $scope.newItem.stock_id = null
                $scope.newItem={};
                $scope.newItem.changeStock = 'no';
                $http.get("index.php/stock_items").success(function(data){
                    $scope.stock_items = data;
                    angular.forEach($scope.stock_items,function(value){
                        value.vaccine = $scope.assignValue($scope.vaccines,value.vaccine_id);
                        value.packaging = $scope.assignValue($scope.packaging_information,value.packaging_id);
                        value.store = $scope.assignValue($scope.stores,value.store_id);
                        value.name = value.vaccine.name +" , "+ value.lot_number+" , "+value.store.name+", "+value.amount+" "+$translate('labels.doses')+", "+value.expiry_date
//                $scope.packagingInformation.push(value);
                    });
                });
            }).error(function(){
                $mdToast.show(
                    $mdToast.simple()
                        .content($translate('error.stock_adjusted_falure'))
                        .position($scope.getToastPosition())
                        .hideDelay(5000)
                );
                $scope.currentSaving3 = false;
            })

        }

        $scope.currentSaving1 = false;
        $scope.saveAdjust1 = function(item){
            $scope.currentSaving1 = true;
            $http.post("index.php/arrival_adjust1/", item).success(function (newItem) {
               $mdToast.show(
                    $mdToast.simple()
                        .content($translate('error.stock_adjusted_successfull'))
                        .position($scope.getToastPosition())
                        .hideDelay(5000)
                );
                $scope.currentSaving1 = false;

            }).error(function(){
                $mdToast.show(
                    $mdToast.simple()
                        .content($translate('error.stock_adjusted_falure'))
                        .position($scope.getToastPosition())
                        .hideDelay(5000)
                );
                $scope.currentSaving1 = false;
            })

        }

        $scope.currentSaving4 = false;
        $scope.moveItem = function(item){
            $scope.currentSaving1 = true;
            $http.post("index.php/move_item", item).success(function (newItem) {
                $scope.newItem = {};
               $mdToast.show(
                    $mdToast.simple()
                        .content($translate('error.stock_adjusted_successfull'))
                        .position($scope.getToastPosition())
                        .hideDelay(5000)
                );
                $scope.currentSaving4 = false;

            }).error(function(){
                $mdToast.show(
                    $mdToast.simple()
                        .content($translate('error.stock_adjusted_falure'))
                        .position($scope.getToastPosition())
                        .hideDelay(5000)
                );
                $scope.currentSaving4 = false;
            })

        }


    })
    .controller("dispatchAdjustmentCtrl",function ($scope,$http,$mdDialog,$mdToast,$modal,$translate,$filter){
        //ntialize variables
        $scope.newItem = {};
        $scope.newItem.dispatch_date = new Date();
        $scope.newItem.items = [];
        $scope.oneItem = {};
        $scope.maxexceed = false;
        //get dispatched_packages
        $http.get("index.php/dispatched_packages").success(function(data){
            $scope.expect_packages = [];
            angular.forEach(data,function(value){
                value.name = value.voucher_number +" to: "+value.destination.name+' '+value.date_sent;
                $scope.expect_packages.push(value);
            });
        });

        //prepare an adjustment item to udjust
        $scope.canCancel = false;
        $scope.getDispatchItem = function(id){
            angular.forEach($scope.expect_packages,function(v){
                if(v.id == id){
                    $scope.newItem.dispatch_date = v.date_sent;
                    $scope.newItem.recipients = v.recipient_id;
                    $scope.newItem.transport_mode = v.transport_mode_id;
                    $scope.newItem.items = [];
                    angular.forEach(v.items,function(value){
                        var item = {};
                        angular.forEach($scope.packaging_information,function(val){
                            if(val.id == value.packaging_id){

                                item.packaging_id = value.packaging_id;
                                item.expired_date = value.expiry_date;
                                item.lot_number = value.batch_number;
                                item.u_price = value.unit_price;
                                item.t_price = value.unit_price * value.amount;
                                item.total_volume = val.cm_per_dose * value.amount * 0.001;
                                item.vials =  value.amount/val.dose_per_vial;
                                item.store_id = value.store_id;
                                item.activity_id = value.activity;
                                item.dose_vial = val.dose_per_vial;
                                item.doses = value.amount;
                                item.vials_per_box = val.vials_per_box;
                                item.cm_per_dose = val.cm_per_dose;
                                item.item_id = val.vaccine.id;
                                item.item_type = val.vaccine.type;
                                item.diluent_id = val.vaccine.diluent_id;
                                item.require_diluent = val.vaccine.require_diluent;
                                item.source_id = value.source_id;
                                item.item = val.vaccine.name;
                                item.manufacture_id = val.manufacture_id;
                            }
                        });
                        $scope.newItem.items.push(item);
                        console.log(item)
                    });
                    if(v.receiving_status == 'pending'){
                        $scope.canCancel = true;
                    }

                }
            });
        }

        $scope.getStockInfo = function(id){
            angular.forEach($scope.stock_items,function(value){
                if(value.id == id){
                    $scope.maxValue = value.amount;
                    $scope.oneItem.packaging_id = value.packaging_id;
                    $scope.oneItem.expired_date = value.expiry_date;
                    $scope.oneItem.lot_number = value.lot_number;
                    $scope.oneItem.u_price = value.unit_price;
                    $scope.oneItem.store_id = value.store_id;
                    $scope.oneItem.activity_id = value.activity_id;
                    $scope.oneItem.activity = value.activity_id;
                    $scope.oneItem.packaging = value.packaging.dose_per_vial;
                    $scope.oneItem.dose_vial = value.packaging.dose_per_vial;
                    $scope.oneItem.vials_per_box = value.packaging.vials_per_box;
                    $scope.oneItem.cm_per_dose = value.packaging.cm_per_dose;
                    $scope.oneItem.item_id = value.vaccine.id;
                    $scope.oneItem.item_type = value.vaccine.type;
                    $scope.oneItem.diluent_id = value.vaccine.diluent_id;
                    $scope.oneItem.require_diluent = value.vaccine.require_diluent;
                    $scope.oneItem.source_id = value.source_id;
                    $scope.oneItem.item = value.vaccine.name;
                    $scope.oneItem.manufacture_id = value.packaging.manufacture_id;
                }
            })
        }

        //updating an Item
        $scope.currentSaving = false;
        $scope.adjustdispatch = function(item){
            $scope.currentSaving = true;
            $http.post("index.php/adjustdispatch/", item).success(function (d) {
                $scope.showSummary = true;
                $scope.newItem.voucher_no = d;
                $mdToast.show(
                    $mdToast.simple()
                        .content($translate('error.stock_dispatched_successfull'))
                        .position($scope.getToastPosition())
                        .hideDelay(5000)
                );
                $scope.currentSaving = false;

            }).error(function(){
                $mdToast.show(
                    $mdToast.simple()
                        .content($translate('error.stock_dispatched_falure'))
                        .position($scope.getToastPosition())
                        .hideDelay(5000)
                );
                $scope.currentSaving = false;
            })
        }


        //cancel an adjustment
        $scope.currentSaving4 = false;
        $scope.cancelDispatch = function(item){
            $scope.currentSaving1 = true;
            $http.post("index.php/cancelDispatch/"+item).success(function (newItem) {
                $scope.newItem = {};
                $mdToast.show(
                    $mdToast.simple()
                        .content($translate('error.stock_adjusted_successfull'))
                        .position($scope.getToastPosition())
                        .hideDelay(5000)
                );
                $scope.currentSaving4 = false;

            }).error(function(){
                $mdToast.show(
                    $mdToast.simple()
                        .content($translate('error.stock_adjusted_falure'))
                        .position($scope.getToastPosition())
                        .hideDelay(5000)
                );
                $scope.currentSaving4 = false;
            })

        }

        //react to changing recipients
        $scope.changeRecipient = function(id){
            $scope.currentAnnual = [];
            angular.forEach($scope.userRecipients,function(value){
                if(value.id == id){
                    $scope.newItem.transport_mode = value.transport_mode_id;
                }
            });
            var i = 0;
            angular.forEach($scope.annual_quota,function(value){
                if(value.recipient_id == id){
                    i++;
                    $scope.currentAnnual.push(value);
                }
            });
            if(i == 0){
                $scope.noAnnualQuota = true;
            }else{
                $scope.noAnnualQuota = false;
            }
        }


        //adding Item to list
        $scope.showAdd = function(){

            $scope.oneItem = {};
            $scope.editing = false;
            $scope.dilluentRequired = false;
            var modalInstance = $modal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'views/dispatch/oneItem.html',
                scope: $scope,
                controller: 'dispatchModalInstanceCtrl',
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
                templateUrl: 'views/dispatch/oneItem.html',
                scope: $scope,
                controller: 'dispatchModalInstanceCtrl',
                size: "lg",
                "backdrop":"static"
            });
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

        $scope.cancelDispatch = function(){
            $scope.newItem = {};
            $scope.newItem.dispatch_date = new Date();
            $scope.newItem.items = [];
            $scope.oneItem = {};
            $scope.maxexceed = false;
            $scope.showSummary = false;
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

    });