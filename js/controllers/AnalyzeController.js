/**
    AnalyzeController.js
 */
application.controller('AnalyzeController', ['$scope', '$stateParams', 'PathService', function ($scope, $stateParams, PathService) {
    $scope.path = {};

    PathService.path({
        Type: "SUBMISSION",
        EntityId: $stateParams.submissionId
    })
    .success(function (data, status, headers, config) {
        $scope.path = data;
    });
}]);