//'use strict';

/**
 * @ngdoc directive
 * @name IoTBnB.directive:sensorTree
 * @description
 * # sensorTree
 */
 /* jshint -W106 */ // Ignore jshint about non-camelCase variables
angular.module('IoTBnB')
  .directive('sensorTree3', function () {

    return {

      template: '<div></div>',
      restrict: 'E',
      scope: false,
      link: function postLink ($scope, element, attrs) {        
        element.jstree({
          plugins: [
            'sort',
            //$scope.checkbox ? 'checkbox' : '',
          ],
          core: {
            check_callback: true,
            worker: false,
            data: $scope.f, 

    }


  });
        var tree = element.jstree(true);

          $scope.$watch('f', function(newValue, oldValue) {
                if (newValue) {
                    //console.log("I see a data change!");
                    //console.log(newValue);
                    tree.settings.core.data = newValue;
                    tree.settings.core.expand_selected_onload = false;
                    tree.refresh();
                    $scope.Selected_ODF_Data = newValue;
                    //console.log($scope.Selected_ODF_Data);
                }
            });


   }}});     
  /* jshint +W106 */
