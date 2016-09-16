/**
 * Created by kelvin on 9/4/15.
 */
angular.module("vssmApp")
    .controller("openStockCtrl",function ($scope,$http,$mdDialog,$mdToast,$modal,$translate,$filter,$timeout) {
                //var barcode structure = ]d20118901415009581171601311030124A14
                //{"lot_number":"30124A14","expiry":"160131","gtin":"18901415009581"}
                $scope.barcode = {};
                $scope.newItem = {};
                $scope.newItem.items = [];
                $scope.oneItem = {};
                $scope.hasItems = false;

        $("#scan_item1").focus();

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

                    //#scan_item
                };

        //this function will be used for the front scanning
        $scope.prepareItems1 = function(str){
            if(str != ''){
                $scope.showNotAvailableError = false;
                $scope.itemFound = false;
                $scope.barcode ={};
                $scope.barcode.str = angular.copy(str);
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
                var pcakage_found = false;
                angular.forEach($scope.packaging_information,function(value){
                    if(value.GTIN == $scope.barcode.gtin){
                        pcakage_found = true;
                        $scope.oneItem.packaging_id = value.id;
                        $scope.oneItem.packaging = value.usename;
                        $scope.oneItem.dose_vial = value.dose_per_vial;
                        $scope.oneItem.doses            = value.dose_per_vial * value.vials_per_box;
                        $scope.oneItem.vials_per_box    = value.vials_per_box;
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

                if(pcakage_found){
                    $scope.showNotAvailableError = false;
                    $scope.itemFound = true;
                    $scope.barcode.vaccine = angular.copy($scope.oneItem.item);
                    $scope.barcode.dose_vial = angular.copy($scope.oneItem.dose_vial);
                    var is_available = false;
                    angular.forEach($scope.newItem.items,function(new_item){
                        console.log(new_item.lot_number +'=='+ $scope.barcode.lot_number);
                        if(new_item.lot_number == $scope.barcode.lot_number){
                            new_item.doses +=  $scope.oneItem.doses;
                            is_available = true;
                            $scope.oneItem = {};
                        }
                    });
                    if(!is_available){
                        $scope.oneItem.total_volume = $scope.oneItem.cm_per_dose * $scope.oneItem.doses * 0.001;
                        $scope.oneItem.vials = $scope.oneItem.doses / $scope.oneItem.dose_vial;
                        $scope.newItem.items.push($scope.oneItem);
                        $scope.oneItem = {};
                    }
                    $("#scan_item1").val('');
                    angular.element(jQuery('#scan_item1')).triggerHandler('input');
                    $scope.showNotAvailableError = false;
                    $scope.itemFound = true;
                }
                else{
                    $scope.showNotAvailableError = true;
                    $scope.itemFound = false;
                    $timeout(function(){
                        $scope.showNotAvailableError = false;
                    },3000);

                    $("#scan_item1").val('');
                    angular.element(jQuery('#scan_item1')).triggerHandler('input');

                    var snd = new Audio("data:audio/wav;base64,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qJrx4KOQjahZxWbcZgztj2c49nKmkId44S71j0c8eV9yDK6uPRzx5X18eDvjvQ6yKo9ZSS6l//8elePK/Lf//IInrOF/FvDoADYAGBMGb7FtErm5MXMlmPAJQVgWta7Zx2go+8xJ0UiCb8LHHdftWyLJE0QIAIsI+UbXu67dZMjmgDGCGl1H+vpF4NSDckSIkk7Vd+sxEhBQMRU8j/12UIRhzSaUdQ+rQU5kGeFxm+hb1oh6pWWmv3uvmReDl0UnvtapVaIzo1jZbf/pD6ElLqSX+rUmOQNpJFa/r+sa4e/pBlAABoAAAAA3CUgShLdGIxsY7AUABPRrgCABdDuQ5GC7DqPQCgbbJUAoRSUj+NIEig0YfyWUho1VBBBA//uQZB4ABZx5zfMakeAAAAmwAAAAF5F3P0w9GtAAACfAAAAAwLhMDmAYWMgVEG1U0FIGCBgXBXAtfMH10000EEEEEECUBYln03TTTdNBDZopopYvrTTdNa325mImNg3TTPV9q3pmY0xoO6bv3r00y+IDGid/9aaaZTGMuj9mpu9Mpio1dXrr5HERTZSmqU36A3CumzN/9Robv/Xx4v9ijkSRSNLQhAWumap82WRSBUqXStV/YcS+XVLnSS+WLDroqArFkMEsAS+eWmrUzrO0oEmE40RlMZ5+ODIkAyKAGUwZ3mVKmcamcJnMW26MRPgUw6j+LkhyHGVGYjSUUKNpuJUQoOIAyDvEyG8S5yfK6dhZc0Tx1KI/gviKL6qvvFs1+bWtaz58uUNnryq6kt5RzOCkPWlVqVX2a/EEBUdU1KrXLf40GoiiFXK///qpoiDXrOgqDR38JB0bw7SoL+ZB9o1RCkQjQ2CBYZKd/+VJxZRRZlqSkKiws0WFxUyCwsKiMy7hUVFhIaCrNQsKkTIsLivwKKigsj8XYlwt/WKi2N4d//uQRCSAAjURNIHpMZBGYiaQPSYyAAABLAAAAAAAACWAAAAApUF/Mg+0aohSIRobBAsMlO//Kk4soosy1JSFRYWaLC4qZBYWFRGZdwqKiwkNBVmoWFSJkWFxX4FFRQWR+LsS4W/rFRb/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////VEFHAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAU291bmRib3kuZGUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAMjAwNGh0dHA6Ly93d3cuc291bmRib3kuZGUAAAAAAAAAACU=");
                    snd.play();
                }
            }


        };

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
        };


        //setting store type
        $scope.setStoreType1 = function(item, id){
            angular.forEach($scope.stores,function(value){
                if(value.id == id){
                    item.store_type = value.store_type;
                    item.used = (value.used_volume/value.net_volume)*1000;
                    item.used1 = ((value.net_volume - value.used_volume)*1000)/item.cm_per_dose;
                }
            });
        };

        $scope.isArrivalSafe = function(){
            var i =0;
            angular.forEach($scope.newItem.items,function(value){
                if(value.hasOwnProperty('expired_date')
                    && value.hasOwnProperty('lot_number')
                    && value.hasOwnProperty('u_price')
                    && value.hasOwnProperty('store_id')
                    && value.hasOwnProperty('activity')
                ){
                    i++;
                }
            });
            if(i == $scope.newItem.items.length){
                $scope.checksave = true;
                return true;
            }else{
                $scope.checksave = false;
                return false;
            }
        };

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