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
 	.controller("PrivateSpaceController", ["$rootScope","authService", "$scope", "$location", "$window", "$http", "omiMessage", "selectedTable", "valueConverter", "$timeout", function ($rootScope, authService, $scope, $location, $window, $http, omiMessage, selectedTable, valueConverter, $timeout) {

var vm = this;
vm.authService = authService;

vm.nickname=authService.getNickname();
vm.email=authService.getEmail();
vm.picture=authService.getPicture();

    //console.log("private"+vm.username)

vm.getExistingToken = function(username) {
      $http({
      method: 'POST',
      url: 'api/getExistingToken.php',
      data: {'username': username}
      }).then(function(response) {
          if(response.data.stat==1){
            $rootScope.Records = response.data.token;
          }
          else {
            var msg= " "+response.data.msg;
            $window.alert(msg);
          }

    });
  }


  vm.getExistingOdfTree = function(omiURL) {
      $http({
      method: 'POST',
      url: 'api/getExistingOdfTree.php',
      data: {'omiURL': omiURL}
      }).then(function(response) {
          if(response.data.stat==1){
            //console.log(response.data.token);
            $rootScope.odfTree = response.data.tree;
            $scope.Selected_ODF_Data = $rootScope.odfTree;
            //console.log($rootScope.odfTree);
          }
          else {
            var msg= " "+response.data.msg;
            $window.alert(msg);
          }

    });
  }



    vm.getProfile = function(username) {
      $http({
      method: 'POST',
      url: 'api/getProfile.php',
      data: {'username': username}
      }).then(function(response) {
        //console.log("rep"+response);
          if(response.data.stat==1){
            $scope.omiURL =response.data.omiURL;
            $scope.omiName =response.data.omiName;
            $scope.omiAddr =response.data.omiAddr;
            $scope.rootUrl = response.data.omiURL;
            $scope.checkbox=true;
          }
          else {
            var msg= " "+response.data.msg;
            $window.alert(msg);
          }

    });
  }

$timeout(function () {
    //$scope.userName2=authService.getNickname();
    //$scope.email=authService.getEmail();
    //console.log(vm.nickname); 
    vm.getProfile(vm.nickname);
   vm.getExistingToken(vm.nickname);
}, 300);

//$timeout(function () {
  //console.log($scope.omiURL);
 //vm.getExistingOdfTree($scope.omiURL);
 //}, 600);
    
    $timeout(function () {
//console.log($scope.omiURL);
 vm.saveProfile(vm.nickname,vm.email);
 }, 600);

vm.saveProfile = function(username, email) {
      $http({
      method: 'POST',
      url: 'api/saveProfile.php',
      data: {'username': username, 'email': email}
      }).then(function(response) {
          if(response.data.stat==1){
            //$location.path("/member");
          }
          else {
            var msg= " "+response.data.msg;
            $window.alert(msg);
          }

    });
  }

  $scope.saveProfile = function(username, email) {
      $http({
      method: 'POST',
      url: 'api/saveProfile.php',
      data: {'username': username, 'email': email}
      }).then(function(response) {
          if(response.data.stat==1){
            //$location.path("/member");
          }
          else {
            var msg= " "+response.data.msg;
            $window.alert(msg);
          }

    });
  }

$scope.saveServer = function(omiURL, omiName, omiAddr) {
      $http({
      method: 'POST',
      url: 'api/saveServer.php',
      data: {'username': vm.nickname, 'omiURL': omiURL, 'omiName': omiName, 'omiAddr': omiAddr}
      }).then(function(response) {
          if(response.data.stat==1){
            //$location.path("/member");
          }
          else {
            var msg= " "+response.data.msg;
            $window.alert(msg);
          }

    });
  }


$scope.publish = function() {
  //console.log($scope.omiURL);
      $http({
      method: 'POST',
      url: 'api/saveODFtree.php',
      data: {'omiURL': $scope.omiURL, 'odfDATA': JSON.stringify($scope.Selected_ODF_Data)}
      }).then(function(response) {
          if(response.data.stat==1){
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