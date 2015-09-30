/**
 * Created by kelvin on 8/24/15.
 */
angular.module("vssmApp")
    .controller("userRolesCtrl",function ($scope,$http,$mdDialog,$mdToast,$modal,$translate,$filter) {
        $scope.userroles = [];
        $scope.currentSaving = false;
        $http.get("index.php/user_roles").success(function(data){
            $scope.userroles = data;
        });


        var roles = [
            {name: 'Dashboard', msGroup: true},
            {  name: "See Dashboard", role: "see_dashboard", ticked: false },
            {   msGroup: false },
            {name: 'Warnings', msGroup: true},
            {  name: "See Item Close to Expiry", role: "see_closetoexpiry", ticked: false },
            {  name: "See Below Minimum", role: "see_belowminimum", ticked: false },
            {  name: "See Above Maximun", role: "see_aboveminimum", ticked: false },
            {   msGroup: false },
            {name: 'Arrival', msGroup: true},
            {  name: "See Vaccine & Diluent", role: "see_vaccinediluent", ticked: false },
            {  name: "See Pre-advice", role: "see_preadvice", ticked: false },
            {   msGroup: false },
            {name: 'Dispatch', msGroup: true},
            {  name: "See Dispatch", role: "see_dispach", ticked: false },
            {   msGroup: false },
            {name: 'Package Summary', msGroup: true},
            {  name: "See Expected Packages", role: "see_expectedpackages", ticked: false },
            {  name: "See Dispatched Packages", role: "see_dispatchedpackage", ticked: false },
            {  name: "See Stock Items", role: "see_stockitems", ticked: false },
            {  name: "See Arrival Summary", role: "see_arrivalsummary", ticked: false },
            {   msGroup: false },
            {name: 'Adjustment', msGroup: true},
            {  name: "See Arrival Cancellation", role: "see_arivalcancellation", ticked: false },
            {  name: "See Dispatch Adjustment", role: "see_dispatchadjustment", ticked: false },
            {  name: "See Stock Adjustment", role: "see_stockadjustment", ticked: false },
            {  name: "See Move Items", role: "see_moveitems", ticked: false },
            {   msGroup: false },
            {name: 'Reports', msGroup: true},
            {  name: "See Dispatch Report", role: "see_dispatchreprt", ticked: false },
            {  name: "See Arrival Report", role: "see_arrivalreport", ticked: false },
            {  name: "See Expired Items", role: "see_expireditems", ticked: false },
            {  name: "See Canceled Invoice", role: "see_canceledinvoice", ticked: false },
            {  name: "", role: "", ticked: false },
            {   msGroup: false },
            {name: 'Stock Opening', msGroup: true},
            {  name: "See", role: "see_stockopening", ticked: false },
            {   msGroup: false },
            {name: 'Pre-advice Notice', msGroup: true},
            {  name: "See", role: "see_preadvicenotice", ticked: false },
            {  name: "Add", role: "add_preadvicenotice", ticked: false },
            {  name: "Update", role: "update_preadvicenotice", ticked: false },
            {  name: "Delete", role: "delete_preadvicenotice", ticked: false },
            {   msGroup: false },
            {name: 'Recipients', msGroup: true},
            {  name: "See", role: "see_recipients", ticked: false },
            {  name: "Add", role: "add_recipients", ticked: false },
            {  name: "Update", role: "update_recipients", ticked: false },
            {  name: "Delete", role: "delete_recipients", ticked: false },
            {   msGroup: false },
            {name: 'User Management', msGroup: true},
            {  name: "See Users", role: "see_users", ticked: false },
            {  name: "Add Users", role: "add_users", ticked: false },
            {  name: "Update Users", role: "update_users", ticked: false },
            {  name: "Delete Users", role: "delete_users", ticked: false },
            {  name: "See User Roles", role: "see_userroles", ticked: false },
            {  name: "Add User Roles", role: "add_userroles", ticked: false },
            {  name: "Update User Roles", role: "update_userroles", ticked: false },
            {  name: "Delete User Roles", role: "delete_userroles", ticked: false },
            {   msGroup: false },
            {name: 'Configuration', msGroup: true},
            {  name: "See", role: "see_configuration", ticked: false },
            {  name: "Update", role: "update_configuration", ticked: false },
            {   msGroup: false },
            {name: 'Basic Data', msGroup: true},
            {name: 'Vaccine & Diluents', msGroup: true},
            {  name: "See", role: "see_vaccine", ticked: false },
            {  name: "Add", role: "add_vaccine", ticked: false },
            {  name: "Edit", role: "edit_vaccine", ticked: false },
            {  name: "Delete", role: "delete_vaccine", ticked: false },
            {   msGroup: false },
            {name: 'Packaging Information', msGroup: true},
            {  name: "See", role: "see_packaging", ticked: false },
            {  name: "Add", role: "add_packaging", ticked: false },
            {  name: "Edit", role: "edit_packaging", ticked: false },
            {  name: "Delete", role: "delete_packaging", ticked: false },
            {   msGroup: false },
            {name: 'Items Max Min', msGroup: true},
            {  name: "See", role: "see_itemsmaxmin", ticked: false },
            {  name: "Add", role: "add_itemsmaxmin", ticked: false },
            {  name: "Edit", role: "edit_itemsmaxmin", ticked: false },
            {  name: "Delete", role: "delete_itemsmaxmin", ticked: false },
            {   msGroup: false },
            {name: 'Sources', msGroup: true},
            {  name: "See", role: "see_sources", ticked: false },
            {  name: "Add", role: "add_sources", ticked: false },
            {  name: "Edit", role: "edit_sources", ticked: false },
            {  name: "Delete", role: "delete_sources", ticked: false },
            {   msGroup: false },
            {name: 'Stores', msGroup: true},
            {  name: "See", role: "see_stores", ticked: false },
            {  name: "Add", role: "add_stores", ticked: false },
            {  name: "Edit", role: "edit_stores", ticked: false },
            {  name: "Delete", role: "delete_stores", ticked: false },
            {   msGroup: false },
            {name: 'Transport Mode', msGroup: true},
            {  name: "See", role: "see_transportmode", ticked: false },
            {  name: "Add", role: "add_transportmode", ticked: false },
            {  name: "Edit", role: "edit_transportmode", ticked: false },
            {  name: "Delete", role: "delete_transportmode", ticked: false },
            {   msGroup: false },
            {name: 'Adjustment Reasons', msGroup: true},
            {  name: "See", role: "see_adjustmentreason", ticked: false },
            {  name: "Add", role: "add_adjustmentreason", ticked: false },
            {  name: "Edit", role: "edit_adjustmentreason", ticked: false },
            {  name: "Delete", role: "delete_adjustmentreason", ticked: false },
            {   msGroup: false },
            {name: 'Activities', msGroup: true},
            {  name: "See", role: "see_activities", ticked: false },
            {  name: "Add", role: "add_activities", ticked: false },
            {  name: "Edit", role: "edit_activities", ticked: false },
            {  name: "Delete", role: "delete_activities", ticked: false },
            {   msGroup: false },
            {name: 'Manufactures', msGroup: true},
            {  name: "See", role: "see_manufactures", ticked: false },
            {  name: "Add", role: "add_manufactures", ticked: false },
            {  name: "Edit", role: "edit_manufactures", ticked: false },
            {  name: "Delete", role: "delete_manufactures", ticked: false },
            {   msGroup: false },
            {name: 'Recipients', msGroup: true},
            {  name: "See", role: "see_basicrecipients", ticked: false },
            {  name: "Add", role: "add_basicrecipients", ticked: false },
            {  name: "Edit", role: "edit_basicrecipients", ticked: false },
            {  name: "Delete", role: "delete_basicrecipients", ticked: false },
            {   msGroup: false },
            {name: 'Annual Quota', msGroup: true},
            {  name: "See", role: "see_annualqouta", ticked: false },
            {  name: "Add", role: "add_annualqouta", ticked: false },
            {  name: "Edit", role: "edit_annualqouta", ticked: false },
            {  name: "Delete", role: "delete_annualqouta", ticked: false },
            {   msGroup: false },
            {   msGroup: false }
        ];
        var roles1 = [
            {name: 'Dashboard', msGroup: true},
            {  name: "See Dashboard", role: "see_dashboard", ticked: false },
            {   msGroup: false },
            {name: 'Warnings', msGroup: true},
            {  name: "See Item Close to Expiry", role: "see_closetoexpiry", ticked: false },
            {  name: "See Below Minimum", role: "see_belowminimum", ticked: false },
            {  name: "See Above Maximun", role: "see_aboveminimum", ticked: false },
            {   msGroup: false },
            {name: 'Arrival', msGroup: true},
            {  name: "See Vaccine & Diluent", role: "see_vaccinediluent", ticked: false },
            {  name: "See Pre-advice", role: "see_preadvice", ticked: false },
            {   msGroup: false },
            {name: 'Dispatch', msGroup: true},
            {  name: "See Dispatch", role: "see_dispach", ticked: false },
            {   msGroup: false },
            {name: 'Package Summary', msGroup: true},
            {  name: "See Expected Packages", role: "see_expectedpackages", ticked: false },
            {  name: "See Dispatched Packages", role: "see_dispatchedpackage", ticked: false },
            {  name: "See Stock Items", role: "see_stockitems", ticked: false },
            {  name: "See Arrival Summary", role: "see_arrivalsummary", ticked: false },
            {   msGroup: false },
            {name: 'Adjustment', msGroup: true},
            {  name: "See Arrival Cancellation", role: "see_arivalcancellation", ticked: false },
            {  name: "See Dispatch Adjustment", role: "see_dispatchadjustment", ticked: false },
            {  name: "See Stock Adjustment", role: "see_stockadjustment", ticked: false },
            {  name: "See Move Items", role: "see_moveitems", ticked: false },
            {   msGroup: false },
            {name: 'Reports', msGroup: true},
            {  name: "See Dispatch Report", role: "see_dispatchreprt", ticked: false },
            {  name: "See Arrival Report", role: "see_arrivalreport", ticked: false },
            {  name: "See Expired Items", role: "see_expireditems", ticked: false },
            {  name: "See Canceled Invoice", role: "see_canceledinvoice", ticked: false },
            {  name: "", role: "", ticked: false },
            {   msGroup: false },
            {name: 'Stock Opening', msGroup: true},
            {  name: "See", role: "see_stockopening", ticked: false },
            {   msGroup: false },
            {name: 'Pre-advice Notice', msGroup: true},
            {  name: "See", role: "see_preadvicenotice", ticked: false },
            {  name: "Add", role: "add_preadvicenotice", ticked: false },
            {  name: "Update", role: "update_preadvicenotice", ticked: false },
            {  name: "Delete", role: "delete_preadvicenotice", ticked: false },
            {   msGroup: false },
            {name: 'Recipients', msGroup: true},
            {  name: "See", role: "see_recipients", ticked: false },
            {  name: "Add", role: "add_recipients", ticked: false },
            {  name: "Update", role: "update_recipients", ticked: false },
            {  name: "Delete", role: "delete_recipients", ticked: false },
            {  name: "See", role: "see_recipientslevels", ticked: false },
            {  name: "Add", role: "add_recipientslevels", ticked: false },
            {  name: "Update", role: "update_recipientslevels", ticked: false },
            {  name: "Delete", role: "delete_recipientslevels", ticked: false },
            {   msGroup: false },
            {name: 'User Management', msGroup: true},
            {  name: "See Users", role: "see_users", ticked: false },
            {  name: "Add Users", role: "add_users", ticked: false },
            {  name: "Update Users", role: "update_users", ticked: false },
            {  name: "Delete Users", role: "delete_users", ticked: false },
            {  name: "See User Roles", role: "see_userroles", ticked: false },
            {  name: "Add User Roles", role: "add_userroles", ticked: false },
            {  name: "Update User Roles", role: "update_userroles", ticked: false },
            {  name: "Delete User Roles", role: "delete_userroles", ticked: false },
            {   msGroup: false },
            {name: 'Configuration', msGroup: true},
            {  name: "See", role: "see_configuration", ticked: false },
            {  name: "Update", role: "update_configuration", ticked: false },
            {   msGroup: false },
            {name: 'Basic Data', msGroup: true},
            {name: 'Vaccine & Diluents', msGroup: true},
            {  name: "See", role: "see_vaccine", ticked: false },
            {  name: "Add", role: "add_vaccine", ticked: false },
            {  name: "Edit", role: "edit_vaccine", ticked: false },
            {  name: "Delete", role: "delete_vaccine", ticked: false },
            {   msGroup: false },
            {name: 'Packaging Information', msGroup: true},
            {  name: "See", role: "see_packaging", ticked: false },
            {  name: "Add", role: "add_packaging", ticked: false },
            {  name: "Edit", role: "edit_packaging", ticked: false },
            {  name: "Delete", role: "delete_packaging", ticked: false },
            {   msGroup: false },
            {name: 'Items Max Min', msGroup: true},
            {  name: "See", role: "see_itemsmaxmin", ticked: false },
            {  name: "Add", role: "add_itemsmaxmin", ticked: false },
            {  name: "Edit", role: "edit_itemsmaxmin", ticked: false },
            {  name: "Delete", role: "delete_itemsmaxmin", ticked: false },
            {   msGroup: false },
            {name: 'Sources', msGroup: true},
            {  name: "See", role: "see_sources", ticked: false },
            {  name: "Add", role: "add_sources", ticked: false },
            {  name: "Edit", role: "edit_sources", ticked: false },
            {  name: "Delete", role: "delete_sources", ticked: false },
            {   msGroup: false },
            {name: 'Stores', msGroup: true},
            {  name: "See", role: "see_stores", ticked: false },
            {  name: "Add", role: "add_stores", ticked: false },
            {  name: "Edit", role: "edit_stores", ticked: false },
            {  name: "Delete", role: "delete_stores", ticked: false },
            {   msGroup: false },
            {name: 'Transport Mode', msGroup: true},
            {  name: "See", role: "see_transportmode", ticked: false },
            {  name: "Add", role: "add_transportmode", ticked: false },
            {  name: "Edit", role: "edit_transportmode", ticked: false },
            {  name: "Delete", role: "delete_transportmode", ticked: false },
            {   msGroup: false },
            {name: 'Adjustment Reasons', msGroup: true},
            {  name: "See", role: "see_adjustmentreason", ticked: false },
            {  name: "Add", role: "add_adjustmentreason", ticked: false },
            {  name: "Edit", role: "edit_adjustmentreason", ticked: false },
            {  name: "Delete", role: "delete_adjustmentreason", ticked: false },
            {   msGroup: false },
            {name: 'Activities', msGroup: true},
            {  name: "See", role: "see_activities", ticked: false },
            {  name: "Add", role: "add_activities", ticked: false },
            {  name: "Edit", role: "edit_activities", ticked: false },
            {  name: "Delete", role: "delete_activities", ticked: false },
            {   msGroup: false },
            {name: 'Manufactures', msGroup: true},
            {  name: "See", role: "see_manufactures", ticked: false },
            {  name: "Add", role: "add_manufactures", ticked: false },
            {  name: "Edit", role: "edit_manufactures", ticked: false },
            {  name: "Delete", role: "delete_manufactures", ticked: false },
            {   msGroup: false },
            {name: 'Recipients', msGroup: true},
            {  name: "See", role: "see_basicrecipients", ticked: false },
            {  name: "Add", role: "add_basicrecipients", ticked: false },
            {  name: "Edit", role: "edit_basicrecipients", ticked: false },
            {  name: "Delete", role: "delete_basicrecipients", ticked: false },
            {   msGroup: false },
            {name: 'Annual Quota', msGroup: true},
            {  name: "See", role: "see_annualqouta", ticked: false },
            {  name: "Add", role: "add_annualqouta", ticked: false },
            {  name: "Edit", role: "edit_annualqouta", ticked: false },
            {  name: "Delete", role: "delete_annualqouta", ticked: false },
            {   msGroup: false },
            {   msGroup: false }
        ];

        $scope.showAEdit = function(item){
            $scope.updatingBrowers = [];
            var rolesArr  = item.roles.split(":");
            angular.forEach(roles1,function(value){
              if(value.hasOwnProperty('role')){
                  if(rolesArr.indexOf(value.role) == -1){
                      value.ticked = false;
                      $scope.updatingBrowers.push(value)
                  }else{
                      value.ticked = true;
                      $scope.updatingBrowers.push(value)
                  }
              }else{
                  $scope.updatingBrowers.push(value)
              }
            });
            $scope.myItem = angular.copy(item);
            $modal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'views/users/updateRoles.html',
                scope: $scope,
                controller: 'UserRolesModalInstanceCtrl',
                size: "lg",
                "backdrop":"static"
            });
        }

        $scope.showAdd = function(){
            $scope.modernBrowsers = [];
            angular.forEach(roles,function(value){
                $scope.modernBrowsers.push(value)
            });
            var modalInstance = $modal.open({
                animation: $scope.animationsEnabled,
                templateUrl: 'views/users/addRoles.html',
                scope: $scope,
                controller: 'UserRolesModalInstanceCtrl',
                size: "lg",
                "backdrop":"static"
            });
        }

        $scope.cancelEditting = function(){
            $scope.showEditAdd = false;
            $scope.currentEditting = false;
            $scope.currentKaya = {};
        }

        $scope.cancelAdding = function(){
            $scope.showEditAdd = false;
            $scope.currentKaya = {};
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
        var $translate = $filter('translate');
        $scope.deletedItem = [];
        $scope.deletingItem = [];
        $scope.showConfirm = function(ev,id) {
            var confirm = $mdDialog.confirm()
                .title($translate('labels.confirm_delete'))
                .content($translate('labels.irreversible_warning'))
                .ariaLabel('Lucky day')
                .ok($translate('help.delete'))
                .cancel($translate('labels.cancel'))
                .targetEvent(ev);
            $mdDialog.show(confirm).then(function() {
                $scope.deletingItem[id] = true;
                $http.post("index.php/delete/user_roles/"+id).success(function (newVal) {
                    $scope.deletedItem[id] = true;
                    $mdToast.show(
                        $mdToast.simple()
                            .content($translate('error.delete_success'))
                            .position($scope.getToastPosition())
                            .hideDelay(3000)
                    );
                }).error(function(){
                    $scope.deletingItem[id] = false;
                    $mdToast.show(
                        $mdToast.simple()
                            .content($translate('error.delete_falure'))
                            .position($scope.getToastPosition())
                            .hideDelay(5000)
                    );
                });
            }, function() {

            });
        };
    }).controller('UserRolesModalInstanceCtrl', function ($scope, $modalInstance,$http,$mdDialog,$mdToast,$filter) {
        var $translate = $filter('translate');

        $scope.ok = function () {
            $modalInstance.close();
        };
        $scope.currentSaving = false;
        $scope.save = function(item){
            var txt = "";
            angular.forEach( $scope.outputBrowsers, function( value, key ) {
                txt += value.role+":"
            });
            txt = txt.slice(0, - 1);
            item.roles = txt;
            $scope.currentSaving = true;
            $http.post("index.php/user_roles", item).success(function (Item) {
                $scope.userroles.push(Item);
                $scope.newItem = {};
                $mdToast.show(
                    $mdToast.simple()
                        .content($translate('error.save_success'))
                        .position($scope.getToastPosition())
                        .hideDelay(5000)
                );
                $scope.currentSaving = false;
                $modalInstance.close();
            }).error(function(){
                $mdToast.show(
                    $mdToast.simple()
                        .content($translate('error.save_falure'))
                        .position($scope.getToastPosition())
                        .hideDelay(5000)
                );
                $scope.currentSaving = false;
                $modalInstance.close();
            });
        }

        //updating an Item
        $scope.currentUpdating = false;
        $scope.update = function(item){
            $scope.currentUpdating = true;
            var txt = "";
            angular.forEach( $scope.outputupdateBrowsers, function( value, key ) {
                txt += value.role+":"
            });
            txt = txt.slice(0, - 1);
            item.roles = txt;
            $http.post("index.php/user_roles/"+item.id, item).success(function (newItem) {

                for (var i = 0; i < $scope.userroles.length; i++) {
                    if ($scope.userroles[i].id == newItem.id) {
                        $scope.userroles[i] = newItem;
                        break;
                    }
                }
                $mdToast.show(
                    $mdToast.simple()
                        .content($translate('error.update_success'))
                        .position($scope.getToastPosition())
                        .hideDelay(5000)
                );
                $scope.currentUpdating = false;
                $modalInstance.close();
            }).error(function(){
                $mdToast.show(
                    $mdToast.simple()
                        .content($translate('error.update_falure'))
                        .position($scope.getToastPosition())
                        .hideDelay(5000)
                );
                $scope.currentUpdating = false;
                $modalInstance.close();
            })

        }
        $scope.cancel = function () {
            $scope.updatingBrowers = [];
            $scope.modernBrowsers = [];
            $scope.outputupdateBrowsers = [];
            $scope.outputBrowsers = [];
            $modalInstance.dismiss('cancel');
        };
    });