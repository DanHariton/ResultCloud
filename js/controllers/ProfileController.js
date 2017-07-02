application.controller('ProfileController', ['$scope', 'UserService', 'TemplateSettingsService', function ($scope, UserService, TemplateSettingsService) {
    // Controller variables
    $scope.user = {};

    // Get current user
    UserService.current()
        .success(function (data, status, headers, config) {
            $scope.user = data;
            TemplateSettingsService.getByUser($scope.user)
                .success(function (data, status, headers, config) {
                    $scope.settings = data;
                    for (var i = $scope.settings.length - 1; i >= 0; i--) {
                        for (var j = $scope.settings[i].Items.length - 1; j >= 0; j--) {
                            if ($scope.settings[i].Items[i].Type == 5) {
                                $scope.settings[i].Items[i].Value = $scope.settings[i].Items[i].Value == "1" ? true : false; 
                            }
                        }
                    }
                    // $scope.$digest();
                });
        });


    // Save current user
    $scope.Save = function()	{
	    UserService.save($scope.user)
			.success(function (data, status, headers, config) {
	            // Show status
	            $scope.ShowStatus('Save user', data.IsValid, data.Errors);
	        });
	}
    $scope.SaveSettings = function()    {
        for (var i = $scope.settings.length - 1; i >= 0; i--) {
            for (var j = $scope.settings[i].Items.length - 1; j >= 0; j--) {
                if ($scope.settings[i].Items[i].Type == 5) {
                    $scope.settings[i].Items[i].Value = $scope.settings[i].Items[i].Value ? "1" : "0"; 
                }
            }
        }
        TemplateSettingsService.save($scope.settings)
            .success(function (data, status, headers, config) {
                // Show status
                $scope.ShowStatus('Save settings', data.IsValid, data.Errors);
            });
    }
    $scope.change = function () {
        console.log($scope.settings);
    };
}]);