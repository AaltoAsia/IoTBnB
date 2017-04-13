(function () {
  'use strict';

  angular
    .module('IoTBnB')
    .controller('LogoutController', LogoutController);

  LogoutController.$inject = ['authService', '$state', '$timeout'];

  function LogoutController(authService, $state, $timeout) {

    var vm = this;
    vm.authService = authService;

    vm.authService.logout();

    //$location.url('home');
    //$timeout(function () {
    //$state.go('home');
      //  }, 1000);

  }

}());
