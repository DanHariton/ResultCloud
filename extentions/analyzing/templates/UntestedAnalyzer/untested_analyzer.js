// analyzeOverview/frontend/analyzeOverview.js
application.directive('untestedanalyzer', function () {
    return {
        restrict: 'E',
        templateUrl: 'extentions/analyzing/templates/UntestedAnalyzer/untested_analyzer.html',
        controller: function ($scope, $stateParams, ViewService, $rootScope) {
        }
    }
});