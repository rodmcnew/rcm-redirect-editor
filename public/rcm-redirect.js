/**
 * Created by bjanish on 2/5/16.
 */
/**
 * rcmRedirectEditor module
 */
angular.module('rcmRedirectEditor', []);

if (typeof rcm != 'undefined') {
    // RCM is undefined in unit tests
    rcm.addAngularModule('rcmRedirectEditor');
}
