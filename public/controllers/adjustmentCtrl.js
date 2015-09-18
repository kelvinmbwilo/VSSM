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

        //get Activities
        $http.get("index.php/activities").success(function(data){
            $scope.activities = data;
        });
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

        $scope.getDispatchItem = function(id){
            console.log(id);
            angular.forEach($scope.expected_packages,function(v){
               if(v.id == id){
                   $scope.dispatchItems = [];
                   angular.forEach(v.items,function(value){
                       angular.forEach($scope.packaging_information,function(val){
                           if(val.id == value.packaging_id){
                               value.packaging = val;
                           }
                       });
                       value.name = value.packaging.vaccine.name+" "+value.batch_number+ ", "+value.amount+"doses";
                       $scope.dispatchItems.push(value);
                   });
               }
            });

        }
        //get packaging_information
        $http.get("index.php/packaging_information").success(function(data){
            $scope.packaging_information = data;
        });

        //get stores
        $http.get("index.php/stores").success(function(data){
            $scope.stores = data;
            angular.forEach($scope.stores,function(value){
                //var freeVol = ((value.net_volume - value.used_volume)/value.net_volume)*100;
                var freeVol = value.net_volume - value.used_volume;
                value.useName = value.name+", Volume: "+value.net_volume+" Used: "+value.used_volume+" Free: "+ freeVol;
            });

        });
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
            var vol = val * $scope.cm_per_dose* 0.001
            if(($scope.selectedStore1.net_volume - $scope.selectedStore1.used_volume) > vol){
                $scope.validVolume = true;
            }else{
                $scope.validVolume = false;
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
        //get sources
        $http.get("index.php/sources").success(function(data){
            $scope.sources = data;
        });

        //get adjustment_reasons
        $http.get("index.php/adjustment_reasons").success(function(data){
            $scope.adjustment_reasons = data;
        });

        //get sent_packages
        $http.get("index.php/dispatched_packages").success(function(data){
            $scope.expect_packages = [];
            angular.forEach(data,function(value){
                value.name = value.voucher_number +" to: "+value.destination.name+' '+value.date_sent;
                $scope.expect_packages.push(value);
            });
        });

        $http.get("index.php/annual_quota").success(function(data){
            $scope.annual_quota = data;
        });

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
        $scope.open3 = function($event) {
            $scope.status.opened3 = true;
        };
        $scope.open2 = function($event) {
            $scope.status.opened2 = true;
        };
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

        $scope.currentSaving2 = false;
        $scope.saveDispatchAdjust = function(item){
            $scope.currentSaving2 = true;
            $http.post("index.php/dispatch_adjust/", item).success(function (newItem) {
                $mdToast.show(
                    $mdToast.simple()
                        .content($translate('error.stock_adjusted_successfull'))
                        .position($scope.getToastPosition())
                        .hideDelay(5000)
                );
                $scope.currentSaving2 = false;
                $scope.newItem = null;
                //get Arrivals

            }).error(function(){
                $mdToast.show(
                    $mdToast.simple()
                        .content($translate('error.stock_adjusted_falure'))
                        .position($scope.getToastPosition())
                        .hideDelay(5000)
                );
                $scope.currentSaving2 = false;
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
                //get stock_items
                $http.get("index.php/stock_items").success(function(data){
                    $scope.stock_items = data;
                    angular.forEach($scope.stock_items,function(value){
                        value.name = value.vaccine.name +" , "+ value.lot_number+" , "+value.store.name+", "+value.amount+$translate('labels.doses')+", "+value.expiry_date
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

    });