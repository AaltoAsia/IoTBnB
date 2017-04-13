(function () {

  'use strict';

  angular
    .module('IoTBnB')
    .service('authService', authService);

  authService.$inject = ['$q','lock', 'authManager', '$state'];

  function authService($q, lock, authManager, $state) {

  
    var username=null;
    var email=null;
    var family_name=null;
    var gender=null;
    var given_name=null;
    var locale=null;
    var name=null;
    var nickname=null;
    var picture=null;

    function login() {
      lock.show();
    }

    function redirectTo() {
      $state.go('billing');
    }

    // Logging out just requires removing the user's
    // id_token and profile
    function logout() {
      localStorage.removeItem('id_token');
      authManager.unauthenticate();
      $state.go('logout');
    }

    // Set up the logic for when a user authenticates
    // This method is called from app.run.js
    function registerAuthenticationListener() {
      lock.on('hide', function () {
      console.log("lock hidden!")
    });

      lock.on('authenticated', function (authResult) {

        
          localStorage.setItem('id_token', authResult.idToken);
          authManager.authenticate();

        lock.getProfile(authResult.idToken, function(error, profile) { //This function is soon deprecated !!!
          if (error) {
            alert("Error in the authentication process");
          }
          //console.log(profile)

          email = profile.email;
          family_name = profile.family_name;
          gender=profile.gender;
          given_name=profile.given_name;
          locale=profile.locale;
          name=profile.name;
          nickname=profile.nickname;
          picture=profile.picture;
          //console.log(userName);
      
      });

        console.log(localStorage.getItem('url'))
          if (typeof localStorage.getItem('url')  != 'undefined'){
            $state.go(localStorage.getItem('url'))
          }
          else {
            $state.go('home')
          }
          
          //event.preventDefault();

      });
    }

    function getEmail(){
      return email;
    }

    function getFamilyName(){
      return family_name;
    }

     function getGender(){
      return gender;
    }

    function getGivenName(){
      return given_name;
    }

     function getLocale(){
      return locale;
    }

    function getName(){
      return name;
    }

    function getNickname(){
      //console.log("in function"+userName);
      return nickname;
    }

    function getPicture(){
      //console.log("in function"+userName);
      return picture;
    }


    return {
      login: login,
      logout: logout,
      registerAuthenticationListener: registerAuthenticationListener,
      getEmail: getEmail,
      getFamilyName: getFamilyName,
      getGender: getGender,
      getGivenName: getGivenName,
      getLocale: getLocale,
      getName: getName,
      getNickname: getNickname,
      getPicture: getPicture,
      redirectTo: redirectTo
    }
  }
})();
