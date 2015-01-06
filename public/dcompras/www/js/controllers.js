angular.module('starter.controllers', [])

.controller('AppCtrl', function($scope,$http, $ionicModal, $timeout, Global) {
	$http.get(Global.url + "/api/stores").success(function(data){
		$scope.stores = [{id:0,name:"Todas"}];
		for (var i in data){
			$scope.stores.push(data[i]);
		}
		$scope.storesModel = $scope.stores[0];
	});
	$http.get(Global.url + "/api/categories").success(function(data){
		$scope.categories = [{id:0,name:"Todas"}];
		for (var i in data["maps"]){
			var _aux = {id:i,name:data["maps"][i]["name"]};
			$scope.categories.push(_aux);
		}
		$scope.categoriesModel = $scope.categories[0];
	});
    $scope.stores = [{id:0,name:"Cargando tiendas..."}];
    $scope.storesModel = $scope.stores[0];
    
    $scope.categories = [{id:0,name:"Cargando categorias..."}];
    $scope.categoriesModel = $scope.categories[0];
    
    $scope.minPrice = "";
    $scope.maxPrice = "";
    
    if (navigator.globalization){
    	navigator.globalization.getPreferredLanguage(
    	    function (language) {alert('language: ' + language.value + '\n');},
    	    function () {alert('Error getting language\n');}
    	);
    };
    
    $scope.items = [
                    { name: 'Cargando...', id: 1 }
                  ];
    $http.get(Global.url + "/api/products").success(function(data){
		$scope.items = data;
	});
    
    $scope.loadMore = function () {
		/*
		 console.log("Recarga");
		 console.log($scope.playlists);
		 $scope.playlists.push([{name:"Prueba" , id:212}]);
		 
		 if ($scope.items.push){
			 
			 $scope.items.push({"2":{"id":"2","name":"MARCA B\u00c1SICA SUDADERA","extid":"30-12082458","price":"27.95","image":"http:\/\/demandware.edgesuite.net\/sits_pod17\/dw\/image\/v2\/AAGB_PRD\/on\/demandware.static\/Sites-ROE-Site\/","url":"http:\/\/jackjones.com\/jack-jones\/sweat\/levy-sweat-hood\/12082458,es_ES,pd.html?dwvar_12082458_colorPattern=12082458_Black_438558&forceScope=","date_created":"2015-01-04 23:22:25","id_store":"30","id_product":"2","id_category":"52"}});
		 }
		 if (false === $scope.items instanceof Array){
			 console.log("vegi");
			 $scope.items["999"] =  {id : 999 , name : "Marca de prueba"};
			 $scope.items = {};
			 console.log($scope.items);
		 }
		 */
   
		 $scope.$broadcast('scroll.infiniteScrollComplete');
		 
	};
	
	$scope.$on('$stateChangeSuccess', function() {
	    $scope.loadMore();
	});

})

.controller('ItemsCtrl', function($scope , $http , Global) {
	
	
	
  
})

.controller('ItemCtrl', function($scope, $stateParams) {
});
