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
            .withBootstrap().withLanguage($scope.langToUse)
            .withTableTools('./bower_components/TableTools-master/swf/copy_csv_xls_pdf.swf')
            .withTableToolsButtons([
                'copy',
                'print', {
                    'sExtends': 'collection',
                    'sButtonText': 'Save',
                    'aButtons': ['csv', 'xls', 'pdf']
                }
            ]);


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

        ////////////////////////////////////////////////////////////////////
        //////////////////Initializing Default Basic Datas/////////////////
        ///////////////////////////////////////////////////////////////////
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
            angular.forEach($scope.stores,function(value){
                //var freeVol = ((value.net_volume - value.used_volume)/value.net_volume)*100;
                var freeVol = value.net_volume - value.used_volume;
                value.useName = value.name+", Volume: "+value.net_volume+" Used: "+value.used_volume+" Free: "+ freeVol;
            });
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

        //get Activities
        $http.get("index.php/activities").success(function(data){
            $scope.activities = data;
        });

        //get transport_mode
        $http.get("index.php/transport_mode").success(function(data){
            $scope.transport_mode = data;
        });

        //get packaging_information
        $http.get("index.php/packaging_information").success(function(data){
            $scope.packaging_information = [];
            $scope.packagingInformation =[];
            angular.forEach(data,function(value){
                value.usename = value.dose_per_vial+" dose_per_vial, "+ value.vials_per_box+" vials_per_box"
                $scope.packagingInformation.push(value);
                $scope.packaging_information.push(value);
            });
        });

        $scope.assignValue = function(series,id){
            var item = null;
            angular.forEach(series,function(value){
                if(value.id == id){
                    item = value;
                }
            });
            return item;
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
        $scope.getVaccineName = function(id){
            var name = "";
            angular.forEach($scope.vaccines,function(value){
                if(value.id == id){
                    name = value.name;
                }
            });
            return name;
        }

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
        $scope.freeValues = [];
        $scope.storeTable = [];
        $scope.storeCapacity = [];

        $http.get("index.php/stores").success(function(data){
            $scope.stores = data;
            var i = 0;
            angular.forEach(data,function(value){
                i++;
                if(i == 1){
                    $scope.data.storeName = value.name;
                    $scope.storeCapacity.push({name: value.name+" - Used Volume" , y: parseInt(value.net_volume) })
                    $scope.storeCapacity.push({name: value.name+" - Remainig Volume" , y: parseInt(value.net_volume)-parseInt(value.used_volume) })
                }
                $scope.storeTable.push({name: value.name,volume:value.net_volume,used_volume:value.used_volume,free:parseInt(value.net_volume) - parseInt(value.used_volume)});
                $scope.storeNames.push(value.name);
                $scope.storeValues.push(parseInt(value.net_volume));
                $scope.usedValues.push(parseInt(value.used_volume));
                $scope.freeValues.push(parseInt(value.net_volume) - parseInt(value.used_volume));
            })

        });

        $scope.setStoreType = function(id){
            $scope.storeCapacity.pop();
            $scope.storeCapacity.pop();
            angular.forEach($scope.stores,function(value){
                if(value.id == id){
                    var log = ""+id+"";
                    $scope.detailedItems = $scope.storeItemsDetails[log];
                    while ($scope.vaccineNames.length) { $scope.vaccineNames.pop(); }
                    while ($scope.vacciineValues.length) { $scope.vacciineValues.pop(); }
                    $scope.chartConfig1.title.text = value.name+" Items";
                    angular.forEach($scope.storeItems[log],function(val){
                        $scope.vaccineNames.push($scope.getVaccineName(val.vaccine_id))
                        $scope.vacciineValues.push(parseInt(val.total))
                    });

                    $scope.data.storeName = value.name;
                    $scope.storeCapacity.push({name: value.name+" - Used Volume" , y: parseInt(value.used_volume) })
                    $scope.storeCapacity.push({name: value.name+" - Remainig Volume" , y: parseInt(value.net_volume)-parseInt(value.used_volume) })
                }
            })
        }

        //get transport_mode
        $http.get("index.php/disaptchedItemsMonth").success(function(data){
            $scope.disaptchedItemsMonth = data;
        });

        $http.get("index.php/vaccineStocks/1").success(function(data){
           $scope.stockss = data;
        });

        $scope.updateRemain = function(id){
            var total_cons = 0;
            var i = 0;
            angular.forEach($scope.disaptchedItemsMonth,function(value){
                i++;
                if(value.vaccine_id == id){
                    total_cons += parseInt(value.total);
                }
            });
            $scope.curLevel = 0;
            angular.forEach($scope.stockss,function(value){
                if(value.id == id){
                    $scope.curLevel = parseInt(value.amount);
                }
            });
            $scope.avg = (total_cons == 0)?0:total_cons/i
            $scope.total_cons = total_cons;
        }



        $http.get("index.php/storeItems").success(function(data){
            $scope.storeItems = data;
        });



        $http.get("index.php/storeItemsDetails").success(function(data){
            $scope.storeItemsDetails = data;

        });

//This is not a highcharts object. It just looks a little like one!
        $scope.chartConfig = {
            options: {
                chart: {
                    type: 'column',
                    zoomType: 'x'
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Volume(Ltr)'
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
            },{
                name:'Free Volume',
                data: $scope.freeValues
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
            yAxis: {
                min: 0,
                title: {
                    text: 'Doses'
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
                text: $scope.storeTitle
            },
//            xAxis: {currentMin: 0, currentMax: 10, minRange: 1},
            loading: false
        };
        //serie.push({name: value+" - "+ val , y: parseInt(data) })
        $scope.chartConfig2 ={
            options: {
                chart: {
                    type: 'pie'
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Doses'
                }
            },
            series: [{
                name: "Volume(Ltr)",
                data: $scope.storeCapacity
            }],
            title: {
                text: $scope.data.storeName
            },
//            xAxis: {currentMin: 0, currentMax: 10, minRange: 1},
            loading: false
        };
    });
