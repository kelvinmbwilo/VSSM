/**
 * Created by kelvin on 8/14/15.
 */

angular.module("vssmApp")
    .config(function($translateProvider) {
        $translateProvider.useStaticFilesLoader({
            prefix: 'languages/',
            suffix: '.json'
        });
        $translateProvider.preferredLanguage('enUS');
        $translateProvider.usePostCompiling(true);
    })
    .controller("mainCtrl",function ($rootScope,$scope,$http,$location,$timeout,$translate,DTOptionsBuilder) {
        //Variables Initialization
        $scope.data = {};

        //date picker initialization
        $scope.dateOptions = {
            changeYear: true,
            changeMonth: true,
            dateFormat: 'yyyy-mm-dd'
        };

        //getting the loggedIn User
        $http.get("index.php/loggenInuser").success(function(data){
            $scope.logedInUser = data;
            $scope.logedInUserName = data.first_name +" "+ data.last_name;
        });
        //getting the system settings
        $http.get("index.php/system_settings").success(function(data){
            $scope.system_settings = data[0];
            $translate.use(data[0].language);
            $scope.main_currency = data[0].main_currency;
        });
        //setting common Translations
        $translate('error.save_success').then(function (save_success) {
            $scope.saveSuccess = save_success;
        });
        $translate('error.save_falure').then(function (save_falue) {
            $scope.saveError = save_falue;
        });
        //Datatables Language Options
        $scope.dataTableEn = {
            "sEmptyTable":     "No data available in table",
            "sInfo":           "Showing _START_ to _END_ of _TOTAL_ entries",
            "sInfoEmpty":      "Showing 0 to 0 of 0 entries",
            "sInfoFiltered":   "(filtered from _MAX_ total entries)",
            "sInfoPostFix":    "",
            "sInfoThousands":  ",",
            "sLengthMenu":     "Show _MENU_ entries",
            "sLoadingRecords": "Loading...",
            "sProcessing":     "Processing...",
            "sSearch":         "Search:",
            "sZeroRecords":    "No matching records found",
            "oPaginate": {
                "sFirst":    "First",
                "sLast":     "Last",
                "sNext":     "Next",
                "sPrevious": "Previous"
            },
            "oAria": {
                "sSortAscending":  ": activate to sort column ascending",
                "sSortDescending": ": activate to sort column descending"
            }
        };
        $scope.dataTableDe = {
            "sEmptyTable":     "No hay datos disponibles en la tabla",
            "sInfo":           "Mostrando _START_ a _END_ de entradas _TOTAL_",
            "sInfoEmpty":      "Mostrando 0-0 de 0 entradas",
            "sInfoFiltered":   "(filtrada de las entradas totales _MAX_)",
            "sInfoPostFix":    "",
            "sInfoThousands":  ",",
            "sLengthMenu":     "Mostrar entradas _MENU_",
            "sLoadingRecords": "Cargando...",
            "sProcessing":     "Procesamiento ...",
            "sSearch":         "Buscar:",
            "sZeroRecords":    "No se encontraron registros coincidentes",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "previo"
            },
            "oAria": {
                "sSortAscending":  ": aactivar para ordenar la columna ascendente",
                "sSortDescending": ": activar para ordenar la columna descendente"
            }
        };
        $scope.langToUse = $scope.dataTableEn;
        //changing language
        $scope.changeLanguage = function (langKey) {
            $translate.use(langKey);
            if(langKey == "enUS"){
              $scope.langToUse = $scope.dataTableEn;
                $scope.dtOptions = DTOptionsBuilder.newOptions().withBootstrap().withLanguage($scope.langToUse);
            }else if(langKey == "deDE"){
                $scope.langToUse = $scope.dataTableDe;
                $scope.dtOptions = DTOptionsBuilder.newOptions().withBootstrap().withLanguage($scope.langToUse);
            }
        };
        //setting active link on top menu
        $scope.isActive = function (viewLocation) {
            var active = (viewLocation === $location.path());
            return active;
        };

        $scope.dtOptions = DTOptionsBuilder.newOptions()
            .withBootstrap().withLanguage($scope.langToUse);


        //getting the recipients level2
        $http.get("index.php/recipients/2").success(function(data){
            $scope.data.recipientsLevel2 = data;
        });

        //getting the recipients level3
        $http.get("index.php/recipients/3").success(function(data){
            $scope.data.recipientsLevel3 = data;
        });

        //getting the recipients level4
        $http.get("index.php/recipients/4").success(function(data){
            $scope.data.recipientsLevel4 = data;
        });

        //get user_recipients
        $http.get("index.php/user/recipients").success(function(data){
            $scope.userRecipients = data;
        });

        //get user_recipients
        $http.get("index.php/user/recipientLevel").success(function(data){
            $scope.userRecipientLevel = data;
        });

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

    }).controller("homeCtrl",function ($scope,$mdDialog,$mdToast,$http) {

        //get stores
        $scope.storeNames= [];
        $scope.storeValues = [];
        $scope.usedValues = [];
        $scope.storeCapacity = [];
        $scope.chartConfig2 ={};
        $scope.chartConfig2.series ={};
        $scope.chartConfig2.series.data = [];
        $http.get("index.php/stores").success(function(data){
            $scope.stores = data;
            var i = 0;
            angular.forEach(data,function(value){
                i++;
                if(i == 1){
                    $scope.chartConfig2.series.data.push({name: value.name+" - Used Volume" , y: parseInt(value.net_volume) })
                    $scope.chartConfig2.series.data.push({name: value.name+" - Remainig Volume" , y: parseInt(value.net_volume)-parseInt(value.used_volume) })
                }
                $scope.storeNames.push(value.name);
                $scope.storeValues.push(parseInt(value.net_volume));
                $scope.usedValues.push(parseInt(value.used_volume));
            })

        });

        $scope.setStoreType = function(id){

            $scope.chartConfig2.series.data = [];
            angular.forEach($scope.stores,function(value){
                if(value.id == id){
                    $scope.chartConfig2.series.data.push({name: value.name+" - Used Volume" , y: parseInt(value.net_volume) })
                    $scope.chartConfig2.series.data.push({name: value.name+" - Remainig Volume" , y: parseInt(value.net_volume)-parseInt(value.used_volume) })

                }
            })
        }

        $scope.vaccineNames= [];
        $scope.vacciineValues = [];

        $http.get("index.php/vaccineStocks/1").success(function(data){
            angular.forEach(data,function(value){
                $scope.vaccineNames.push(value.name);
                $scope.vacciineValues.push(parseInt(value.amount));
            })

        });

//This is not a highcharts object. It just looks a little like one!
        $scope.chartConfig = {
            options: {
                chart: {
                    type: 'column',
                    zoomType: 'x'
                }
            },
            xAxis: {
            labels: {
                rotation: -45,
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            },
                categories: $scope.storeNames
            },
            series: [{
                name:'Total Volume',
                data: $scope.storeValues
            },{
                name:'Used Volume',
                data: $scope.usedValues
            }],
            title: {
                text: 'Hello'
            },
//            xAxis: {currentMin: 0, currentMax: 10, minRange: 1},
            loading: false
        };

        $scope.chartConfig1 = {
            options: {
                chart: {
                    type: 'column',
                    zoomType: 'x'
                }
            },
            xAxis: {
            labels: {
                rotation: -45,
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            },
                categories: $scope.vaccineNames
            },
            series: [{
                data: $scope.vacciineValues
            }],
            title: {
                text: 'Hello'
            },
//            xAxis: {currentMin: 0, currentMax: 10, minRange: 1},
            loading: false
        };
        //serie.push({name: value+" - "+ val , y: parseInt(data) })
        $scope.chartConfig2 ={
            options: {
                chart: {
                    type: 'pie',
                    zoomType: 'x'
                }
            },
            title: {
                text: 'Hello'
            },
//            xAxis: {currentMin: 0, currentMax: 10, minRange: 1},
            loading: false
        };
    });

