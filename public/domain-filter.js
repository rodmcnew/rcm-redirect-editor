/**
 * Filter that will query all existing domains
 */
angular.module('rcmRedirectEditor').filter('domainFilter', function () {
    return function (input, query) {
        if (!query) {
            return input
        }
        var result = {};
        var regex = new RegExp(query, 'i');
        angular.forEach(
            input, function (site) {
                if (site.domain && site.domain.search(regex) !== -1) {
                    result[site.siteId] = site;
                }
            }
        );

        return result;
    };
});


