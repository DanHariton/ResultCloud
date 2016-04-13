// analyzeOverview/frontend/analyzeOverview.js
application.directive('analyzeOverview', function ($compile) {
    return {
        restrict: 'E',
        templateUrl: 'visualization/components/analyzeOverview/frontend/template.html',
        controller: function ($scope, $stateParams, ViewService, $rootScope) {
            // Get data for current view
            ViewService.visualize({
                Source: {
                        Submission: $stateParams.submissionId
                },
                Identifier: 'analyze-overview'
			})
			.success(function (data, status, headers, config) {
				$scope.data = data.Data;
                // // $scope.$digest();
                // angular.forEach(data.Data, function (analyzer, key) {
                //     var data2 = $rootScope.$new();
                //     data2.data2 = $scope.data[key];
                //     console.log(data2);
                //     var el = $compile('<' + key + '/>')(data2);
                //     console.log($("#"+key));
                //     // And append to html
                //     $("#"+key).append(el);
                // });
			});

            $scope.buildAnalyzerView = function (key) {

                // console.log($(key));
                if (!$(key).length) {
                    var data2 = $rootScope.$new();
                    data2.data = $scope.data[key];
                    var el = $compile('<' + key + '/>')(data2);
                    // console.log($("#"+key));
                    // And append to html
                    $("#"+key).append(el);
                }
            }
        }
    }
});