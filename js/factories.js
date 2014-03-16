// User service
application.factory('UserService', function($http)	{
	return	{
		query: function()	{
			return $http({
				method: 'GET',
				url: 'api/UserController.class.php?method=QUERY'
			})
		},
		get: function(user)	{
			return $http({
				method: 'POST',
				url: 'api/UserController.class.php?method=GET',
				data: user
			})
		},
		save: function(user)	{
			return $http({
				method: 'POST',
				url: 'api/UserController.class.php?method=SAVE',
				data: user

			})
		}
	}
});

// Authentization service
application.factory('AuthentizationService', function($http)	{
	return	{
		authorize: function(credentials)	{
			return $http({
				method: 'POST',
				url: 'api/AuthentizationController.class.php?method=AUTHORIZE',
				data: credentials
			})
		}
	}
});