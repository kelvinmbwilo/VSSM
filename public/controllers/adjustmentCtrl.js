/**
 * Created by kelvin on 9/15/15.
 */

angular.module("vssmApp")
    .controller("adjustmentCtrl",function ($scope,$http,$mdDialog,$mdToast,$modal,$translate,$filter) {

        $scope.fetchBasicData();
        $scope.newItem = {};

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

        $scope.cancel = function(){
            $scope.newItem.stock_id = null
            $scope.newItem={};
        }

        $scope.cancel1 = function(){
            $scope.newItem.stock_id = null
            $scope.newItem={};
        }

        var $translate = $filter('translate');
        //updating an Item
        $scope.currentSaving = false;



        $scope.currentSaving3 = false;
        $scope.saveStockAdjust = function(item){
            $scope.currentSaving2 = true;
            $http.post("index.php/stock_adjust/", item).success(function (ref) {
                $mdToast.show(
                    $mdToast.simple()
                        .content($translate('error.stock_adjusted_successfull1',{ reference: ref }))
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
            $http.post("index.php/move_item", item).success(function (ref) {
                $scope.newItem = {};
               $mdToast.show(
                    $mdToast.simple()
                        .content($translate('error.stock_moved_successfull',{ reference: ref }))
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

        $scope.fetchBasicData();
        $scope.newItem = {};
        $scope.newItem.items = [];
        $scope.oneItem = {};
        $scope.maxexceed = false;
        $scope.showSummary = false;
        //get dispatched_packages
        $scope.getDispatchesTocancel  = function(){
            $http.get("index.php/packagesToCancel").success(function(data){
                $scope.expect_packages = [];
                angular.forEach(data,function(value){
                    value.name = value.voucher_number +" to: "+value.destination.name+' '+value.date_sent;
                    $scope.expect_packages.push(value);
                });
            });
        }
        $scope.getDispatchesTocancel();


        //prepare an adjustment item to udjust
        $scope.canCancel = false;
        $scope.getDispatchItem = function(id){
            angular.forEach($scope.expect_packages,function(v){
                if(v.id == id){
                    $scope.newItem.id = v.id;
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
                    });
                    $scope.canCancel = true;
                    $scope.checkVaccineDiluent();

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
                value.usename = value.vaccine.name +" , "+ value.lot_number+" , "+value.store.name+", "+value.expiry_date+", "+ value.amount +" Doses, Source: "+$scope.getSourceName(value.source_id);
            });
        });

        //changing the item dropdown
        $scope.updateStock = function(id){
            angular.forEach($scope.currentAnnual,function(value){
                if(value.item_id == id){
                    $scope.annual_need = value.expected_annual_need
                }
            });
            var i = 0;
            angular.forEach($scope.stock_items,function(value){
                if(value.vaccine_id == id){
                    i++;
                }
            });
            if(i==0){
                $scope.maxexceed = true;
                $scope.oneItem.doses = 0;
                $scope.oneItem.stock_id = "";
            }else{
                $scope.maxexceed = false;
            }

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

        $scope.getTotalPrice = function(){
            var total = 0;
            angular.forEach($scope.newItem.items,function(item){
                total += item.u_price * item.doses;
            });
            return total;
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



        $scope.sendCancelDispatch = function(ev,item){
            var confirm = $mdDialog.confirm()
                .title($translate('labels.confirm_cancelTransaction'))
                .content($translate('labels.irreversible_warning'))
                .ariaLabel('Lucky day')
                .ok($translate('help.delete'))
                .cancel($translate('labels.cancel'))
                .targetEvent(ev);
            $mdDialog.show(confirm).then(function() {
                $scope.currentSaving = true;
                $http.post("index.php/cancelDispatch/"+item.id).success(function (newVal) {
                    $scope.currentSaving = false;
                    console.log(newVal)
                    $scope.cancelDispatch();
                    $scope.getDispatchesTocancel();
                    $mdToast.show(
                        $mdToast.simple()
                            .content($translate('error.delete_success'))
                            .position($scope.getToastPosition())
                            .hideDelay(3000)
                    );
                }).error(function(){
                    $scope.currentSaving = false;
                    $mdToast.show(
                        $mdToast.simple()
                            .content($translate('error.delete_falure'))
                            .position($scope.getToastPosition())
                            .hideDelay(5000)
                    );
                });
            }, function() {

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
        //updating an Item
        $scope.currentSaving = false;
        $scope.adjustdispatch = function(item){
            $scope.currentSaving = true;
            $http.post("index.php/dispatch_adjust/", item).success(function (d) {
                $scope.showSummary = true;
                $scope.newItem.voucher_no = d;
                $scope.getDispatchesTocancel();
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
            $scope.newItem.items = [];
            $scope.oneItem = {};
            $scope.maxexceed = false;
            $scope.showSummary = false;
            $scope.canCancel = false;
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

        $scope.downloadData =function(){
            var doc = new jsPDF('p', 'pt');

            var specialElementHandlers = {
                '#editor': function(element, renderer){
                    return true;
                }
            };

// All units are in the set measurement for the document
// This can be changed to "pt" (points), "mm" (Default), "cm", "in"
            var datas = [];
            var i = 1;
            var total_price = 0;
            angular.forEach($scope.newItem.items,function(data){
                total_price+= data.u_price * data.doses;
                datas.push(
                    {'sn':i,'item':data.item,'batch_number':data.lot_number,'manufacture':$scope.getManufactureName(data.manufacture_id),'expire_date':data.expired_date,'doses':data.doses,'vials':data.doses/data.dose_vial,'t_price':data.u_price * data.doses}
                );
                i++;
            });
            var columns = [
                {title: "S/N", key: "sn"},
                {title: $translate('labels.product'), key: "item"},
                {title: $translate('labels.lot_number'), key: "batch_number"},
                {title: $translate('labels.manufacture'), key: "manufacture"},
                {title: $translate('labels.expired_date'), key: "expire_date"},
                {title: $translate('labels.doses'), key: "doses"},
                {title: $translate('labels.vials'), key: "vials"},
                {title: $translate('labels.t_price'), key: "t_price"}
            ];
            var data = datas;
//            doc.fromHTML(html, 15, 15, {
//                'width': 280,
//                'elementHandlers': specialElementHandlers
//            });


            var imgData ="data:image/jpg;base64,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";
            var imgData1 ="data:image/jpg;base64,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";
            var name = $scope.getRecipientName($scope.newItem.recipients)+"_"+$scope.newItem.voucher_no;


            doc.autoTable(columns, data, {
                padding: 3, // Horizontal cell padding
                fontSize: 10,
                lineHeight: 15,
                border: 1,
                renderHeader: function (doc, pageNumber, settings) {
                    doc.setFontSize('17');
                    doc.text(220, 38, $translate('app.title'));
                    doc.text(155, 55, $translate('app.voucher_title'));
                    doc.setFontSize('11');
                    doc.text(40, 105, $translate('labels.entered_by',{name:$scope.logedInUserName}));
                    doc.text(230, 105, $translate('labels.issued_to',{name:$scope.getRecipientName($scope.newItem.recipients)}));
                    doc.text(395, 105, $translate('labels.transport_mode1',{name:$scope.getTransportName($scope.newItem.transport_mode)}));
                    doc.setFontSize('13');
                    doc.text(40, 128, $translate('labels.voucher_no'));
                    doc.text(160,128,$scope.newItem.voucher_no );
//                    var barcode = "http://barcode.tec-it.com/barcode.ashx?code=Code128&modulewidth=fit&data="+$scope.newItem.voucher_no+"&dpi=72&imagetype=png&rotation=90&color=&bgcolor=&fontcolor=&quiet=0&qunit=mm"
                    doc.setFontStyle('bold');
                    doc.addImage(imgData1, 'PNG', 40, 15, 70, 70);
                    doc.addImage(imgData, 'PNG', 500, 15, 70, 70);
                }, // Called before every page
                renderFooter: function (doc, lastCellPos, pageNumber, settings) {
                    doc.text(lastCellPos.x,lastCellPos.y+40, $translate('labels.total_price',{price:total_price,currency:$scope.main_currency}));
                    doc.text(lastCellPos.x,lastCellPos.y+70, $translate('labels.issued'));
                    doc.text(lastCellPos.x,lastCellPos.y+100, $translate('labels.name_signature'));
                    doc.text(lastCellPos.x,lastCellPos.y+130, $translate('labels.sign'));

                    doc.text(lastCellPos.x+395,lastCellPos.y+70, $translate('labels.receiver'));
                    doc.text(lastCellPos.x+395,lastCellPos.y+100, $translate('labels.name_signature'));
                    doc.text(lastCellPos.x+395,lastCellPos.y+130, $translate('labels.sign'));


                }, // Called on the end of every page
                renderHeaderCell: function (x, y, width, height, key, value, settings) {
                    doc.setFillColor(52, 73, 94); // Asphalt
                    doc.setTextColor(255, 255, 255);
                    doc.setFontStyle('bold');
                    doc.setFontSize('11');
                    doc.rect(x, y, width, height, 'F');
                    y += settings.lineHeight / 2 + doc.internal.getLineHeight() / 2 - 2.5;
                    doc.text('' + value, x + settings.padding, y);
                },
                renderCell: function (x, y, width, height, key, value, row, settings) {
//                    doc.setFillColor(row % 2 === 0 ? 215 : 255);
                    doc.setFontSize('11');
                    doc.rect(x, y, width, height, 'S');
                    y += settings.lineHeight / 2 + doc.internal.getLineHeight() / 2 - 2.5;
                    doc.text('' + value, x + settings.padding, y);
                },
                margins: { horizontal: 40, top: 150, bottom: 40 }, //How much space around the table
                startY: false, //The start Y position on the first page. If set to false, top margin is used
                avoidPageSplit: false, //Avoid splitting table over multiple pages (starts drawing table on fresh page instead).
                extendWidth: true //If true, the table will span 100% of page width minus horizontal margins.
            });
            doc.save(name+'.pdf');

//            doc.save('test.pdf')

        }
    })
    .controller("arrivalAdjustmentCtrl",function ($scope,$http,$mdDialog,$mdToast,$modal,$translate,$filter){
        $scope.newItem = {};
        $scope.fetchBasicData();
        $scope.getCanceledItems = function(){
            $http.get("index.php/arrivalsforcancel").success(function(data){
                $scope.arrivals = [];
                angular.forEach(data,function(value){
                    var name = (value.from_source)?"from"+value.from_source.name+' #':' ';
                    value.name = name+value.reference+' , '+value.arrival_date
                    $scope.arrivals.push(value);
                });
            });
        }
        $scope.getCanceledItems();


        $scope.canCancel = false;
        $scope.getArrivalItem = function(id){

            angular.forEach($scope.arrivals,function(v){
                if(v.id == id){
                    $scope.newItem.id = v.id;
                    $scope.newItem.source = v.source;
                    $scope.newItem.arrival_report_no = v.arrival_report_number;
                    $scope.newItem.arrival_date      = v.arrival_date;
                    $scope.newItem.freight_cost      = v.freight_cost;
                    $scope.newItem.packed_volume     = v.package_volume;
                    $scope.newItem.total_weight      = v.total_weight;
                    $scope.newItem.notes             = v.notes;
                    $scope.newItem.main_currency     = v.main_currency;
                    $scope.newItem.used_currency     = v.used_currency;
                    $scope.newItem.currency_of_bill  = v.currency_of_bill;
                    $scope.newItem.exchange_rate     = v.exchange_rate;
                    $scope.newItem.reference         = v.reference;
                    $scope.newItem.items = [];
                    angular.forEach(v.arrival_items,function(value){
                        var item = {};
                        angular.forEach($scope.packaging_information,function(val){
                            if(val.id == value.packaging_id){

                                item.packaging_id = value.packaging_id;
                                item.expired_date = value.expiry_date;
                                item.lot_number = value.lot_number;
                                item.u_price = value.unit_price;
                                item.t_price = value.unit_price * value.number_received;
                                item.total_volume = val.cm_per_dose * value.number_received * 0.001;
                                item.vials =  value.number_received/val.dose_per_vial;
                                item.store_id = value.store_id;
                                item.activity_id = value.activity_id;
                                item.dose_vial = val.dose_per_vial;
                                item.doses = value.number_received;
                                item.vials_per_box = val.vials_per_box;
                                item.cm_per_dose = val.cm_per_dose;
                                item.item_id = val.vaccine.id;
                                item.item_type = val.vaccine.type;
                                item.diluent_id = val.vaccine.diluent_id;
                                item.source_id = value.source_id;
                                item.item = val.vaccine.name;
                                item.manufacture_id = val.manufacture_id;
                            }
                        });
                        $scope.newItem.items.push(item);
                    });
//                    if(v.receiving_status == 'pending'){
                    $scope.canCancel = true;
//                    }

                }
            });
        }

        $scope.cancel2 = function(){
            $scope.canCancel = false;
            $scope.newItem={};
        }

        $scope.canCancelyes = false;
        var $translate = $filter('translate');
        $scope.cancelarrival = function(ev,item) {
            $scope.canCancelyes = true;
        }
        $scope.cancel3 = function(ev,item) {
            $scope.canCancelyes = false;
        }

        $scope.cancelarrival1 = function(ev,item) {
            $scope.currentSaving = true;
            $http.post("index.php/arrival_adjust/", item).success(function (newVal) {
                $scope.currentSaving = false;
                $scope.canCancelyes = false;
                $scope.cancel2();
                $scope.getCanceledItems();
                $mdToast.show(
                    $mdToast.simple()
                        .content($translate('error.delete_success'))
                        .position($scope.getToastPosition())
                        .hideDelay(3000)
                );
            }).error(function(){
                $scope.currentSaving = false;
                $mdToast.show(
                    $mdToast.simple()
                        .content($translate('error.delete_falure'))
                        .position($scope.getToastPosition())
                        .hideDelay(5000)
                );
            });

        };

    });