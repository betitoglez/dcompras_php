angular.module('starter.controllers', [])

.controller('AppCtrl', function($scope,$http, $ionicModal, $timeout, Global,i18,$ionicLoading,$location) {
	//Translate module
	$scope.t = i18;
	
	//Loading
	$scope.showLoading = function() {$ionicLoading.show({
	      template: i18.translate("CARGANDO")
	});};
	
	//Offset
	$scope.offset = 0;
	$scope.finished = false;
	
	$scope.showLoading();
	
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
    //$scope.storesModel = $scope.stores[0];
    
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
    
    $scope.items = [];
    
    $http.get(Global.url + "/api/products").success(function(data){
		$scope.items = data;
		$ionicLoading.hide();
	});

    $scope.params = "";
    
    $scope.refresh = function (){
    	$scope.offset = 0;
    	$scope.finished = false;
    	$scope.showLoading();
    	var _params = "";
    	var _id = $("select[name='slc-stores'] option:selected").val();
    	if (_id != "0"){
    		_params += "id_store="+_id+"&";
    	}
    	_id = $("select[name='slc-categories'] option:selected").val();
    	if (_id != "0"){
    		_params += "id_category="+_id+"&";
    	}
    	
    	
    	$scope.params = _params;
    	$location.path("/app/items");
    	$http.get(Global.url + "/api/products?"+_params).success(function(data){
    		if (data.length < 20){
    			$scope.finished = true;
    		}
    		$scope.items = data;
    		$ionicLoading.hide();
    	});
    };
    
    $scope.imageUrl = Global.imageUrl;
    
    $scope.loadMore = function () {
    	$scope.offset += 20;
    	var _params = $scope.params + "offset=" + $scope.offset;
    	$http.get(Global.url + "/api/products?"+_params).success(function(data){
    		if (data.length < 20){
    			$scope.finished = true;
    		}
    		$scope.items = $scope.items.concat(data);
    		$ionicLoading.hide();
    		$scope.$broadcast('scroll.infiniteScrollComplete');
    	});
		
		 
	};
	
	$scope.$on('$stateChangeSuccess', function() {
	    $scope.loadMore();
	});

})

.controller('ItemsCtrl', function($scope , $http , Global) {
	
	
	
  
})

.controller('ItemCtrl', function($scope, $stateParams, $location,$ionicModal) {
	if ($scope.items.length == 0){
		$location.path("/app/items");
	}
	
	$ionicModal.fromTemplateUrl('modal-image.html', {
	    scope: $scope,
	    animation: 'slide-in-up'
	  }).then(function(modal) {
	    $scope.modal = modal;
	  });
	  $scope.openModal = function() {
	    $scope.modal.show();
	  };
	  $scope.closeModal = function() {
	    $scope.modal.hide();
	  };
	  //Cleanup the modal when we're done with it!
	  $scope.$on('$destroy', function() {
	    $scope.modal.remove();
	  });
	  
	var id = $stateParams.ItemId, selectedId = null;
	for (var i in $scope.items){
		if ($scope.items[i].id == id){
			selectedId = i;
		}
	}
	$scope.selectedItem = $scope.items[selectedId];
	
	$scope.openUrl = function ()
	{
		window.open($scope.selectedItem.url,'_system');
	};
	
});
