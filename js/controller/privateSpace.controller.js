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
 	.controller("PrivateSpaceController", ["$state","$rootScope","authService", "$scope", "$location", "$window", "$http", "omiMessage", "selectedTable", "valueConverter", "$timeout", "$q", function ($state, $rootScope, authService, $scope, $location, $window, $http, omiMessage, selectedTable, valueConverter, $timeout, $q) {

var vm = this;
vm.authService = authService;

vm.nickname=authService.getNickname();
vm.email=authService.getEmail();
vm.picture=authService.getPicture();



//$scope.checkboxModel.value1=true;

    //console.log("private"+vm.username)

//console.log("omiURL"+vm.omiURL);

vm.getPurchasedData = function(username) {
      $http({
      method: 'POST',
      url: 'api/getPurchasedData.php',
      data: {'username': username}
      }).then(function(response) {
          if(response.data.stat==1){
            $rootScope.RecordsPurchasedData = response.data.idPurchasedData;
          }
          else {
            var msg= "getPurchasedData"+response.data.msg;
            $window.alert(msg);
          }

    });
  }

  vm.getWallet = function(username) {
      $http({
      method: 'POST',
      url: 'api/getWallet.php',
      data: {'username': username}
      }).then(function(response) {
          if(response.data.stat==1){
            $scope.wallet = response.data.walletAddress;
          }
          else {
            var msg= "getWallet "+response.data.msg;
            $window.alert(msg);
          }

    });
  }

/*vm.getExistingToken = function(username) {
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
  }*/


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


vm.getIndexedServices = function(omiURL) {
      //var urlToODS = "https://biotope.opendatasoft.com/api/records/1.0/search/?dataset=iotbnb-v2&apikey=5677f3197edde5512e65fddbd752eca3056dee9e9693930f18bbb38f&rows=200&facet=path&q=url="+omiURL;
      var urlToODS = "https://biotope.opendatasoft.com/api/datasets/1.0/search/?apikey=<API_KEY/TOKEN>&rows=1000&q=omi-node-url="+omiURL;
      //console.log(urlToODS);
      $http({
      method: 'GET',
      url: urlToODS
      }).then(function(response) {
        //console.log(response);
          //$scope.indexedServices=response.data.records;
          $scope.indexedServices=response.data.datasets;
          //console.log( $scope.indexedServices);
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
            $scope.omiVersion =response.data.omiVersion;
            $scope.themes =response.data.themes;
            $scope.secuUrl = response.data.secuUrl;
            $scope.clientId=response.data.clientId;
            $scope.clientSecret=response.data.clientSecret;
            $scope.rootUrl = response.data.omiURL;
            $scope.checkbox=true;
            $scope.billingUrl = response.data.billingUrl;

            if($scope.secuUrl){
              $scope.singleSelect="secu";
            }

            if($scope.billingUrl){
              $scope.singleSelect="billing";
            }
            //console.log(omiVersion);
          }
          else {
            var msg= "getProfile"+response.data.msg;
            $window.alert(msg);
          }

    });
  }

$timeout(function () {
    //$scope.userName2=authService.getNickname();
    //$scope.email=authService.getEmail();
    //console.log(vm.nickname);
    
   vm.getProfile(vm.nickname);
   //vm.getExistingToken(vm.nickname);
   vm.getPurchasedData(vm.nickname);
   vm.getWallet(vm.nickname);
   //vm.getIndexedServices($scope.omiURL);
}, 600);

$timeout(function () {
  console.log($scope.omiURL);

  /* To modify: to do only when the users enters in the 'published data' page */
  vm.getIndexedServices($scope.omiURL);
 //vm.getExistingOdfTree($scope.omiURL);
 }, 700);
    
    $timeout(function () {
//console.log($scope.omiURL);
//console.log(vm.omiURL); 
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
            var msg= "saveProfile "+response.data.msg;
            $window.alert(msg);
          }

    });
  }

    $scope.payOnBaaS = function() {



        //localStorage.billing = true;
        localStorage.setItem('billing', true);
        //$state.go('billing')
        
        // console.log("cart"+$rootScope.cartTest[0]);

         var totalAmount = 0; 
         var tempPrice = 0; 
         var url1 ="";

          //var arrayUrlOMInode = new Array();
          var listOfServices = new Array();
          var listOfServicesFullURL = new Array();
          $scope.listOfServicesFullURL = [];
          $scope.listOfServicesNodeURL = [];
          $scope.listOfServicesServicesURL = [];
          //console.log("length"+$rootScope.cartTest.length);//=1

          //var promises = [];
          var promiseArray = [];
          for (var i = 0; i < $rootScope.cartTest.length ; i++) {
              //Request URL of the node on ODS
              
              var service = $rootScope.cartTest[i]["_id"];
              //console.log(service);
              var urlToGetURL = "https://biotope.opendatasoft.com/api/datasets/1.0/search/?apikey=<API_KEY/TOKEN>&q=datasetid="+service;
              //console.log("217: "+ urlToGetURL);
                             //$scope.listOfServicesFullURL=listOfServicesFullURL;
            $scope.hidCut=$rootScope.cartTest[i]["_name"].split("Objects");
            tempPrice=$rootScope.cartTest[i]["_price"] * $rootScope.cartTest[i]["_quantity"];;
            totalAmount+=tempPrice;
            //console.log(totalAmount);
            $scope.obj="Objects";
            $scope.hid=$scope.obj.concat($scope.hidCut[1]);
            listOfServices[i]=$scope.hid;
              
              if(i==0) {
                      $scope.listOfPrice=$rootScope.cartTest[i]["_price"];
                      $scope.listOfQuantity=$rootScope.cartTest[i]["_quantity"];
                    }
                    else {
                        $scope.listOfPrice+=","+$rootScope.cartTest[i]["_price"];
                        $scope.listOfQuantity+=","+$rootScope.cartTest[i]["_quantity"];
                    }

              promiseArray.push($http({
              method: 'GET',
              url: urlToGetURL
              }));




               /*$http({
              method: 'POST',
              url: urlToGetURL
              }).then(function(response) {
                  //console.log("216:"+response);
                  console.log("answer"+i);
                  url1 = response.data.datasets[i].metas["omi-node-url"]+response.data.datasets[i].metas.title;
                  console.log("Url"+i+" "+url1); 
                  listOfServicesFullURL[i]=url1;
                 $scope.listOfServicesFullURL[i]=url1;
                 console.log("223:"+$scope.listOfServicesFullURL[i]);

            });*/
              //promises.push(promise);
              //$q.all(promises).then(console.log(promises);
                $q.all(promiseArray).then(function(dataArray) {
                  //console.log(dataArray);
                  for (var i = 0; i < dataArray.length ; i++) {
                    var N1 = dataArray[i].data.datasets[0].metas["omi-node-url"];
                    var N2 = dataArray[i].data.datasets[0].metas.title;
                    url1 = N1+N2;
                    $scope.listOfServicesFullURL[i]=url1;
                    $scope.listOfServicesNodeURL[i]=N1;
                    $scope.listOfServicesServicesURL[i]=N2;
                    if(i==0) {
                      $scope.listOfServicesFullURLForAPIs=url1;
                    }
                    else {
                        $scope.listOfServicesFullURLForAPIs+=","+url1;
                    }
                  }

    // Each element of dataArray corresponds to each result of http request.
});
          };





          $timeout(function () {
              //Check if billing module, if so modify URL with the secured port of this module
              var promiseArray2 = [];
              console.log("233: "+ $scope.listOfServicesServicesURL);
              console.log("234: "+ $scope.listOfServicesFullURL);
              console.log("235: "+ $scope.listOfServicesNodeURL);
              for (var i = 0; i < $scope.listOfServicesNodeURL.length ; i++) {
                $scope.nU=$scope.listOfServicesNodeURL[i];
                console.log("nU"+$scope.nU);
                
$http({
                                  method: 'POST',
                                  url: 'api/getBillingUrl2.php',
                                  data: {'username': 'http://127.0.0.1:8080/' }
                                  }).then(function(response) {console.log(response)});

                  promiseArray2.push($http({
                                  method: 'POST',
                                  url: 'api/getBillingUrl2.php',
                                  data: {'username': $scope.nU }
                                  }));

                  $q.all(promiseArray2).then(function(dataArray) {
                  for (var i = 0; i < dataArray.length ; i++) {
                    console.log(dataArray[i]);
                  if(dataArray[i].data.stat==1){
                    console.log("bef"+dataArray[i].data.billingUrl+$scope.listOfServicesServicesURL[i]);
                    $scope.listOfServicesFullURL[i]=dataArray[i].data.billingUrl+$scope.listOfServicesServicesURL[i];       
                    console.log("aft"+$scope.listOfServicesFullURL[i]);  
                    url1=dataArray[i].data.billingUrl+$scope.listOfServicesServicesURL[i];
                    if(i==0) {
                      $scope.listOfServicesFullURLForAPIs=url1;
                    }
                    else {
                        $scope.listOfServicesFullURLForAPIs+=","+url1;
                    }

                  }
                  else{
                    url1=$scope.listOfServicesFullURL[i];
                    if(i==0) {
                      $scope.listOfServicesFullURLForAPIs=url1;
                    }
                    else {
                        $scope.listOfServicesFullURLForAPIs+=","+url1;
                    }

                  }
                }
                  });
                };
            }, 1000);

                  /*.then(function(response) {
                                if(response.data.stat==1){
                                  //$rootScope.RecordsPurchasedData = response.data.idPurchasedData;
                                  console.log(response.data.billingUrl);
                                  url1 = response.data.billingUrl+nodeTitle;
                                  console.log(url1);
                                }
                                else {
                                  url1 = nodeUrl+nodeTitle;
                                  console.log("else"+url1);
                                  //var msg= " "+response.data.msg;
                                  //$window.alert(msg);
                                }
                                });*/

              


          $timeout(function () {
          console.log("236:"+$scope.listOfServicesFullURL);
          console.log("237:"+$scope.listOfServicesFullURLForAPIs);
          console.log("238:"+$scope.listOfPrice);
          console.log("239:"+$scope.listOfQuantity);

          //First, request an invoice token
          //curl -X POST https://api-token:MJvhZiYQGrlu87TfBBSBi3EfU1y5KctP@baas.sghatpande.eu/invoice -d msatoshi=5000 -d metadata[customer_id]=9817 -d metadata[product_id]=7189
          //var urlToGetInvoiceToken = 'https://baas.sghatpande.eu/invoice -d msatoshi='+totalAmount+' -d metadata[customer_id]='+$scope.wallet+'-d metadata[product_id]='+listOfServices;
          
          //var urlToGetInvoiceToken ="https://api-token:MJvhZiYQGrlu87TfBBSBi3EfU1y5KctP@baas.sghatpande.eu/invoice"
          //NEW ONE AT UNI.LU
          var urlToGetInvoiceToken ="http://api-token:<API_KEY/TOKEN>@baas.serval.uni.lu/invoice"
          //var urlToGetInvoiceToken ="http://api-token:abcd5887@biotope.itrust.lu:9112/invoice"

          //console.log("new16");
          $http({
          method: 'POST',
          url: urlToGetInvoiceToken,
          headers: {
            'Content-Type': 'application/json',
            'Access-Control-Allow-Origin': '*'
          },
          data: {'msatoshi': totalAmount, 'metadata':{
            'customer_id': $scope.wallet, 
            'product_id': $scope.listOfServicesFullURL,
            'price_id': $scope.listOfPrice,
            'quantity_id': $scope.listOfQuantity}}
          }).then(function(response) {
            $scope.InvoiceToken= response.data.payreq;
    });

var domain="http://baas-gui.uni.lu:8081";



$timeout(function () {
  console.log($scope.InvoiceToken);

 var tokenUrl=domain+"/payment/pay?token="+$scope.InvoiceToken+"&service="+$scope.listOfServicesFullURLForAPIs+"&quantity="+$scope.listOfQuantity+"&price="+$scope.listOfPrice;
  $http({
          method: 'POST',
          url: tokenUrl
          }).then(function(response) {
            //console.log(response)
            //$scope.pay=response.status;
    });
  }, 3500);

          
       $window.alert("You will be redirect to the payment website for execute the money transactions");
        
        var landingUrl=domain+"/list/";
        //console.log(landingUrl);
        //$window.location.href = landingUrl;
        //var win = $window.open(landingUrl,'_blank');
        $timeout(function () {
        var win = $window.open(landingUrl,'_blank');

        var timer = setInterval(function() {
        if (win.closed) {
            clearInterval(timer);
            //paid();
            //alert("'Secure Payment' window closed !");

            var tokenUrl2=domain+"/payment/verify?token="+$scope.InvoiceToken+"&service="+$scope.listOfServicesFullURLForAPIs;
            $http({
          method: 'POST',
          url: tokenUrl2
          }).then(function(response) {
            //console.log(response.status);
            //console.log(response.data);
            if(response.status==200){
              $scope.hashToken=response.data;
              alert("Your payment is accepted! You can go to your account to get the url (and the associated token) for accessing your data.");
              
              //Save Purchased data into DB
                console.log("301:"+$scope.listOfServicesFullURL);
            $http({
            method: 'POST',
            url: 'api/savePurchasedDataWithToken.php',
            data: {'username': $rootScope.currentUser, 'items': $rootScope.cartTest, 'services': $scope.listOfServicesFullURL, 'token': $scope.hashToken}
            }).then(function(response) {
          if(response.data.stat==1){
            //var msg= " "+response.data.msg;
            //$window.alert(msg);
            //$rootScope.RecordsPurchasedData = response.data.idPurchasedData;
          }
          else {
            var msg= " "+response.data.msg;
            $window.alert(msg);
          }
          })

              //go to account
              $timeout(function () {
            $http({
            method: 'POST',
            url: 'api/getPurchasedData.php',
            data: {'username': $rootScope.currentUser}
            }).then(function(response) {
          if(response.data.stat==1){
            $rootScope.RecordsPurchasedData = response.data.idPurchasedData;
          }
          else {
            var msg= " "+response.data.msg;
            $window.alert(msg);
          }
          });
            }, 1000);

$state.go('memberDashboard');
            }
            else {
              alert("Your payment is not accepted! Please proceed once again to your payment or come back later on!");
            }
            
            //console.log(response);
            //$scope.pay=response.status;
    });
           //alert($scope.pay);
        }
    }, 2500);
 }, 3500);

  }, 1500);

      };


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
            var msg= "Please log in!";//+response.data.msg;
            $window.alert(msg);
          }

    });
  }

