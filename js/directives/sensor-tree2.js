//'use strict';

//author: A. Buda ??????
//modified by: J. Robert
//creation date: ???????
//modification date: 18/08/2016

/**
 * @ngdoc directive
 * @name IoTBnB.directive:sensorTree
 * @description
 * # sensorTree
 */
 /* jshint -W106 */ // Ignore jshint about non-camelCase variables
angular.module('IoTBnB')
  .directive('sensorTree2', function ($document, omiMessage, valueConverter, $interval, $timeout) {
    return {
      template: '<div></div>',
      restrict: 'E',
      scope: false,
      link: function postLink ($scope, element, attrs) {       
        $timeout(function () {
          $timeout(function () {
        element.jstree({
          plugins: [
            'sort',
            $scope.checkbox ? 'checkbox' : '',
          ],
          core: {
            check_callback: true,
            worker: false,
            data: function (node, callback, scope) {

              function sendSuccess(self, callback, children) {
                callback.call(self, children);
                $scope.error.show = false;
                $scope.error.message = '';
              }

              function sendError(self, callback, error,scope) {
                callback.call(self, error.errorNode);
                $scope.error.show = true;
                $scope.error.message = error.errorMsg;
              }

              var children = [];
              var error = {
                errorNode: [{ text: 'Ooopsss ! something happens... please re-open the tree node' }],
                errorMsg: 'Error when opening a tree node. Please close and reopen the node to try again.'
              };
              var rootId;

              //To not have to put /Objects in the Server URL but only the main path 
              $scope.rootUrl = $scope.rootUrl + '/Objects';
              //console.log("RootId =" + rootId);
              if ($scope.rootUrl) {
                //rootId = $scope.rootUrl.split('/');
                  // //ServerUrl = $scope.rootUrl.split('/Objects')[0];
                //rootId = rootId[rootId.length - 1];
                rootId = "Objects";
                console.log("RootId =" + rootId);
              }

              if (attrs.odfObject) {

                callback.call(this, [makeJsTree($scope.odfObject, $scope.odfObject.id)]);

              } else if (node.id === '#') {
                var icon = 'img/icon-room.svg';;
           
                //Root element
                children = [{
                  id: rootId,
                  text: rootId,
                  children: true,
                  isOdfObject: true,
                  url: $scope.rootUrl,
                  icon: icon,
                  state: {opened: true}
                }];

                callback.call(this, children);

              } else {//if (node.original.isOdfObject) {

                var isInfoItm =false;

                omiMessage.restApi(node.original.url,isInfoItm)
                  .then(function (odfObject) {
                    
                    for (var j = 0; j < odfObject.childObjects.length; j++) {
                      var childObject = odfObject.childObjects[j];
                      var url = node.original.url + '/' + childObject.id;
                       
                      children.push({
                        id: childObject.id, //url
                        text: childObject.id,
                        children: true,
                        isOdfObject: true,
                        icon: 'img/icon-room.svg',
                        url: url
                      });
                    }

                     var infoItems = odfObject.infoItems.reduce(
                      function(previous, current) {


                        var path = node.original.url.substring(node.original.url.indexOf(rootId));

                        var infoItemName = current.name
                        if(infoItemName.indexOf("Temperature") > -1 )
                         infoItemName = "temperature"
                        else if(infoItemName.indexOf("Humidity") > -1 )
                         infoItemName = "humidity"
                        else if(infoItemName.indexOf("Basic") > -1 )
                         infoItemName = "pir"
                        else if(infoItemName.indexOf("Motion") > -1 )
                         infoItemName = "pir"
                        else if(infoItemName.indexOf("Door") > -1 )
                         infoItemName = "pir"
                        else if(infoItemName.indexOf("Luminance") > -1 )
                         infoItemName = "light"
                       else if(infoItemName.indexOf("Co2") > -1 )
                         infoItemName = "co2"
                        else
                          infoItemName = "latest"

                        children.push({
                            id: current.name,
                            text: current.name,
                            icon: 'img/icon-' +
                              infoItemName + '.svg',
                            url: path
                          });
        
                        return previous +
                        '<InfoItem name="' + current.name + '">' +
                          '<MetaData/>' +
                        '</InfoItem>';
                      }, ''
                    );

                    sendSuccess(this, callback, children);

                      }, function () {
                        sendError(this, callback, error);
                      });
              } 
            },

            themes: {
              responsive: true
            }
          }
        });
      }, 500);
}, 500);

        function makeJsTree(data, rootUrl) {
          if (!data || !rootUrl) return null; //jshint ignore: line

          var childObjects = [];
          var infoItems = [];

          if (data.infoItems) {
            infoItems = data.infoItems.map(function(infoItem) {
              var url = rootUrl + '/' + infoItem.name;
              var valueText = '';

              if (infoItem.values.length) {
                var value = infoItem.values[0];
                valueText = ': ' + value.value +
                  valueConverter.getValueUnit(infoItem.name);
              }
			  
			  var infoItemName = infoItem.name
			  if(infoItemName.indexOf("Temperature") > -1 )
				 infoItemName = "temperature"
			  else if(infoItemName.indexOf("Humidity") > -1 )
				 infoItemName = "humidity"
			  else if(infoItemName.indexOf("Basic") > -1 )
				 infoItemName = "pir"
			  else if(infoItemName.indexOf("Motion") > -1 )
				 infoItemName = "pir"
			  else if(infoItemName.indexOf("Door") > -1 )
				 infoItemName = "pir"
			  else if(infoItemName.indexOf("Luminance") > -1 )
				 infoItemName = "light"

              return {
                id: url,
                text: infoItem.name + valueText,
                icon: 'img/icon-' + infoItemName + '.svg'
              };
            });
          }

          if (data.childObjects) {
            childObjects = data.childObjects.map(function(object) {
              makeJsTree(object, rootUrl + '/', object.id);
            });
          }

          return {
            id: rootUrl,
            text: data.id,
            isOdfObject: true,
            state: { opened: true },
            children: infoItems.concat(childObjects),
            icon: 'img/icon-room.svg'
          };

        
        }


        var tree = element.jstree(true);
        console.log(tree);

        function getNode(node, original) {
          if (!original) {
            return tree.get_node(node);
          } else {
            return tree.get_node(node).original;
          }
        }

       /* if (!attrs.odfObject) {
          var updateSensors = $interval(function () {
            element.find('.jstree-open:not(.jstree-last)').each(function () {
              if ($(this).children('.jstree-children').children('.jstree-leaf').length) {
                tree.refresh_node(getNode($(this)));
              }
            });
          }, 5000);
        }*/

        element
          .on('after_close.jstree', function (_, data) {
            if (!attrs.odfObject) {
              data.node.children = true;
              getNode(data.node.id).state.loaded = false;
            }
          })
          .on('changed.jstree', function (e,data) {
            var i, j, r = [],q = [], str= "{" ;
                for (i = 0, j = data.selected.length; i < j; i++) {
                    //r.push(data.instance.get_node(data.selected[i]).text.trim());
                    r.push(data.instance.get_node(data.selected[i]));
                    //q.push(r[i].original);
                    //console.log(r[i]);

                    for (k = r[i].parents.length-1, l =0; k>l; k--){

                    ID = r[i].parents[k-1];
                    parent = r[i].parents[k];
                    text = r[i].parents[k-1];
                    icon = "img/icon-room.svg";
                    test=str.concat('"id":', '"', ID,'"',  ',"parent":', '"',  parent,'"',  ',"text":', '"',  text,'"',  ',"icon":', '"',  icon, '"}') 
                    json= JSON.parse(test);
                    //console.log(json);
                    //console.log(JSON.stringify(q).indexOf(ID) > -1);
                    if (JSON.stringify(q).indexOf(ID) == -1) q.push(json);
                    }

                    ID = r[i].original.id;
                    parent = r[i].parent;
                    text = r[i].text;
                    icon = r[i].icon;
                    test=str.concat('"id":', '"', ID,'"',  ',"parent":', '"',  parent,'"',  ',"text":', '"',  text,'"',  ',"icon":', '"',  icon, '"}') 
                    json= JSON.parse(test);
                    if (JSON.stringify(q).indexOf(ID) == -1) q.push(json);
                  
                    //console.log(test);
                }
             //console.log(q)
             $scope.$apply(function() {
                $scope.f=q;
             });
             
         
            if ($scope.selectCallback) {
              $scope.selectCallback(tree.get_selected(true));
              //console.log(r)
            }
          });

  

        /*$document  
          .on('dnd_move.vakata', function (_, data) {
            var target = $(data.event.target);
            if(!target.closest(element).length) {
              if(target.closest('#drop-area').length) {
                data.helper.find('.jstree-icon')
                  .removeClass('jstree-er')
                  .addClass('jstree-ok');
              }
              else {
                data.helper.find('.jstree-icon')
                  .removeClass('jstree-ok')
                  .addClass('jstree-er');
              }
            }
          });*/

        /*$scope.$watch('odfObject', function (newObject, oldObject) {
          if (newObject !== oldObject) {
            tree.refresh();
          }
        });*/

        /*$scope.$on('$destroy', function () {
          $interval.cancel(updateSensors);
          element.jstree('destroy');
          //TODO: create different event listeners for each jstree instance
          //$document.off('dnd_stop.vakata dnd_move.vakata')
        });*/

      }
    };
  });
  /* jshint +W106 */
