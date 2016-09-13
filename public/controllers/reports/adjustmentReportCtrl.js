/**
 * Created by florence on 13/09/15.
 */
angular.module("vssmApp")
    .controller("adjustmentReportCtrl",function ($scope,$http,$mdDialog,$mdToast,$modal,$translate,$filter,DTOptionsBuilder, DTColumnBuilder) {
        
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

        $scope.number_of_notification = 0
        $scope.number_close_to_expiry = 0
        $scope.number_below_minimum = 0
        $scope.number_above_maximum = 0
        $scope.number_expired_items = 0
        $scope.notification_object = [];

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
        $scope.data.years = [{ name: "2016", ticked: true },{ name: "2015", ticked: true },{ name: "2014", ticked: true}];
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

        //getting screening types
        $scope.fetchData = function(){
            $http.get('index.php/adjustedItems/'+$scope.data.children+'/child/'+$scope.selected_level).success(function(data){
                $scope.data.adjustedItems = data;
            });
        }

        $scope.fetchData();
        $scope.updateLevel = function(){
            $http.get('index.php/adjustedItems/'+$scope.data.children+'/child/'+$scope.selected_level).success(function(data){
                $scope.adjustedItems = data;
                angular.forEach($scope.stock_items,function(value){
                    value.vaccine = $scope.assignValue($scope.vaccines,value.vaccine_id);
                    value.packaging = $scope.assignValue($scope.packaging_information,value.packaging_id);
                    value.store = $scope.assignValue($scope.stores,value.store_id);
                });
                $scope.prepareSeries();
            });
        };
        $scope.updateLevel();


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
            //$scope.chartConfig.title.text = $translate('labels.monthly_arrivals_title');
            $scope.area = [];
            if($scope.data.reportPeriod == "Years"){
                angular.forEach($scope.data.selectedYear,function(value){
                    $scope.area.push(value.name);
                });
                $scope.data.category = "Years"
            }
            if($scope.data.reportPeriod == "Month"){
                //$scope.chartConfig.title.text +=" "+ $scope.data.selectedMonthYear+ " ";
                angular.forEach($scope.data.selectedMonth,function(value){
                    $scope.area.push(value.name);
                });
                $scope.data.category = "Month"
            }
            if($scope.data.reportPeriod == "Date range"){
                var startDate = $filter('date')($scope.data.startDate, 'dd MMM yyyy')
                $scope.area[0] = startDate + " "+ $translate('labels.to')+" "+ $filter('date')($scope.data.endDate, 'dd MMM yyyy');
                //$scope.chartConfig.title.text +="  "+ startDate + $translate('labels.to')+" "+ $filter('date')($scope.data.endDate, 'dd MMM yyyy');
                $scope.data.category = "Date"
            }
            //$scope.chartConfig.xAxis.categories = $scope.area;
            /*if($scope.chartConfig.xAxis.categories.length == 0){
                $mdToast.show(
                    $mdToast.simple()
                        .content('Please Select at least one period!')
                        .position($scope.getToastPosition())
                        .hideDelay(3000)
                );
            }*/

            /*$scope.normalseries = [];
            if($scope.data.chartType == "pie"){
                delete $scope.chartConfig.chart;
                var serie = [];
                angular.forEach($scope.subCategory,function(value){
                    angular.forEach($scope.chartConfig.xAxis.categories,function(val){
                        var number = $scope.filterDataStatus(value.id,val);
                        serie.push({name: value.name+" - "+ val , y: parseInt(number.count)})
                    });
                });
                if($scope.data.main_cat == 'doses'){
                    $scope.UsedName = "Doses";
                }else if($scope.data.main_cat == 'cost'){
                    $scope.UsedName = "Price($)";
                }
                $scope.normalseries.push({type: $scope.data.chartType, name:$scope.UsedName , data: serie,showInLegend: true,
                    dataLabels: {
                        enabled: false
                    } });
                $scope.chartConfig.series = $scope.normalseries;
            }
            else if($scope.data.chartType == "excel"){
                $scope.table.headers = [];
                $scope.table.colums =[];
                angular.forEach($scope.subCategory,function(value){
                    var serie = [];
                    $scope.table.headers.push(value);
                });
                angular.forEach($scope.chartConfig.xAxis.categories,function(val){
                    var seri = [];
                    angular.forEach($scope.subCategory,function(value){
                        seri.push({name:value,value:parseInt(Math.random()*100)});
                    });

                    $scope.table.colums.push({name:val,values:seri});

                });
                window.location.assign("index.php/excel?data="+JSON.stringify($scope.table));
                alert("drawing excel");

            }
            else if($scope.data.chartType == "nyingine"){
                delete $scope.chartConfig.chart;
                var serie1 = [];
                angular.forEach($scope.subCategory,function(value){
                    var serie = [];

                    angular.forEach($scope.chartConfig.xAxis.categories,function(val){
                        var number = $scope.filterDataStatus(value.id,val);
                        serie.push(parseInt(number.count));
                        serie1.push({name: value.name+" - "+ val , y: parseInt(number.count) })
                    });
                    $scope.normalseries.push({type: 'column', name: value.name, data: serie});
                    $scope.normalseries.push({type: 'spline', name: value.name, data: serie});
                });
                if($scope.data.main_cat == 'doses'){
                    $scope.UsedName = "Doses";
                }else if($scope.data.main_cat == 'cost'){
                    $scope.UsedName = "Price($)";
                }
                $scope.normalseries.push({type: 'pie', name: $scope.UsedName, data: serie1,center: [100, 80],size: 150,showInLegend: false,
                    dataLabels: {
                        enabled: false
                    }})
                $scope.chartConfig.series = $scope.normalseries;
            }
            else if($scope.data.chartType == 'table'){
                $scope.table.headers = [];
                $scope.table.colums =[];
                angular.forEach($scope.subCategory,function(value){
                    var serie = [];
                    $scope.table.headers.push(value.name);
                });
                angular.forEach($scope.chartConfig.xAxis.categories,function(val){
                    var seri = [];
                    angular.forEach($scope.subCategory,function(value){
                        var number = $scope.filterDataStatus(value.id,val);
                        seri.push({name:value.name,value:parseInt(number.count)});
                    });

                    $scope.table.colums.push({name:val,values:seri});
                });
            }else if($scope.data.chartType == 'list'){
                $scope.table.headers = [];
                $scope.table.colums =[];
                $scope.itemsList = []
                angular.forEach($scope.chartConfig.xAxis.categories,function(val){
                    var seri = [];
                    angular.forEach($scope.subCategory,function(value){
                        var number = $scope.filterDataStatus(value.id,val);
                        if(number.data.length != 0){
                            angular.forEach(number.data,function(v){
                                $scope.itemsList.push(v);
                            });
                        }

                    });

                });
            }
            else{
                delete $scope.chartConfig.chart;
                angular.forEach($scope.subCategory,function(value){
                    var serie = [];
                    angular.forEach($scope.area,function(val){
                        var number = $scope.filterDataStatus(value.id,val);
                        serie.push(number.count);
                    });
                    $scope.normalseries.push({type: $scope.data.chartType, name: value.name, data: serie})
                });
                $scope.chartConfig.series = $scope.normalseries;
            }*/
            $scope.prepareTitle()
        };

        /*$scope.filterDataStatus = function(vaccine_id,period){
            var result = {};
            result.data = [];
            var count = 0;
            angular.forEach($scope.filterFilters($scope.filterTime( $scope.data.adjustedItems,period)),function(val){
                if(val.vaccine_id == vaccine_id){
                    result.data.push(val);
                    if($scope.data.main_cat == 'doses'){
                        count += parseInt(val.number_received);
                        $scope.chartConfig.yAxis.title.text = 'Doses'
                    }else if($scope.data.main_cat == 'cost'){
                        count += parseInt(val.total_price);
                        $scope.chartConfig.yAxis.title.text = 'Price ($)'
                    }
                }
            });
            result.count = count;
            return result;
        };*/

        /*$scope.filterTime = function(series,value){
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
                }else if($scope.data.main_date == "user_date"){
                    if(val.arrival_date >= start && val.arrival_date <= end ){
                        result.push(val);
                    }
                }
            });
            return result;
        }*/

        /*$scope.filterFilters = function(series){
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

        };*/

        $scope.title = "";
        $scope.prepareTitle = function(){
            $scope.title = "";
            //$scope.title += (!$scope.data.recipient || $scope.data.recipient == '')?'':" Recipient: "+$scope.getRecipientName($scope.data.recipient);
            $scope.title += (!$scope.data.activity || $scope.data.activity == '')?'':" Activity: "+$scope.getActivityName($scope.data.activity)+" | ";
            $scope.title += (!$scope.data.store || $scope.data.store == '')?'':" Store: "+$scope.getStoreName($scope.data.store)+" | ";
            $scope.title += (!$scope.data.sources || $scope.data.sources == '')?'':" Source: "+$scope.getSourceName($scope.data.sources)+" | ";
            //$scope.chartConfig.subtitle={text :$scope.title};
        }



    });