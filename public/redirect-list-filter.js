/**
 * Created by bjanish on 2/5/16.
 */
/**
 *  Filter that will query all fields.
 *  - requestUrl
 *  - redirectUrl
 *  - domain
 */
angular.module('rcmRedirectEditor')
.filter('redirectListFilter', function () {

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
            input, function (redirect) {
                if (compareStr(redirect.requestUrl, query) || compareStr(redirect.redirectUrl, query) ||
                    compareStr(redirect.domain, query)) {
                    result[redirect.redirectId] = redirect;
                }
            }
        );
        return result;
    };
});
