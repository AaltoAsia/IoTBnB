(function () {

  'use strict';

  angular
    .module('IoTBnB', ['auth0.lock', 'angular-jwt', 'ui.router','ngMap',
  'ngDialog','infinite-scroll', 'ngSanitize', 'gettext','ngCart', 'ui.bootstrap'])
    .config(config);

  config.$inject = ['$stateProvider', 'lockProvider', '$urlRouterProvider'];

  function config($stateProvider, lockProvider, $urlRouterProvider) {

    $stateProvider
      .state('home', {
        url: '/home',
        controller: 'IoTBnBController',
        templateUrl: 'views/partials/map.html',
        controllerAs: 'vm',
        redirectUrl: 'home'
      })
      .state('login', {
        url: '/login',
        controller: 'LoginController',
        templateUrl: 'views/partials/login.html',
        controllerAs: 'vm',
        redirectUrl: 'home'
      })
      .state('logout', {
        url: '/logout',
        controller: 'LogoutController',
        templateUrl: 'views/partials/logout.html',
        controllerAs: 'vm',
        redirectUrl: 'home'
      })
      .state('seller', {
        url: '/seller',
        controller: 'PageCtrl',
        templateUrl: 'views/partials/NewSeller.html',
        controllerAs: 'vm',
        redirectUrl: 'home'
      })
      .state('member', {
        url: '/member',
        controller: 'PrivateSpaceController',
        templateUrl: 'views/partials/member.html',
        controllerAs: 'vm',
        redirectUrl: 'home'
      })
       .state('memberData', {
        url: '/memberData',
        controller: 'PrivateSpaceController',
        templateUrl: 'views/partials/memberData.html',
        controllerAs: 'vm',
        redirectUrl: 'home'
      })
        .state('memberDashboard', {
        url: '/memberDashboard',
        controller: 'DashboardCtrl',
        templateUrl: 'views/partials/memberDashboard.html',
        controllerAs: 'vm',
        redirectUrl: 'home'
      })
        .state('memberServerInfo', {
        url: '/memberServerInfo',
        controller: 'PrivateSpaceController',
        templateUrl: 'views/partials/memberServerInfo.html',
        controllerAs: 'vm',
        redirectUrl: 'home'
      })
         .state('memberWallet', {
        url: '/memberWallet',
        controller: 'PrivateSpaceController',
        templateUrl: 'views/partials/memberWallet.html',
        controllerAs: 'vm',
        redirectUrl: 'home'
      })
      .state('AdvStat', {
        url: '/AdvStat',
        controller: 'PageCtrl',
        templateUrl: 'views/partials/AdvStat.html',
        controllerAs: 'vm',
        redirectUrl: 'home'
      })
        .state('cart', {
        url: '/cart',
        controller: 'PageCtrl',
        templateUrl: 'views/partials/cart.html',
        controllerAs: 'vm',
        redirectUrl: 'billing'
      })
           .state('billing', {
        url: '/billing',
        controller: 'PageCtrl',
        templateUrl: 'views/partials/billing.html',
        controllerAs: 'vm',
        redirectUrl: 'billing'
      })  ;

    lockProvider.init({
      clientID: AUTH0_CLIENT_ID,
      domain: AUTH0_DOMAIN
    });

    $urlRouterProvider.when("", "home")
    //.otherwise('/home');

      
  }

})();
