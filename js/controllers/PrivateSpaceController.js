'use strict';

//author: J. Robert
//creation date: 01/03/2016
//modification date: 05/09/2016

/**
 * @ngdoc function
 * @name IoTBnB.controller:PrivateSpaceController
 * @description
 * # PrivateSpaceController
 * Controller of the IoTBnB
 */
 angular.module('IoTBnB')
 	.controller("PrivateSpaceController", ["$rootScope","$scope", "$location", "$window", "auth", "$http", "omiMessage", "selectedTable", "valueConverter", "$timeout", function ($rootScope, $scope, $location, $window, auth, $http, omiMessage, selectedTable, valueConverter, $timeout) {

var vm = this;

    vm.getProfile = function(username) {
      $http({
      method: 'POST',
      url: 'api/getProfile.php',
      data: {'username': username}
      }).then(function(response) {
          if(response.data.stat==1){
            $scope.omiURL =response.data.omiURL;
            $scope.omiName =response.data.omiName;
            $scope.omiAddr =response.data.omiAddr;
            $scope.rootUrl = response.data.omiURL;
            $scope.checkbox=true;
            //$location.path("/member");
          }
          else {
            var msg= " "+response.data.msg;
            $window.alert(msg);
          }

    });
  }

$timeout(function () {
    $scope.userName2=auth.profile.nickname;
    $scope.email=auth.profile.email;  
    vm.getProfile($scope.userName2);
}, 200);
    
$scope.saveProfile = function(username, email, omiURL, omiName, omiAddr) {
      $http({
      method: 'POST',
      url: 'api/saveProfile.php',
      data: {'username': username, 'email': email, 'omiURL': omiURL, 'omiName': omiName, 'omiAddr': omiAddr}
      }).then(function(response) {
          if(response.data.test==1){
            //$location.path("/member");
          }
          else {
            var msg= " "+response.data.msg;
            $window.alert(msg);
          }

    });
  }

$scope.publish = function() {
  console.log($scope.omiURL);
      $http({
      method: 'POST',
      url: 'api/saveODFtree.php',
      data: {'omiURL': $scope.omiURL, 'odfDATA': JSON.stringify($scope.Selected_ODF_Data)}
      }).then(function(response) {
          if(response.data.stat==1){
            //console.log($scope.omiURL);
            //console.log("it works");
            //$location.path("/");
          }
          else {
            var msg= " "+response.data.msg;
            $window.alert(msg);
          }

    });
  }

    $scope.error = {
      show: false,
      message: ''
    };


}]);