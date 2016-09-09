'use strict';

//author: J. Robert
//creation date: 18/08/2016
//modification date: 09/09/2016

/**
 * @ngdoc function
 * @name IoTBnB.controller:PrivateSpaceController
 * @description
 * # PrivateSpaceController
 * Controller of the IoTBnB
 */
 angular.module('IoTBnB')
 	.controller('IoTBnBController', function ($rootScope, $http, $scope, $location, ngDialog, $localStorage, auth, ngCart, $window, $timeout) { //ngCart
        $scope.selectedSources = {};
        $scope.cart = [];
        $scope.priceItemInit = 10;
       

        if (!$scope.odsDomain){
        $scope.odsDomain = ODS_DOMAIN;
        $scope.odsKey = ODS_APIKEY;
        }

    var vm = this;

        /*$scope.toggleItem = function (item) {
          if ($scope.selectedSources[item.fields.url + item.fields.path]) {
            $scope.cart.push(item);
          } else {
            $scope.cart.splice($scope.cart.indexOf(item), 1);
          }
        };*/

        $scope.clickToOpen = function (item) {
            $rootScope.InfoItem = item.fields.path;
            $rootScope.Std = $rootScope.stats[item.fields.url + "Objects/" + item.fields.path]["format"];
            $rootScope.Voc = $rootScope.stats[item.fields.url + "Objects/" + item.fields.path]["vocab"];
            $rootScope.Metadata = $rootScope.stats[item.fields.url + "Objects/" + item.fields.path]["metadata"];

            $rootScope.pathToXML = item.fields.url + "Objects/" + item.fields.path;
            $rootScope.pathToMetadataXML = item.fields.url + "Objects/" + item.fields.path + "/MetaData";
            $scope.getInfoXML();
            $scope.getXMLMetadata();
        ngDialog.open({ template: 'views/partials/AdvStat.html', className: 'ngdialog-theme-default',
        showClose: true,
                    closeByDocument: true,
                    closeByEscape: true,
                    appendTo: false });
        };


        $scope.openCart = function () {
        ngDialog.open({ template: 'views/partials/cart.html', className: 'ngdialog-theme-default',
        showClose: true,
                    closeByDocument: true,
                    closeByEscape: true,
                    appendTo: false });
        };

        $scope.openBilling = function() {
        $localStorage.billing = true;
        ngDialog.close("openCart");
        $location.url("/billing")
      };
       
        $scope.toMember = function() {
          ngDialog.close("openBilling");
          $localStorage.billing = false; 
          $scope.cartTest = ngCart.getItems();
          $scope.username= auth.profile.nickname;

          $http({
            method: 'POST',
            url: 'api/getToken.php',
            data: {'username': $scope.username, 'items': ngCart.getItems()}
            }).then(function(response) {
          if(response.data.stat==1){
          }
          else {
            var msg= " "+response.data.msg;
            $window.alert(msg);
          }
          })

$timeout(function () {
           $http({
      method: 'POST',
      url: 'api/getExistingToken.php',
      data: {'username': $scope.username}
      }).then(function(response) {
          if(response.data.stat==1){
            $rootScope.Records = response.data.token;
          }
          else {
          }

    }); 
    }, 1000);

          $location.url('/memberDashboard');
              //Should I remove all items in the cart? But be sure to record needed information in a variable 
              //to print it in the next pages 
              //ngCart.empty();
        };

    vm.getStats = function() {
    
          $http({
            method: 'POST',
            url: 'api/getStat.php',
            data: {'username': $scope.username, 'items': ngCart.getItems()}
            }).then(function(response) {
          $rootScope.stats = response.data;
          })

        };

        vm.getStats();


        $scope.getInfoXML = function() {
          $http({
            method: 'GET',
            url: $rootScope.pathToXML,
            }).then(function(response) {
          $rootScope.InfoXML = response.data;
          })
        };

        $scope.getXMLMetadata = function() {
          $http({
            method: 'GET',
            url: $rootScope.pathToMetadataXML,
            }).then(function(response) {
          $rootScope.InfoMetadataXML = response.data;
          })
        };

});