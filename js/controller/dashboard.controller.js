'use strict';

//author: J. Robert
//creation date: 01/03/2016
//modification date: 09/09/2016

/**
 * @ngdoc function
 * @name IoTBnB.controller:LoginController
 * @description
 * # DashboardCtrl
 * Controller of the IoTBnB
 */
angular.module('IoTBnB')
    .controller('DashboardCtrl', ['$scope', 'ngCart', '$timeout', '$http', '$rootScope', '$window', function ($scope, ngCart, $timeout, $http, $rootScope, $window) {

    	$scope.InCart =  ngCart.getItems();

    	//var $scope.fullURLofPurchasedData = new Array();

    	$scope.getFullURL = function (item) {
    		//$scope.eye=1;
   console.log("getFullURL");
   var urlToODS = "https://biotope.opendatasoft.com/api/datasets/1.0/search/?apikey=<API_KEY/TOKEN>&q=title="+item.dataURL;
             //console.log(fullURLofPurchasedData);

            $http({
            method: 'GET',
            url: urlToODS
            }).then(function(response) {
              console.log(response);
                //$scope.indexedServices=response.data.records;
                //console.log(response.data.datasets[0]);
                $scope.fullURLofPurchasedData=response.data.datasets[0].metas["omi-node-url"]+item.dataURL;
                console.log($scope.fullURLofPurchasedData);
            
            });

            $timeout(function () {
            $window.open($scope.fullURLofPurchasedData, '_blank');
        }, 1500);

}

}]);