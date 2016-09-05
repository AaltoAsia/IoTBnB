'use strict';

//author: J. Robert
//creation date: 18/08/2016
//modification date: 05/09/2016

/**
 * @ngdoc function
 * @name IoTBnB.controller:PrivateSpaceController
 * @description
 * # PrivateSpaceController
 * Controller of the IoTBnB
 */
 angular.module('IoTBnB')
 	.controller('IoTBnBController', function ($scope, $location, ngDialog) { //ngCart
        $scope.selectedSources = {};
        $scope.cart = [];
        $scope.priceItemInit = 10;

        if (!$scope.odsDomain){
        $scope.odsDomain = ODS_DOMAIN;
        $scope.odsKey = ODS_APIKEY;
    }

        /*$scope.toggleItem = function (item) {
          if ($scope.selectedSources[item.fields.url + item.fields.path]) {
            $scope.cart.push(item);
          } else {
            $scope.cart.splice($scope.cart.indexOf(item), 1);
          }
        };*/

        $scope.clickToOpen = function (item) {
        ngDialog.open({ template: 'views/partials/AdvStat.html', className: 'ngdialog-theme-default',
        showClose: true,
                    closeByDocument: true,
                    closeByEscape: true,
                    appendTo: false });
        };


        $scope.openCart = function (item) {
        ngDialog.open({ template: 'views/partials/cart.html', className: 'ngdialog-theme-default',
        showClose: true,
                    closeByDocument: true,
                    closeByEscape: true,
                    appendTo: false });
        };

        $scope.openBilling = function() {
        ngDialog.close("openCart");
        ngDialog.open({ template: 'views/partials/billing.html', className: 'ngdialog-theme-default',
        showClose: true,
                    closeByDocument: true,
                    closeByEscape: true,
                    appendTo: false });
      };
       
        $scope.toMember = function() {
          ngDialog.close("openBilling");
          $location.url('/member');
        };

      

});