(function () {

  'use strict';

  angular
    .module('IoTBnB')
    .run(run);

  run.$inject = ['$rootScope', 'authService', 'lock'];

  function run($rootScope, authService, lock) {
    // Put the authService on $rootScope so its methods
    // can be accessed from the nav bar
    $rootScope.authService = authService;

    // Register the authentication listener that is
    // set up in auth.service.js
    authService.registerAuthenticationListener();

    // Register the synchronous hash parser
    lock.interceptHash();

    $rootScope.$on('$stateChangeStart', function (event, toState, toParams) {
    
    //console.log(toState.redirectUrl);

    if (toState.url != "/login" || toState.url != '/billing'){
    localStorage.setItem('url',toState.redirectUrl)
    }

    if (toState.url == '/billing'){
      
      if( localStorage.getItem('id_token') == null){
        authService.login();

      }
      //event.preventDefault();
    }
    console.log(localStorage.getItem('url'))



  });
  }

})();
