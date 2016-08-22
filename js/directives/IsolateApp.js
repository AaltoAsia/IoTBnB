//'use strict';

//author: J. Robert
//creation date: 18/08/2016
//modification date: 18/08/2016

/**
 * @ngdoc directive
 * @name IoTBnB.directive:sensorTree
 * @description
 * # sensorTree
 */
 /* jshint -W106 */ // Ignore jshint about non-camelCase variables
angular.module('IoTBnB')
.directive("ngIsolateApp", function() {
        return {
            "scope" : {},
            "restrict" : "AEC",
            "compile" : function(element, attrs) {
                var html = element.html();
                element.html('');
                return function(scope, element) {
                    scope.$destroy();
                    setTimeout(function() {
                        // prepare root element for new app
                        var newRoot = document.createElement("div");
                        newRoot.innerHTML = html;
                        // bootstrap module
                        angular.bootstrap(newRoot, [attrs["ngIsolateApp"]]);
                        // add it to page
                        element.append(newRoot);
                    });
                }
            }
        }
    });