$scope.saveServer = function(omiURL, omiName, omiAddr, themes, secuUrl, clientId, clientSecret, omiVersion, billingUrl) {
  //$scope.saveServer = function(omiURL, omiName, omiAddr, themes) {
    //console.log($scope.omiVersion);
      $http({
      method: 'POST',
      url: 'api/saveServer.php',
      data: {'username': vm.nickname, 'omiURL': omiURL, 'omiName': omiName, 'omiAddr': omiAddr, 'themes': themes ,'secuUrl': secuUrl, 'clientId': clientId, 'clientSecret': clientSecret, 'omiVersion': omiVersion, 'billingUrl': billingUrl}
      //data: {'username': vm.nickname, 'omiURL': omiURL, 'omiName': omiName, 'omiAddr': omiAddr, 'themes': themes}
      }).then(function(response) {
          if(response.data.stat==1){
            //$location.path("/member");
          }
          else {
            var msg= "saveServer "+response.data.msg;
            $window.alert(msg);
          }

    });

      
      $http({
      method: 'POST',
      url: 'api/indexing.php',
      data: {'username': vm.nickname, 'omiURL': omiURL, 'omiName': omiName, 'omiAddr': omiAddr, 'themes': themes ,'secuUrl': secuUrl, 'clientId': clientId, 'clientSecret': clientSecret, 'omiVersion': omiVersion, 'billingUrl': billingUrl}
      //data: {'username': vm.nickname, 'omiURL': omiURL, 'omiName': omiName, 'omiAddr': omiAddr, 'themes': themes}
      }).then(function(response) {
          if(response.data.stat==1){
            //$location.path("/member");
          }
          else {
            var msg= "indexing "+response.data.msg;
            $window.alert(msg);
          }

    });
  
  }

  $scope.testFunction = function(omiName) {
  //$scope.saveServer = function(omiURL, omiName, omiAddr, themes) {
    //console.log($scope.omiVersion);
      $http({
      method: 'POST',
      url: 'api/testFunction.php',
      data: {'omiName': omiName}
      //data: {'username': vm.nickname, 'omiURL': omiURL, 'omiName': omiName, 'omiAddr': omiAddr, 'themes': themes}
      }).then(function(response) {
        var msg= response.data.msg;
            $window.alert(msg);

    });  
  }


  $scope.saveWallet = function(wallet) {
      $http({
      method: 'POST',
      url: 'api/saveWallet.php',
      //data: {'username': vm.nickname, 'omiURL': omiURL, 'omiName': omiName, 'omiAddr': omiAddr, 'secuUrl': secuUrl, 'clientId': clientId, 'clientSecret': clientSecret}
      data: {'username': vm.nickname, 'wallet': wallet}
      }).then(function(response) {
          if(response.data.stat==1){
            //$location.path("/member");
          }
          else {
            var msg= " saveWallet"+response.data.msg;
            $window.alert(msg);
          }

    });
  }


  $scope.createWallet = function() {
      //TO BE DEFINED 
      $window.alert("GO TO BAAS UI");
  }




$scope.publish = function() {
  var msg= "Modifications ";
            $window.alert(msg);
  //console.log($scope.omiURL);
      /*$http({
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

    });*/
  }

    $scope.error = {
      show: false,
      message: ''
    };


}]);