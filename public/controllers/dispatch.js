angular.module("vssmApp")
    .controller("dispatchCtrl",function ($scope,$http,$mdDialog,$mdToast,$modal,$translate,$filter) {
        $scope.newItem = {};
        $scope.newItem.arrival_date = new Date();
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
        $scope.formats = ['dd-MMMM-yyyy', 'yyyy/MM/dd', 'dd.MM.yyyy', 'shortDate'];
        $scope.format = $scope.formats[0];
        $scope.open = function($event) {
            $scope.status.opened = true;
        };
        $scope.open3 = function($event) {
            $scope.status.opened3 = true;
        };
        $scope.open2 = function($event) {
            $scope.status.opened2 = true;
        };

        //get transport_mode
        $http.get("index.php/transport_mode").success(function(data){
            $scope.transport_mode = data;
        });

    });