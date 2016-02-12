/**
 * Filter that will query all existing domains
 */
angular.module('rcmRedirectEditor')
.filter('domainFilter', function () {

    var compareStr = function (stra, strb) {
        stra = ("" + stra).toLowerCase();
        strb = ("" + strb).toLowerCase();

        return stra.indexOf(strb) !== -1;
    };

    return function (input, query) {
        if (!query) {
            return input
        }
        var result = {};
        angular.forEach(
            input, function (site) {
                if (compareStr(site.domain, query)) {
                    result[site.siteId] = site;
                }
            }
        );

        return result;
    };
});


