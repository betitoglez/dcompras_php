// Ionic Starter App

// angular.module is a global place for creating, registering and retrieving Angular modules
// 'starter' is the name of this angular module example (also set in a <body> attribute in index.html)
// the 2nd parameter is an array of 'requires'
// 'starter.controllers' is found in controllers.js
angular.module('starter', ['ionic', 'starter.controllers'])
.factory("Global",function(){
	
	var _imageUrl = function () {
		if (navigator.connection){
			return "/proxy/http://local.dcompras/resource/image/id/";
		} else {
			return "http://local.dcompras/resource/image/id/";
		}
	};
	return  {
		 url : "http://local.dcompras" ,
		 imageUrl : _imageUrl()
	};
})
.factory ("i18",function(){
	var i18 = function () {
		
		this._t = {
			"CARGANDO" : {
				"es" : "Cargando..."
			} ,
			"SUDADERAS_HOMBRE" : {
				"es" : "Sudaderas hombre"
			}
		};
		
		this.language = "es";
		this.setLanguage = function (lang){
			this.language = lang;
		} ;
		this.getLanguage = function () {
			return this.language;
		} ;
		
		this.translate = function (key){
			return typeof this._t[key] !== "undefined"?this._t[key][this.language]:"i18 not available";
		} ;
	};
	
	return new i18;
})
.run(function($ionicPlatform) {
  $ionicPlatform.ready(function() {
    // Hide the accessory bar by default (remove this to show the accessory bar above the keyboard
    // for form inputs)
    if (window.cordova && window.cordova.plugins.Keyboard) {
      cordova.plugins.Keyboard.hideKeyboardAccessoryBar(true);
    }
    if (window.StatusBar) {
      // org.apache.cordova.statusbar required
      StatusBar.styleDefault();
    }
  });
})

.config(function($stateProvider, $urlRouterProvider) {
  $stateProvider

  .state('app', {
    url: "/app",
    abstract: true,
    templateUrl: "templates/menu.html",
    controller: 'AppCtrl'
  })

  .state('app.search', {
    url: "/search",
    views: {
      'menuContent': {
        templateUrl: "templates/search.html"
      }
    }
  })

  .state('app.browse', {
    url: "/browse",
    views: {
      'menuContent': {
        templateUrl: "templates/browse.html"
      }
    }
  })
    .state('app.items', {
      url: "/items",
      views: {
        'menuContent': {
          templateUrl: "templates/items.html",
          controller: 'ItemsCtrl'
        }
      }
    })

  .state('app.item', {
    url: "/item/:ItemId",
    views: {
      'menuContent': {
        templateUrl: "templates/item.html",
        controller: 'ItemCtrl'
      }
    }
  });
  // if none of the above states are matched, use this as the fallback
  $urlRouterProvider.otherwise('/app/items');
});
