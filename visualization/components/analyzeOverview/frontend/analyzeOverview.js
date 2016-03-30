// analyzeOverview/frontend/analyzeOverview.js
application.directive('analyzeOverview', function () {
    return {
        restrict: 'E',
        templateUrl: 'visualization/components/analyzeOverview/frontend/template.html',
        controller: function ($scope, $stateParams, ViewService) {
            // Get data for current view
            ViewService.visualize({
                Source: {
                        Submission: $stateParams.submissionId
                },
                Identifier: 'analyze-overview'
			})
			.success(function (data, status, headers, config) {
				$scope.data = data.Data;
			});
        }
    }
});