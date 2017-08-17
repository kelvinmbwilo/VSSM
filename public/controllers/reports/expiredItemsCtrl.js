/**
 * Created by kelvin on 9/22/15.
 */
angular.module("vssmApp")
    .controller("expiredItemsCtrl",function ($scope,$http,$mdDialog,$mdToast,$modal,$translate,$filter,DTOptionsBuilder, DTColumnBuilder) {
//getting all regions
        $scope.data = {};
        $scope.data.usedRegions = [];
        $scope.data.usedDistricts = [];
        $scope.data.usedWards = [];
        $scope.data.usedVillages = [];
        $scope.data.report_type = "Order_Status";
        $scope.data.category = "Years"
        $scope.data.reportMainCategory = "Orders";
        $scope.data.reportSeries = "Order Status";
        $scope.data.selectedMonthYear = "2015";
        $scope.data.main_cat = "doses";
        $scope.data.reportPeriod = "Years"
        $scope.table = {};
        $scope.data.recipient = "";
        $scope.data.sources = "";
        $scope.data.activity = "";
        $scope.data.store = "";
        $scope.data.main_date = "system_date";
        $scope.selected_level = '1';

        $scope.childs = $scope.userRecipients;
        $scope.childs.unshift($scope.logedInUser.recipient);
        $scope.data.children = $scope.logedInUser.recipient.id;

        //preparing start date and end date
        var date = new Date();
        var curr_date	= date.getDate();
        var curr_month	= date.getMonth()+1;
        var curr_year 	= date.getFullYear();
        if(curr_month<10){
            curr_month="0"+curr_month;
        }
        if(curr_date<10){
            curr_date="0"+curr_date;
        }
        if(curr_month == 1){
            var curr_month1	= 12;
        }else{
            var curr_month1	= date.getMonth();
        }
        $scope.data.startDate = curr_year+"-"+curr_month1+"-"+curr_date;
        $scope.data.endDate = curr_year+"-"+curr_month+"-"+curr_date;

        $scope.data.orderCategory = [{ name: "Pending", ticked: true },{ name: "In Progress", ticked: true},{ name: "Complete", ticked: true},{name: "Declined", ticked: true}]
        $scope.data.generalCategory = [{ name: "Good", ticked: true },{ name: "Average", ticked: true},{ name: "Bad", ticked: true}];
        $scope.data.months = [{ name: "January", ticked: true },{ name: "February", ticked: true},{ name: "March", ticked: true},{ name: "April", ticked: true},{ name: "May", ticked: true},{ name: "June", ticked: true},{ name: "July", ticked: true},{ name: "August", ticked: true},{ name: "September", ticked: true},{ name: "October", ticked: true},{ name: "November", ticked: true},{ name: "December", ticked: true}];
        $scope.data.years = [{ name: "2015", ticked: true },{ name: "2014", ticked: true}];
        $scope.startingYears = [];
        angular.forEach($scope.data.years,function(datta){
            $scope.startingYears.push(datta.name);
        });

        //preparing date range pickers
        $scope.dateOptions1 = {
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            onClose: function( selectedDate ) {
                $scope.dateOptions2.minDate= selectedDate;
            }
        };
        $scope.dateOptions2 = {
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            onClose: function( selectedDate ) {
                $scope.dateOptions1.maxDate = selectedDate;
            }
        };

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
                value.store = $scope.assignValue($scope.allstores,value.store_id);
                $scope.notification_object.push({'url':'close_to_expiry','name':$filter('translate')('labels.near_expired_item'),'descr':value.vaccine.name +" "+$filter('translate')('labels.of_batch_number')+" "+ value.lot_number+" "+$filter('translate')('labels.will_expire_at')+" "+value.expiry_date })
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
                            $scope.notification_object.push({'url':'above_maximum','name':$filter('translate')('labels.above_maximum_value'),'descr':value.itemMinMax.vaccine.name +" "+ $filter('translate')('labels.is_above_maximum_settled_value') +","+ $filter('translate')('labels.current_number_of_dose_is')+" " +value.itemMinMax.max_value });
                            $scope.number_of_notification += 1;
                            $scope.number_above_maximum += 1;
                        }else if(parseInt(value.amount) < parseInt(val.min_value)){
                            $scope.below_minimum.push(value)
                            $scope.notification_object.push({'url':'below_minimum','name': $filter('translate')('labels.item_below_minimum'),'descr':value.itemMinMax.vaccine.name +" "+ $filter('translate')('labels.is_below_minimum_settled_value') +", "+$filter('translate')('labels.current_number_of_dose_is')+" "+ value.amount+" "+$filter('translate')('labels.and_has_minimum_of')+" "+value.itemMinMax.min_value })
                            $scope.number_of_notification += 1;
                            $scope.number_below_minimum += 1;
                        }
                    }
                })
            })
        });

        //getting screening types
        
        $scope.fetchData = function(){
            //get stock_items
            $http.get("index.php/expired_stock_items").success(function(data){
            $scope.stock_items = data;
            angular.forEach($scope.stock_items,function(value){
                value.vaccine = $scope.assignValue($scope.vaccines,value.vaccine_id);
                value.packaging = $scope.assignValue($scope.packaging_information,value.packaging_id);
                value.store = $scope.assignValue($scope.allstores,value.store_id);
                $scope.notification_object.push({'url':'expired_items','name': $filter('translate')('labels.expired_item'),'descr':value.vaccine.name +" "+ $filter('translate')('labels.of_batch_number')+" "+ value.lot_number+" "+ $filter('translate')('labels.has_expired_since')+" "+value.expiry_date })
                $scope.number_of_notification += 1;
                $scope.number_expired_items += 1;
                value.usename = value.vaccine.name +" , "+ value.lot_number+" , "+value.store.name+", "+value.expiry_date+", "+ value.amount +" Doses, Source: "+$scope.getSourceName(value.source_id);
            });
        });
        }

        $scope.fetchData();

        $scope.updateExpired = function(){
            //get stock_items
            $http.get('index.php/expired_stock_items/'+$scope.data.children+'/child/'+$scope.selected_level).success(function(data){
                $scope.stock_items = data;
                angular.forEach($scope.stock_items,function(value){
                    value.vaccine = $scope.assignValue($scope.vaccines,value.vaccine_id);
                    value.packaging = $scope.assignValue($scope.packaging_information,value.packaging_id);
                    value.store = $scope.assignValue($scope.allstores,value.store_id);
                    $scope.notification_object.push({'url':'expired_items','name': $filter('translate')('labels.expired_item'),'descr':value.vaccine.name +" "+ $filter('translate')('labels.of_batch_number')+" "+ value.lot_number+" "+ $filter('translate')('labels.has_expired_since')+" "+value.expiry_date })
                    $scope.number_of_notification += 1;
                    $scope.number_expired_items += 1;
                    value.usename = value.vaccine.name +" , "+ value.lot_number+" , "+value.store.name+", "+value.expiry_date+", "+ value.amount +" Doses, Source: "+$scope.getSourceName(value.source_id);
                });
                $scope.prepareSeries();
            });

        }


        //get vaccines
        $http.get("index.php/vaccines").success(function(data){
            $scope.vaccines    = data;
            $scope.subCategory = data;
        });

        $scope.changeMainCat = function(){
            $scope.prepareSeries();
        }
        $scope.changeShowFilter = function(){
            $scope.data.recipient = "";
            $scope.data.sources = "";
            $scope.data.activity = "";
            $scope.data.store = "";
            $scope.prepareSeries();
        }

        $scope.showOrders = true;
        $scope.showScreening = false;
        $scope.showOrderType = true;

        //selecting Series
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
        $scope.showYearRange = true;
        $scope.showMonthRange = false;
        $scope.showDayRange = false;
        $scope.selectPeriod = function(){
            if($scope.data.reportPeriod == "Years"){
                $scope.showYearRange = true;
                $scope.showMonthRange = false;
                $scope.showDayRange = false;
                $scope.prepareSeries();

            }if($scope.data.reportPeriod == "Month"){
                $scope.showYearRange = false;
                $scope.showMonthRange = true;
                $scope.showDayRange = false;
                $scope.prepareSeries();
            }if($scope.data.reportPeriod == "Date range"){
                $scope.showYearRange = false;
                $scope.showMonthRange = false;
                $scope.showDayRange = true;
                $scope.prepareSeries();
            }

        };

        $scope.prepareSeries = function(){
            $scope.area = [];
            if($scope.data.reportPeriod == "Years"){
                angular.forEach($scope.data.selectedYear,function(value){
                    $scope.area.push(value.name);
                });
                $scope.data.category = "Years"
            }
            if($scope.data.reportPeriod == "Month"){
                angular.forEach($scope.data.selectedMonth,function(value){
                    $scope.area.push(value.name);
                });
                $scope.data.category = "Month"
            }
            if($scope.data.reportPeriod == "Date range"){
                var startDate = $filter('date')($scope.data.startDate, 'dd MMM yyyy')
                $scope.area[0] = startDate + " "+ $translate('labels.to')+" "+ $filter('date')($scope.data.endDate, 'dd MMM yyyy');
                $scope.data.category = "Date"
            }
        };
            
        $scope.filterDataStatus = function(vaccine_id,period){
            var result = {};
            result.data = [];
            var count = 0;
            angular.forEach($scope.filterFilters($scope.filterTime( $scope.data.stock_items,period)),function(val){
                if(val.vaccine_id == vaccine_id){
                    result.data.push(val);
                    if($scope.data.main_cat == 'doses'){
                        count += parseInt(val.number_received);
                    }else if($scope.data.main_cat == 'cost'){
                        count += parseInt(val.total_price);
                    }
                }
            });
            result.count = count;
            return result;
        };

        $scope.filterTime = function(series,value){
            var start = "";
            var end = "";
            if($scope.data.reportPeriod == "Years"){
                start = value+"-01-01";
                end = value+"-12-31";
            }if($scope.data.reportPeriod == "Month"){
                var year = $scope.data.selectedMonthYear;
                var month =new Array();
                month["January"] = "01";month["February"] = "02";
                month["March"] = "03";month["April"] = "04";month["May"] = "05";
                month["June"] = "06";month["July"] = "07";month["August"] = "08";
                month["September"] = "09";month["October"] = "10";
                month["November"] = "11";month["December"] = "12";
                start =year+"-"+month[value]+"-01";
                end = year+"-"+month[value]+"-31";

            }if($scope.data.reportPeriod == "Date range"){
                if($scope.data.startDate instanceof Date){
                    var d = $scope.data.startDate;
                    var curr_date	= d.getDate();
                    var curr_month	= d.getMonth()+1;
                    var curr_year 	= d.getFullYear();
                    if(curr_month<10){
                        curr_month="0"+curr_month;
                    }
                    if(curr_date<10){
                        curr_date="0"+curr_date;
                    }
                    start =curr_year+"-"+curr_month+"-"+curr_date;
                }else{
                    start = $scope.data.startDate;
                }
                if($scope.data.endDate instanceof Date){
                    var d1 = $scope.data.endDate;
                    var curr_date1	= d1.getDate();
                    var curr_month1	= d1.getMonth()+1;
                    var curr_year1 	= d1.getFullYear();
                    if(curr_month<10){
                        curr_month1="0"+curr_month1;
                    }
                    if(curr_date1<10){
                        curr_date1="0"+curr_date1;
                    }
                    end = curr_year1+"-"+curr_month1+"-"+curr_date1
                }else{
                    end = $scope.data.endDate
                };
            }
            var result = [];
            angular.forEach(series,function(val){
                if($scope.data.main_date == "system_date"){
                    if(val.created_at >= start && val.created_at <= end ){
                        result.push(val);
                    }
                }/*else if($scope.data.main_date == "user_date"){
                    if(val.arrival_date >= start && val.arrival_date <= end ){
                        result.push(val);
                    }
                }*/
            });
            return result;
        };

        $scope.filterFilters = function(series){
           return $scope.reduceSeries($scope.reduceSeries($scope.reduceSeries(series,
                           'activity_id',
                            $scope.data.activity),
                       'store_id',
                       $scope.data.store),
                 'source_id',
                  $scope.data.sources);
        };

        $scope.reduceSeries = function(series,colum,val){
            var result = [];
            angular.forEach(series,function(value){
                if(!val){
                    result.push(value);
                }else{
                    if(value[colum] == val ){
                        result.push(value);
                    }
                }
            });
            return result;

        };

        $scope.title = "";
        $scope.prepareTitle = function(){
            $scope.title = "";
            //$scope.title += (!$scope.data.recipient || $scope.data.recipient == '')?'':" Recipient: "+$scope.getRecipientName($scope.data.recipient);
            $scope.title += (!$scope.data.activity || $scope.data.activity == '')?'':" Activity: "+$scope.getActivityName($scope.data.activity)+" | ";
            $scope.title += (!$scope.data.store || $scope.data.store == '')?'':" Store: "+$scope.getStoreName($scope.data.store)+" | ";
            $scope.title += (!$scope.data.sources || $scope.data.sources == '')?'':" Source: "+$scope.getSourceName($scope.data.sources)+" | ";
        }
});