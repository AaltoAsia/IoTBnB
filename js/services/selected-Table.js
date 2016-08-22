'use strict';

/**
 * @ngdoc service
 * @name IoTBnB.selectedTable
 * @description
 * # selectedTable
 * Service in the IoTBnB.
 */
angular.module('IoTBnB')
  .service('selectedTable', function () {

	//var self = this;
  	this.getSelectedTree = function () {

  	console.log($scope.f);
  	return $scope.f;
  	}

  });