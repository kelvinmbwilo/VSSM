/**
 * Created by kelvin on 9/9/15.
 */
angular.module("vssmApp")
    .controller("summaryCtrl",function ($scope,$http,$mdDialog,$mdToast,$modal,$translate,$filter) {

        //get stock_items
        $http.get("index.php/stock_items").success(function(data){
            $scope.stock_items = data;
        });
    })
