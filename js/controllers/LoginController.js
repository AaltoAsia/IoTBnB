'use strict';

//author: J. Robert
//creation date: 01/03/2016
//modification date: 05/09/2016

/**
 * @ngdoc function
 * @name IoTBnB.controller:LoginController
 * @description
 * # LoginController
 * Controller of the IoTBnB
 */
angular.module('IoTBnB')
    .controller('LoginController', ['$scope', 'auth', function ($scope, auth) {

    auth.signin();

}]);