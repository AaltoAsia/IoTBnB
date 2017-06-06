  'use strict';

  angular
    .module('IoTBnB')
    .controller('IoTBnBController', function ($state, authService, $rootScope, $http, $scope, $location, ngDialog, ngCart, $window, $timeout) { 

      //$state.reload();
console.log("iotbnb")

	  var vm = this;
    vm.authService = authService;
      
    $rootScope.currentUser=authService.getNickname();

    console.log($rootScope.currentUser);

    $scope.selectedSources = {};
    $scope.cart = [];
        $scope.priceItemInit = 10;
       

        if (!$scope.odsDomain){
        $scope.odsDomain = ODS_DOMAIN;
        $scope.odsKey = ODS_APIKEY;
        }


        $scope.clickToOpen = function (item) {
          //item.fields.Reputation, item.fields.Billing, item.fields.Technology
          //console.log(item)
            $rootScope.InfoItem = item.fields.path;
            $rootScope.Std = item.fields.format;//$rootScope.stats[item.fields.url + "Objects/" + item.fields.path]["format"];
            $rootScope.Voc = item.fields.vocabulary;//$rootScope.stats[item.fields.url + "Objects/" + item.fields.path]["vocab"];
            $rootScope.Metadata = item.fields.metadata;//$rootScope.stats[item.fields.url + "Objects/" + item.fields.path]["metadata"];

            $rootScope.pathToXML = item.fields.url + "Objects/" + item.fields.path;
            $rootScope.pathToMetadataXML = item.fields.url + "Objects/" + item.fields.path + "/MetaData";
            $scope.getInfoXML($rootScope.pathToXML);
              if ($rootScope.Metadata !=0){
             $scope.getXMLMetadata();
              }
        ngDialog.open({ template: 'views/partials/AdvStat.html', className: 'ngdialog-theme-default',
        showClose: true,
                    closeByDocument: true,
                    closeByEscape: true,
                    appendTo: false });
        };


        $scope.openCart = function () {
        //localStorage.setItem('url','/billing');
        $state.go('cart')
        /*ngDialog.open({ template: 'views/partials/cart.html', className: 'ngdialog-theme-default',
        showClose: true,
                    closeByDocument: true,
                    closeByEscape: true,
                    appendTo: false });
        */
        };

        $scope.openBilling = function() {
        //localStorage.billing = true;
        localStorage.setItem('billing', true);
        //ngDialog.close("openCart");
        //$location.url("/billing")
        $state.go('billing')
      };
       
        $scope.toMember = function() {
          //ngDialog.close("openBilling");
          //localStorage.billing = false; 

          $rootScope.currentUser=authService.getNickname();

          //console.log($rootScope.currentUser);

          localStorage.setItem('billing', false);

          $scope.cartTest = ngCart.getItems();
          $scope.username= authService.getNickname();

          console.log(ngCart.getItems());

          var arrayUrlOMInode = new Array();
          for (var i = 0; i < ngCart.getItems().length ; i++) {
            $scope.test=ngCart.getItems()[i]["_id"].split("/");
            //$scope.urltest=$scope.test[0].concat('//').concat($scope.test[2]).concat('/').concat($scope.test[3]);
            $scope.urltest=$scope.test[0].concat('//').concat($scope.test[2]);
            arrayUrlOMInode[i]=$scope.urltest;
          };
          
          console.log(arrayUrlOMInode);
          
          $http({
            method: 'POST',
            url: 'api/getToken2.php',
            data: {'username': $rootScope.currentUser, 'items': ngCart.getItems(), 'urlOMI': arrayUrlOMInode}
            }).then(function(response) {
          if(response.data.stat==1){
          }
          else {
            var msg= " "+response.data.msg;
            $window.alert(msg);
          }
          })

$timeout(function () {
           $http({
      method: 'POST',
      url: 'api/getExistingToken.php',
      data: {'username': $rootScope.currentUser}
      }).then(function(response) {
          if(response.data.stat==1){
            $rootScope.Records = response.data.token;
          }
          else {
          }

    }); 
    }, 1000);

          $state.go('memberDashboard');
          //$location.url('/memberDashboard');
              //Should I remove all items in the cart? But be sure to record needed information in a variable 
              //to print it in the next pages 
              //ngCart.empty();
        };

    vm.getStats = function() {
    
          $http({
            method: 'POST',
            url: 'api/getStat.php',
            data: {'username': $scope.username, 'items': ngCart.getItems()}
            }).then(function(response) {
          $rootScope.stats = response.data;
          })

        };

        //vm.getStats();


        $scope.getInfoXML = function(urlXML) {
          $rootScope.InfoXML="";
          $http({
            method: 'GET',
            url: urlXML,//$rootScope.pathToXML,
            }).then(function(response) {
            //console.log(response)
              $rootScope.InfoXML = response.data;
          })
        };

        $scope.getXMLMetadata = function() {
          $http({
            method: 'GET',
            url: $rootScope.pathToMetadataXML,
            }).then(function(response) {
          $rootScope.InfoMetadataXML = response.data;
          })
        };

});

