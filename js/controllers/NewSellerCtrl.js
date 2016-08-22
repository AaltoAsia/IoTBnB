'use strict';

//author: J. Robert
//creation date: 01/03/2016
//modification date: 18/08/2016

/**
 * @ngdoc function
 * @name IoTBnB.controller:NewSellerCtrl
 * @description
 * # NewSellerCtrl
 * Controller of the IoTBnB
 */
angular.module('IoTBnB')
	.controller('NewSellerCtrl', function ($scope, $location, $http) {
  //console.log("Page Controller reporting for duty.");

// $scope.userInfo = null;
 //console.log($scope.userInfo);

$scope.coefNb=0;
$scope.price ="0";
$scope.coefType1="0";
$scope.coefType2="0";
$scope.coefType3="0";
$scope.coefType4="0";
$scope.coefType5="0";
$scope.coefType6="0";
$scope.coefType7="0";
$scope.coefCity="0";

$scope.UpdateIncome=function(){



	if ($scope.dataNumber=="0 - 10") 
	{
	$scope.coefNb = "10";
	}
	else if ($scope.dataNumber=="10 - 100"){
	$scope.coefNb = "100";
	}
	else if ($scope.dataNumber=="100 - 1000"){
	$scope.coefNb = "1000";
	}
	else if ($scope.dataNumber==">1000"){
	$scope.coefNb = "10000";
	}
	else {
	$scope.coefNb ="0";
	}

	if ($scope.manufacturing == 1){
		$scope.coefType1 = "1";
	}

	if ($scope.mobility == 1){
		$scope.coefType2 = "1";
	}

	if ($scope.farming == 1){
		$scope.coefType3 = "2";
	}

	if ($scope.wearable == 1){
		$scope.coefType4 = "2";
	}

	if ($scope.living == 1){
		$scope.coefType5 = "10";
	}	

	if ($scope.cities == 1){
		$scope.coefType6 = "10";
	}	

	if ($scope.environment == 1){
		$scope.coefType7 = "100";
	}	

	if ($scope.dataLocation =="Metz"){
		$scope.coefCity = "10";
	}

	$scope.price = $scope.coefNb * ($scope.coefType1 + $scope.coefType2 + $scope.coefType3 + $scope.coefType4 + $scope.coefType5 + $scope.coefType6 + $scope.coefType7) + $scope.coefCity;

}

  // Activates Tooltips for Social Links
 /* $('.tooltip-social').tooltip({
    selector: "a[data-toggle=tooltip]"
  })*/
});
