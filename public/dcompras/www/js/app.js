// Ionic Starter App

// angular.module is a global place for creating, registering and retrieving Angular modules
// 'starter' is the name of this angular module example (also set in a <body> attribute in index.html)
// the 2nd parameter is an array of 'requires'
// 'starter.controllers' is found in controllers.js
angular.module('starter', ['ionic', 'starter.controllers' ])
.factory("Favorites",function(){
	 var _favorites = function () {
		 this._storage = Storage?window.localStorage:null;
		 
		 this.deleteFavorite = function (item){
			 var _auxSto = this.get();
			 if (!_auxSto)
				 return false;
			 for (var i in _auxSto){
				 if (_auxSto[i].id == item.id){
					 _auxSto.splice(i,1);
				 }
					 
			 }
			 var strFav = JSON.stringify(_auxSto);
			 this._storage.setItem("favorites", strFav);
			 return true; 
		 };
		 
		 this.get = function  () {
			 if (this._storage){
				return this._storage.getItem("favorites")===null?[]:JSON.parse(this._storage.getItem("favorites")); 
			 }else{
				 return null;
			 };
		 };
		 this.exists = function (item){
			 var _auxSto = this.get();
			 if (!_auxSto)
				 return false;
			 for (var i in _auxSto){
				 if (_auxSto[i].id == item.id)
					 return true;
			 }
			 return false;
		 };
		 this.add = function (item) {
			 var _auxSto = this.get();
			 if (!_auxSto)
				 return false;
			 for (var i in _auxSto){
				 if (_auxSto[i].id == item.id)
					 return false;
			 }
			 _auxSto.push(item);
			 var strFav = JSON.stringify(_auxSto);
			 this._storage.setItem("favorites", strFav);
			 return true;
		 };
	 };
	 return new _favorites;
 })
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
			"SUDADERAS" : {
				"es" : "Sudaderas"
			},
			"SUDADERAS_HOMBRE" : {
				"es" : "Sudaderas hombre"
			},
			"SUDADERAS_MUJER" : {
				"es" : "Sudaderas mujer"
			},
			"CAMISAS" : {
				"es" : "Camisas"
			},
			"CAMISAS_HOMBRE" : {
				"es" : "Camisas hombre"
			},
			"CAMISAS_MUJER" : {
				"es" : "Camisas mujer"
			},
			"CAMISETAS" : {
				"es" : "Camisetas"
			},
			"CAMISETAS_HOMBRE" : {
				"es" : "Camisetas hombre"
			},
			"CAMISETAS_MUJER" : {
				"es" : "Camisetas mujer"
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
			if (typeof this._t[key] !== "undefined"){
			  if (typeof this._t[key][this.language] !== "undefined"){
				  return this._t[key][this.language];
			  }else if (this.language !== "es" && typeof this._t[key]["es"] !== "undefined") {
				  return this._t[key]["es"];
			  }else{
				  return key;
			  }
			}else  {
			   return key;	
			}
			
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

  .state('app.fav', {
    url: "/fav",
    views: {
      'menuContent': {
        templateUrl: "templates/fav.html" ,
        controller: 'FavCtrl'
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
