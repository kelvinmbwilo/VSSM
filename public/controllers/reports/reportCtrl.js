/**
 * Created by kelvin on 9/22/15.
 */
angular.module("vssmApp")
    .controller("reportCtrl",function ($scope,$http,$mdDialog,$mdToast,$modal,$translate,$filter,DTOptionsBuilder, DTColumnBuilder) {
        //get vaccines
        $http.get("index.php/vaccines").success(function(data){
            $scope.vaccines = data;
        });

        //get stock_items
        $http.get("index.php/stock_items").success(function(data){
            $scope.stock_items = data;
        });

        //get stores
        $http.get("index.php/stores").success(function(data){
            $scope.stores = data;
        });

        //get manufactures
        $http.get("index.php/manufactures").success(function(data){
            $scope.manufactures = data;
        });

        //get Activities
        $http.get("index.php/activities").success(function(data){
            $scope.activities = data;
        });

        //get transport_mode
        $http.get("index.php/transport_mode").success(function(data){
            $scope.transport_mode = data;
        });

        //get sources
        $http.get("index.php/sources").success(function(data){
            $scope.sources = data;
        });

        $http.get("index.php/packaging_information").success(function(data){
            $scope.packaging_information = data;
            $scope.packagingInformation =[];
            angular.forEach($scope.packaging_information,function(value){
                value.usename = value.dose_per_vial+" dose_per_vial, "+ value.vials_per_box+" vials_per_box"
                $scope.packagingInformation.push(value);
            });
        });

        $scope.seeStock = 'details';
        $scope.setSeeStock = function(value){
            $scope.seeStock = value;
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
        $scope.getVaccineName = function(id){
            var name = "";
            angular.forEach($scope.vaccines,function(value){
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
        $scope.getRecipientName = function(id){
            var name = "";
            angular.forEach($scope.userRecipients,function(value){
                if(value.id == id){
                    name = value.name;
                }
            });
            return name;
        }
        $scope.getTransportName = function(id){
            var name = "";
            angular.forEach($scope.transport_mode,function(value){
                if(value.id == id){
                    name = value.name;
                }
            });
            return name;
        }
        $scope.getSourceName = function(id){
            var name = "";
            angular.forEach($scope.sources,function(value){
                if(value.id == id){
                    name = value.name;
                }
            });
            return name;
        }

        $scope.getTotalPrice = function(){
            var total = 0;
            angular.forEach($scope.newItem.items,function(item){
                total += item.u_price * item.doses;
            });
            return total;
        }


    });