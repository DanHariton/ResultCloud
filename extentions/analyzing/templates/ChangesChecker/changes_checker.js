// analyzeOverview/frontend/analyzeOverview.js
application.directive('changeschecker', function () {
    return {
        restrict: 'E',
        templateUrl: 'extentions/analyzing/templates/ChangesChecker/changes_checker.html',
        controller: function ($scope, $stateParams, ViewService, $rootScope) {
        }
    }
});