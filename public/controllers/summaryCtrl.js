/**
 * Created by kelvin on 9/9/15.
 */
angular.module("vssmApp")
    .controller("summaryCtrl",function ($scope,$http,$mdDialog,$mdToast,$modal,$translate,$filter) {

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

        $http.get("index.php/packaging_information").success(function(data){
            $scope.packaging_information = data;
            $scope.packagingInformation =[];
            angular.forEach($scope.packaging_information,function(value){
                value.usename = value.dose_per_vial+" dose_per_vial, "+ value.vials_per_box+" vials_per_box"
                $scope.packagingInformation.push(value);
            });
        });

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
    })
