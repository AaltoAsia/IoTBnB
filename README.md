# bIoTope IoTBnB

This repository contains the source code of the marketplace/service catalog component of the bIoTope ecosystem. 

## Description

IoTBnB (**IoT** service pu**B**lication and **B**illing) is a developed as a marketplace that serves as a service catalogue within the bIoTope ecosystem. This marketplace was developed as a means to assist the two stakeholders of the ecosystem: IoT data/service produces/publishers and IoT data/service consumers. The marketplace permits searching and consumption of IoT generated data streams or services with potentially the objective to re-use them.

## Getting started

The implementation makes use of OpenDataSoft platform for the indexing and storage of the data items. As such, using this implementation in its current state requires the user to have usage agreement with OpenDataSoft or the user needs to develop their own component for such indexing and storage. The instructions assume that the user is using the OpenDataSoft platform.

### Prerequisites

The implemented website also has a local MySQL database which needs to installed on the machine. Once the database is up and running, the user needs to configure the API key and the credentials within the code as mentioned in the CREDENTIALS.md.

### Installation

To have the instance of IoTBnB running is easy. First clone the repo and set the correct Auth0 values in the file `js/auth0.variables.js`. Now, make sure to install the dependencies of the code using the following:

```bash
bower install
npm install -g serve
```

That’s it, now a simple `serve` would get the website up and running locally on port 5000.

## bIoTope integration
The IoTBnB is integrated with the Billing-as-a-Service, Security-as-a-Service components of the bIoTope ecosystem.

## Authors
@jrobert-github

## License
This project is licensed under the ??? - see the LICENSE.md file for details.

## Acknowledgments
This project has been developed as part of the bIoTope Project, which has received funding from the European Union’s Horizon 2020 Research and Innovation Programme under grant agreement No. 688203.

## Important Snippets

### 1. Add the module dependencies and configure the service

```js
// app.js
(function () {

'use strict';

angular
.module('app', ['auth0.lock', 'angular-jwt', 'ui.router'])
.config(config);

config.$inject = ['$stateProvider', 'lockProvider', '$urlRouterProvider'];

function config($stateProvider, lockProvider, $urlRouterProvider) {

$stateProvider
.state('home', {
url: '/home',
controller: 'HomeController',
templateUrl: 'components/home/home.html',
controllerAs: 'vm'
})
.state('login', {
url: '/login',
controller: 'LoginController',
templateUrl: 'components/login/login.html',
controllerAs: 'vm'
});

lockProvider.init({
clientID: AUTH0_CLIENT_ID,
domain: AUTH0_DOMAIN
});

$urlRouterProvider.otherwise('/home');
}

})();
```

```js
// app.run.js
(function () {

'use strict';

angular
.module('app')
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
}

})();
```

### 2. Implement the auth service

```js
// components/auth/auth.service.js
(function () {

'use strict';

angular
.module('app')
.service('authService', authService);

authService.$inject = ['lock', 'authManager'];

function authService(lock, authManager) {

function login() {
lock.show();
}

// Logging out just requires removing the user's
// id_token and profile
function logout() {
localStorage.removeItem('id_token');
localStorage.removeItem('profile');
authManager.unauthenticate();
}

// Set up the logic for when a user authenticates
// This method is called from app.run.js
function registerAuthenticationListener() {
lock.on('authenticated', function (authResult) {
localStorage.setItem('id_token', authResult.idToken);
authManager.authenticate();
});
}

return {
login: login,
logout: logout,
registerAuthenticationListener: registerAuthenticationListener
}
}
})();
```

### 3. Implement the login controller

```js
// components/login/login.controller.js
(function () {
'use strict';

angular
.module('app')
.controller('LoginController', LoginController);

LoginController.$inject = ['authService'];

function LoginController(authService) {

var vm = this;
vm.authService = authService;

}

}());
```

### 4. Add the login view

```html
<!-- components/login/login.html -->
<div class="jumbotron">
<h2 class="text-center"><img src="https://cdn.auth0.com/styleguide/1.0.0/img/badge.svg"></h2>
<h2 class="text-center">Login</h2>
<div class="text-center">
<button class="btn btn-primary" ng-click="vm.authService.login()">Log In</button>
</div>
</div>
```

### 5. Update the home controller

```js
// components/home/home.controller.js
(function () {

'use strict';

angular
.module('app')
.controller('HomeController', HomeController);

HomeController.$inject = ['authService'];

function HomeController(authService) {

var vm = this;
vm.authService = authService;

}

}());
```

### 6. Update the home view

```html
<!-- components/home/home.html -->
<div class="jumbotron">
<h2 class="text-center"><img src="https://cdn.auth0.com/styleguide/1.0.0/img/badge.svg"></h2>
<h2 class="text-center">Home</h2>
<div class="text-center" ng-if="!isAuthenticated">
<p>You are not yet authenticated. <a href="#/login">Log in.</a></p>
</div>
<div class="text-center" ng-if="isAuthenticated">
<p>Thank you for logging in! <a href="javascript:;" ng-click="vm.authService.logout()">Log out.</a></p>
</div>
</div>

```

