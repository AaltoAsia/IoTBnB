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
    .controller('DashboardCtrl', ['$scope', 'auth', 'ngCart', '$timeout', '$http', '$rootScope', function ($scope, auth, ngCart, $timeout, $http, $rootScope) {

    	$scope.InCart =  ngCart.getItems();

   	

}]);