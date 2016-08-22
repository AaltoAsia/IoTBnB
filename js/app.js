//'use strict'

//author: J. Robert
//creation date: 01/03/2016
//modification date: 18/08/2016

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
  'ngMap'
]);

app.config( function myAppConfig (authProvider) {
  //authProvider init configuration
  authProvider.init({
    domain: '', //You have to add your auth0 domain
    clientID: ''//You have to add your auth0 key
});

//Called when login is successful
authProvider.on('loginSuccess', ['$location', 'profilePromise', 'idToken', 'store','$rootScope',  function($location, profilePromise, idToken, store, $rootScope) {
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
        $location.path('/login');
        // Either show the login page
        // $location.path('/');
        // .. or
        // or use the refresh token to get a new idToken
        //auth.refreshIdToken(token);
      }
    }
  });
/*  $rootScope.signOut = function signOut () {
    auth.signout();
    store.remove('profile');
    store.remove('token');
    $location.path('tab/leads');
  } */
}])

/**
 * Configure the Routes
 */
app.config(['$routeProvider', function ($routeProvider) {
  $routeProvider
    // Home
    .when("/", {templateUrl: "views/partials/map.html", controller: "PageCtrl", requiresLogin: false})
    .when("/seller", {templateUrl: "views/partials/NewSeller.html", controller: "PageCtrl", requiresLogin: false})
    // Pages
    .when("/member", {templateUrl: "views/partials/member.html", controller: "PrivateSpaceController", requiresLogin: true
        })
    //
    .when("/memberData", {templateUrl: "views/partials/memberData.html", controller: "PrivateSpaceController", requiresLogin: true
      })
    //
    .when("/memberDashboard", {templateUrl: "views/partials/memberDashboard.html", controller: "PageCtrl"})
    // else 404
    .otherwise("/404", {templateUrl: "views/partials/404.html", controller: "PageCtrl"});
}]);