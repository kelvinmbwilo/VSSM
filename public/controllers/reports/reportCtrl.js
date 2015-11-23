/**
 * Created by kelvin on 9/22/15.
 */
angular.module("vssmApp")
    .controller("reportCtrl",function ($scope,$http,$mdDialog,$mdToast,$modal,$translate,$filter,DTOptionsBuilder, DTColumnBuilder) {
        //get vaccines
        $http.get("index.php/vaccines").success(function(data){
            $scope.vaccines = data;
        });

        $scope.selected_level = '1';

        $scope.childs = $scope.userRecipients;
        $scope.childs.unshift($scope.logedInUser.recipient);
        $scope.data.children = $scope.logedInUser.recipient.id;

        $scope.number_of_notification = 0
        $scope.number_close_to_expiry = 0
        $scope.number_below_minimum = 0
        $scope.number_above_maximum = 0
        $scope.number_expired_items = 0
        $scope.notification_object = [];
        $http.get("index.php/near_expired_stock_items").success(function(data){
            $scope.near_expired_stock_items = data;
            angular.forEach($scope.near_expired_stock_items,function(value){

                value.vaccine = $scope.assignValue($scope.vaccines,value.vaccine_id);
                value.packaging = $scope.assignValue($scope.packaging_information,value.packaging_id);
                value.store = $scope.assignValue($scope.stores,value.store_id);
                $scope.notification_object.push({'url':'close_to_expiry','name':'Near Expired Item','descr':value.vaccine.name +" of Batch Number "+ value.lot_number+' Will Expire At ' +value.expiry_date })
                $scope.number_of_notification += 1;
                $scope.number_close_to_expiry += 1;
                value.usename = value.vaccine.name +" , "+ value.lot_number+" , "+value.store.name+", "+value.expiry_date+", "+ value.amount +" Doses, Source: "+$scope.getSourceName(value.source_id);
            });
        });

        $http.get("index.php/vaccineStocks/1").success(function(data){
            $scope.stockss = data;
            $scope.below_minimum = []
            $scope.above_maximum = []
            angular.forEach($scope.stockss,function(value){
                angular.forEach($scope.items_min_max,function(val){
                    if(value.id == val.item_id){
                        value.itemMinMax =  val;
                        if(parseInt(value.amount) > parseInt(val.max_value)){
                            $scope.above_maximum.push(value);
                            $scope.notification_object.push({'url':'above_maximum','name':'Item Above Maximum','descr':value.itemMinMax.vaccine.name +" is Above Maximum Settled Value, Current Number of Dose is "+ value.amount+' and has maximum of ' +value.itemMinMax.max_value })
                            $scope.number_of_notification += 1;
                            $scope.number_above_maximum += 1;
                        }else if(parseInt(value.amount) < parseInt(val.min_value)){
                            $scope.below_minimum.push(value)
                            $scope.notification_object.push({'url':'below_minimum','name':'Item Below Minimum','descr':value.itemMinMax.vaccine.name +" is Below Minimum Settled Value, Current Number of Dose is "+ value.amount+' and has minimum of ' +value.itemMinMax.min_value })
                            $scope.number_of_notification += 1;
                            $scope.number_below_minimum += 1;
                        }
                    }
                })
            })
        });


        $scope.updateExpired = function(){
            //get stock_items
            $http.get('index.php/expired_stock_items/'+$scope.data.children+'/child/'+$scope.selected_level).success(function(data){
                $scope.stock_items = data;
                angular.forEach($scope.stock_items,function(value){
                    value.vaccine = $scope.assignValue($scope.vaccines,value.vaccine_id);
                    value.packaging = $scope.assignValue($scope.packaging_information,value.packaging_id);
                    value.store = $scope.assignValue($scope.stores,value.store_id);
                    $scope.notification_object.push({'url':'expired_items','name':'Expired Item','descr':value.vaccine.name +" of Batch Number "+ value.lot_number+' Has expired Since ' +value.expiry_date })
                    $scope.number_of_notification += 1;
                    $scope.number_expired_items += 1;
                    value.usename = value.vaccine.name +" , "+ value.lot_number+" , "+value.store.name+", "+value.expiry_date+", "+ value.amount +" Doses, Source: "+$scope.getSourceName(value.source_id);
                });
            });

        }
        //get stock_items
        $http.get("index.php/expired_stock_items").success(function(data){
            $scope.stock_items = data;
            angular.forEach($scope.stock_items,function(value){
                value.vaccine = $scope.assignValue($scope.vaccines,value.vaccine_id);
                value.packaging = $scope.assignValue($scope.packaging_information,value.packaging_id);
                value.store = $scope.assignValue($scope.stores,value.store_id);
                $scope.notification_object.push({'url':'expired_items','name':'Expired Item','descr':value.vaccine.name +" of Batch Number "+ value.lot_number+' Has expired Since ' +value.expiry_date })
                $scope.number_of_notification += 1;
                $scope.number_expired_items += 1;
                value.usename = value.vaccine.name +" , "+ value.lot_number+" , "+value.store.name+", "+value.expiry_date+", "+ value.amount +" Doses, Source: "+$scope.getSourceName(value.source_id);
            });
        });
    }).controller("cancelInvoiceCtrl",function ($scope,$http,$mdDialog,$mdToast,$modal,$translate,$filter,DTOptionsBuilder, DTColumnBuilder) {
        $scope.selected_level = '1';

        $scope.childs = $scope.userRecipients;
        $scope.childs.unshift($scope.logedInUser.recipient);
        $scope.data.children = $scope.logedInUser.recipient.id;

//getting screening types

        $scope.updateCanceledInvoice = function(){
            $http.get('index.php/canceledarrivalItems/'+$scope.data.children+'/child/'+$scope.selected_level).success(function(data){
                $scope.canceled_arrival_invoices = data;
            });
            $http.get('index.php/canceledDisItems/'+$scope.data.children+'/child/'+$scope.selected_level).success(function(data){
                $scope.canceled_dispatch_invoices = data;
            });
        }
        $scope.updateCanceledInvoice();
//        //getting canceled_arrival_invoices
//        $http.get('index.php/canceledarrivalItems').success(function(data){
//            $scope.canceled_arrival_invoices = data;
//        });
//
//        //getting canceled_dispatch_invoices
//        $http.get('index.php/canceledDisItems').success(function(data){
//            $scope.canceled_dispatch_invoices = data;
//        });

        //getting adjustedItems
        $scope.updateadjusted = function(){
        $http.get('index.php/adjustedItems/'+$scope.data.children+'/child/'+$scope.selected_level).success(function(data){
            $scope.adjustedItems = data;
            angular.forEach($scope.stock_items,function(value){
                value.vaccine = $scope.assignValue($scope.vaccines,value.vaccine_id);
                value.packaging = $scope.assignValue($scope.packaging_information,value.packaging_id);
                value.store = $scope.assignValue($scope.stores,value.store_id);
            });

        });
        };
        $scope.updateadjusted();

        //getting movedItems
        $scope.updateItemMoved = function(){
        $http.get('index.php/movedItems/'+$scope.data.children+'/child/'+$scope.selected_level).success(function(data){
            $scope.movedItems = data;
        });
        };
        $scope.updateItemMoved();
    });