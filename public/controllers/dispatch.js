angular.module("vssmApp")
    .controller("dispatchCtrl",function ($scope,$http,$mdDialog,$mdToast,$modal,$translate,$filter,$interval) {
        $scope.newItem = {};
        $scope.newItem.dispatch_date = new Date();
        $scope.newItem.items = [];
        $scope.oneItem = {};
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
        $scope.noAnnualQuota = false;

        //get Activities
        $http.get("index.php/activities").success(function(data){
            $scope.activities = data;
        });

        //get transport_mode
        $http.get("index.php/transport_mode").success(function(data){
            $scope.transport_mode = data;
        });

        //get stock_items
        $http.get("index.php/stock_items").success(function(data){
            $scope.stock_items = data;
            angular.forEach($scope.stock_items,function(value){
                value.usename = value.vaccine.name +" , "+ value.lot_number+" , "+value.store.name+", "+value.expiry_date
//                $scope.packagingInformation.push(value);
            });
        });

        $scope.updateStock = function(id){
            angular.forEach($scope.currentAnnual,function(value){
                if(value.item_id == id){
                    $scope.annual_need = value.expected_annual_need
                }
            });
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
        $http.get("index.php/getNextPackageNumber").success(function(data){
            var str = ""
            for (var i = 6;i > data.length; i--){
                str+="0";
            }
            $scope.newItem.voucher_no = parseInt(data) + 1;
            $scope.newItem.voucher_no = new Date().getFullYear()+str+$scope.newItem.voucher_no;
        });
        $scope.generateVoucheNumber = function(){
           //get transport_mode
            $http.get("index.php/getNextPackageNumber").success(function(data){
                var str = ""
               for (var i = 6;i > data.length; i--){
                   str+="0";
               }
                    $scope.newItem.voucher_no = parseInt(data) + 1;
                    $scope.newItem.voucher_no = new Date().getFullYear()+str+$scope.newItem.voucher_no;
            });

        }
        $interval( function(){
            $scope.generateVoucheNumber()
        }, 12333000);

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
                    $scope.oneItem.packaging = value.packaging.dose_per_vial;
                    $scope.oneItem.dose_vial = value.packaging.dose_per_vial;
                    $scope.oneItem.vials_per_box = value.packaging.vials_per_box;
                    $scope.oneItem.cm_per_dose = value.packaging.cm_per_dose;
                    $scope.oneItem.item_id = value.vaccine.id;
                    $scope.oneItem.item = value.vaccine.name;
                    $scope.oneItem.manufacture_id = value.packaging.id;
                }
            })
        }

        $scope.getActivityName = function(id){
            var name = "";
            angular.forEach($scope.activities,function(value){
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
        $scope.getManufactureName = function(id){
            var name = "";
            angular.forEach($scope.manufactures,function(value){
                if(value.id == id){
                    name = value.name;
                }
            });
            return name;
        }
    }).controller('dispatchModalInstanceCtrl', function ($scope, $modalInstance,$http,$mdDialog,$mdToast,$filter) {
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
            $modalInstance.close();
        }


        $scope.cancel = function () {
            $scope.current_batch_no = "";
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