angular.module('starter.controllers', [ "isteven-multi-select"])

.controller('AppCtrl', function($scope,$http, $ionicModal, $timeout, Global,i18,$ionicLoading,$location,Favorites,$ionicScrollDelegate,$ionicSideMenuDelegate ) {
	//Translate module
	$scope.t = i18;
	
	$scope.langs = {
		    selectAll       : "Todos",
		    selectNone      : "Ninguno",
		    reset           : "Resetear",
		    search          : "Buscar...",
		    nothingSelected : "Nada seleccionado"     
		};
	$scope.langShops = angular.extend({},$scope.langs,{nothingSelected:"Ninguna tienda"});
	$scope.langCat = angular.extend({},$scope.langs,{nothingSelected:"Ninguna categoria"});
	//Hide Right Menu
	
	$scope.$on( "$ionicView.enter", function( scopes, states ) {
      if (states.stateId && states.stateId != "app.items"){
        $("span.right-buttons").hide();
      }else if (states.stateId && states.stateId == "app.items"){
    	  $("span.right-buttons").show(); 
      }
    });

	
	
	//Type Top Menu
	$scope.topMenu = {
		 defaultClassView : "ion-navicon-round"	,
		 defaultTypeView : "list" ,
		 showList : true
	};
	
	$scope.typeLeftView = "view";
	
	$scope.changeTypeView = function (type){
		$scope.typeLeftView = type;
	};
	
	//Loading
	$scope.showLoading = function() {$ionicLoading.show({
	      template: i18.translate("CARGANDO")
	});};
	
	//Offset
	$scope.offset = 0;
	$scope.finished = false;
	
	$scope.showLoading();
	
	//Scroll top 
	 $scope.scrollTop = function() {
		    $ionicScrollDelegate.scrollTop(false);
	 };
	 
	//Inicializando tiendas
	$scope.storeSelected = function (store){
			console.log(storeSelected);
	};
	$scope.stores = [];
	$http.get(Global.url + "/api/stores").success(function(data){
		for (var i in data){
			$scope.stores.push(data[i]);
		}

	});
	
	//Inicializando Categorías
	$scope.categories = [];
	$http.get(Global.url + "/api/categories").success(function(data){
		for (var i in data["maps"]){
			var _aux = {id:i,name:i18.translate(data["maps"][i]["name"]),key:data["maps"][i]["name"]};
			$scope.categories.push(_aux);
		}
	});

    
    
    

    
    $scope.form = {
    		minPrice : "" ,
    		maxPrice : "" ,
    		descr    : "" ,
    		discounts : [5,10,20,30,50,70,90]
    };

    $scope.language = "es";
    if (navigator.globalization){
    	navigator.globalization.getPreferredLanguage(
    	    function (language) {
    	    	 $scope.language = language.value.substr(0,2).toLowerCase();
    	    	 },
    	    function () {$scope.language = "es";}
    	);
    };
    
    $scope.items = [];
    
    $http.get(Global.url + "/api/products").success(function(data){
		$scope.items = data;
		$ionicLoading.hide();

	});

    $scope.params = "";
    $scope.order = "name";
    
    $scope.sort  = function (order){
    	    $scope.order = order;
    		$scope.refresh();
    };
    
    $scope.refresh = function (){
    	$scope.offset = 0;
    	$scope.finished = false;
    	$scope.showLoading();
    	var _stores = $scope.getMultiSelect("multi-stores",$scope.stores),
    	    _categories = $scope.getMultiSelect("multi-categories",$scope.categories);
    	
    	_stores = _stores.length > 0 ? _stores.join(","):null;
    	_categories = _categories.length > 0 ? _categories.join(","):null;
    	
    	var _params = "";

    	if (_stores){
    		_params += "id_store="+_stores+"&";
    	}
 
    	if (_categories){
    		_params += "id_category="+_categories+"&";
    	}
    	
    	_id = $("select[name='slc-discount'] option:selected").val();
    	if (_id && _id != "0"){
    		_params += "discount="+_id+"&";
    	}

    	if ($scope.form.minPrice)
    		_params += "price_min="+$scope.form.minPrice+"&";
    	
    	if ($scope.form.maxPrice)
    		_params += "price_max="+$scope.form.maxPrice+"&";
    	
    	if ($scope.form.descr)
    		_params += "name="+$scope.form.descr+"&";
    	
    	_params += "order="+$scope.order+"&";
    	
    	$scope.params = _params;
    	$location.path("/app/items");
    	$scope.scrollTop();
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
	  //    $scope.loadMore();
	});
	
	//Favorites
	$scope.favoriteItems = Favorites.get();
	$scope.favorite = function (item){
		if (Favorites.add(item)){
			$scope.favoriteItems.push(item);
		};
	};
	
	$scope.favoriteExists = function (item){
		if (!item){
			return false;
		}
		return Favorites.exists(item);
	};
	
	$scope.deleteFavorite = function (item) {
		Favorites.deleteFavorite(item);
		for (var i in $scope.favoriteItems){
			 if ($scope.favoriteItems[i].id == item.id){
				 $scope.favoriteItems.splice(i,1);
			 }
				 
		}
	};
	
	$scope.getMultiSelect = function (type, aMatch){
		//Workaround
		var aSelected = [];
		$("."+type+ " .checkBoxContainer > div").each(function(){
			if ($(this).find("span.tickMark").length > 0){
				aSelected.push($(this).find("div.acol span.ng-binding").text());
			}
		});
		
		if (aMatch){
			var auxSelected = [];
			for (var i in aSelected){
				for (var j in aMatch){
					if (aSelected[i].trim() == aMatch[j]["name"].trim()){
						auxSelected.push(aMatch[j]["id"]);
						break;
					}
				}
			}
			aSelected = auxSelected;
		}
		return aSelected;
	};

})

.controller('ItemsCtrl', function($scope , $http , Global) {
	
})

.controller('FavCtrl', function($scope , $http , Global, $ionicActionSheet, Favorites) {
	
	
	$scope.showSheet = function (item) {
		// Show the action sheet
		 
		 $ionicActionSheet.show({
		     buttons: [
		     ],
		     destructiveText: 'Borrar',
		     destructiveButtonClicked : function () {
		    	 $scope.deleteFavorite(item);
		    	 return true;
		     } ,
		     titleText: 'Administrar favorito',
		     cancelText: 'Cancelar',
		     cancel: function() {
		          // add cancel code..
		        },
		     buttonClicked: function(index) {
		       
		       return true;
		     }
		 });

	};
	 
	
  
})

.controller('ItemCtrl', function($scope, $stateParams, $location,$ionicModal,Favorites,$window,$templateCache) {
	if ($scope.items.length == 0){
		$window.location.href = "?";
		//$location.path("/app/items");
		return;
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
	
	if (!selectedId){
		var favoriteItems = Favorites.get();
		for (var i in favoriteItems){
			if(favoriteItems[i].id == id){
				$scope.selectedItem = favoriteItems[i];
			}
		}
	}else{
		$scope.selectedItem = $scope.items[selectedId];
	}

	$scope.openUrl = function ()
	{
		window.open($scope.selectedItem.url,'_system');
	};
	
});
