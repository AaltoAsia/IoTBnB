//'use strict'

//author: J. Robert
//creation date: 01/03/2016
//modification date: 05/09/2016

/**
 * @ngdoc overview
 * @name IoTBnB
 * @description
 * # IoTBnB
 *
 * Main module of the application.
 */

var app = angular
.module('IoTBnB', [
  'auth0', 'angular-storage', 'angular-jwt',
  'ngRoute', 
  'ngMap',
  'ngDialog',
  'infinite-scroll', 'ngSanitize', 'gettext',
  'ngStorage',
  'ngCart'//,
  //'ods-widgets'
]);

app.config( function myAppConfig (authProvider) {
  //authProvider init configuration
  authProvider.init({
    domain: AUTH0_DOMAIN,
    clientID: AUTH0_CLIENT_ID,
    loginUrl: '/login' // matches login url
});

//Called when login is successful
authProvider.on('loginSuccess', ['$location', 'profilePromise', 'idToken', 'store','$rootScope', function($location, profilePromise, idToken, store, $rootScope) {
  // Successfully log in
  // Access to user profile and token
  profilePromise.then(function(profile){
    // profile
    store.set('profile', profile);
    store.set('token', idToken);
    $rootScope.redirectModeProfile = profile;
  });
  $location.url('/member');
}]);

//Called when login fails
authProvider.on('loginFailure', function() {
  // If anything goes wrong
$location.path('/');
});

//authProvider.on('authenticated', function($location, $rootScope) {
  // if user is authenticated.
  // Useful in re-authentication
  //console.log("yy")
  //$rootScope.redirectModeProfile =store.get('profile');
  //console.log($rootScope.redirectModeProfile);
  //$location.url('/member');
//});

}).run(['$rootScope', 'auth', 'store', 'jwtHelper', '$location', function($rootScope, auth, store, jwtHelper, $location) {
  // Listen to a location change event
  $rootScope.$on('$locationChangeStart', function() {
    // Grab the user's token
    var token = store.get('token');
    //console.log(token);
    // Check if token was actually stored
    if (token) {
      // Check if token is yet to expire
      if (!jwtHelper.isTokenExpired(token)) {
        // Check if the user is not authenticated
        if (!auth.isAuthenticated) {
          auth.authenticate(store.get('profile'), token).then(function(profile){
            $rootScope.redirectModeProfile = profile;
            $location.url('/member');
          })
          // Re-authenticate with the user's profile
          // Calls authProvider.on('authenticated')
          //auth.authenticate(store.get('profile'), token);
        }
      } else {
        //$location.path('/login');
        // Either show the login page
         //$location.path('/');
        // .. or
        // or use the refresh token to get a new idToken
        auth.refreshIdToken(token);
      }
    }
  });

$rootScope.login = function(){
    auth.signin();
  }

$rootScope.logout = function(){
    auth.signout();
    store.set('profile',"");
    store.set('token',"");
    //store.remove('token');
    $location.url('/');
  }
}])

/**
 * Configure the Routes
 */
app.config(['$routeProvider', function ($routeProvider) {
  $routeProvider
    // Home
    .when("/", {templateUrl: "views/partials/map.html", controller: "IoTBnBController", requiresLogin: false})
    .when("/seller", {templateUrl: "views/partials/NewSeller.html", controller: "PageCtrl", requiresLogin: false})
    .when("/login", {templateUrl: "views/partials/login.html", controller: "LoginController", requiresLogin: false})
    .when("/member", {templateUrl: "views/partials/member.html", controller: "PrivateSpaceController", requiresLogin: true
        })
    .when("/memberData", {templateUrl: "views/partials/memberData.html", controller: "PrivateSpaceController", requiresLogin: true
      })
    .when("/memberDashboard", {templateUrl: "views/partials/memberDashboard.html", controller: "PageCtrl"})
    .when("/fetchData", {templateUrl: "views/partials/dataFetching.html", controller: "PageCtrl", requiresLogin: false})
    .when("/AdvStat", {templateUrl: "views/partials/AdvStat.html", controller: "PageCtrl", requiresLogin: false})
    .when("/cart", {templateUrl: "views/partials/cart.html", controller: "PageCtrl", requiresLogin: false})
    .when("/billing", {templateUrl: "views/partials/cart.html", controller: "PageCtrl", requiresLogin: true})
    // else 404
    .otherwise("/404", {templateUrl: "views/partials/404.html", controller: "PageCtrl"});
}]);