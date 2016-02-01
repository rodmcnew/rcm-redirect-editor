angular.module('rcmRedirectEditor', [])
    .directive(
        'rcmRedirectAdminDirective',

        [
            'rcmApiLibService',
            'rcmApiLibMessageService',
            'rcmLoading',
            '$timeout',
            '$rootScope',
            function (rcmApiLibService, rcmApiLibMessageService, rcmLoading, $timeout, $rootScope) {
                function link($scope) {

                    var self = this;

                    var apiUrl = {
                        redirect: '/api/redirect',
                        globalRedirects: '/api/redirect?siteId=global',
                        domains: '/api/admin/manage-sites?page_size=-1',
                        updateExistingRedirect: '/api/redirect/{id}'
                    };

                    $scope.sites = [];

                    $scope.redirect = {};

                    $scope.redirectList = [];
                    $scope.addRedirect = false;

                    $scope.showAddButton = false;
                    $scope.showSaveButton = false;

                    $scope.saveGlobal = function () {
                        $scope.global = false;
                        $scope.domainQuery = '';
                        $scope.redirectDomain = '';
                        $scope.redirect.siteId = null;
                    };


                    $scope.globalList = function () {
                        getGlobalRedirects()
                    };
                    $scope.completeList = function() {
                        getRedirects();
                    };
                    $scope.modalTitle = "";
                    getRedirects();

                    $scope.showModal = false;
                    $scope.toggleModal = function () {
                        $scope.showModal = !$scope.showModal;
                    };


                    $scope.setupAddRedirectModal = function() {
                        $scope.modalTitle = "Add Redirect";
                        $scope.redirect = {};
                        $scope.domainQuery = '';
                        $scope.showSaveButton = false;
                        $scope.showAddButton = true;
                    };

                    $scope.deleteRedirect = function (redirect, success, error, loading) {
                        console.log('delete');

                        index = redirect.redirectId;

                        rcmApiLibService.del(
                            {
                                url: apiUrl.updateExistingRedirect,
                                urlParams: {id: redirect.redirectId},
                                data: redirect.redirectId,
                                loading: function (loading) {
                                    var loadingInt = Number(!loading);
                                    rcmLoading.setLoading(
                                        'rcmRedirectAdminDirective',
                                        loadingInt
                                    );
                                },
                                success: function () {
                                    var index = $scope.redirectList.indexOf(redirect);

                                    if (index > -1) {
                                        $scope.redirectList.splice(index, 1);
                                    }
                                },
                                error: error
                            }
                        );
                    };

                    $scope.showList = true;

                    $scope.saveSiteId = function (site) {
                        $scope.domainQuery = site.domain;
                        $scope.redirect['siteId'] = site.siteId;
                        if(!site.siteId) {

                        }
                        console.log('saveSiteId = ', $scope.redirect['siteId']);
                    };

                    $scope.redirect = {};

                    $scope.addRedirect = function (redirect, success, error, loading) {
                        console.log('save new redirect');
                        console.log($scope.domainQuery);
                        console.log('redirect.siteId = ', redirect.siteId);
                        rcmApiLibService.post(
                            {
                                url: apiUrl.redirect,
                                data: redirect,
                                loading: function (loading) {
                                    var loadingInt = Number(!loading);
                                    rcmLoading.setLoading(
                                        'rcmRedirectAdminDirective',
                                        loadingInt
                                    );
                                },
                                success: function () {
                                    console.log('returned = ',redirect.siteId);
                                    $scope.redirectList.push(redirect);
                                    $scope.redirect.domain = $scope.domainQuery;
                                    $scope.redirect = {};
                                    $scope.domainQuery = '';
                                    $scope.showList = true;
                                    $scope.checked = false;
                                    //$scope.redirect.domain = $scope.domainQuery;
                                    //$scope.redirectList.d
                                },
                                error: function () {
                                    console.log(redirect.siteId);
                                    console.log('siteId does not exist');
                                }
                            }
                        );
                    };

                    $scope.populateUpdateRedirectModal = function(redirect) {
                        console.log(redirect);
                        $scope.domainQuery = redirect.domain;
                        $scope.redirect = redirect;
                        $scope.showAddButton = false;
                        $scope.showSaveButton = true;
                        $scope.showList = true;
                        $scope.modalTitle = "Update Redirect";
                    };

                    $scope.updateRedirect = function (redirectId, redirect, success, error, loading) {
                        console.log('update');
                        //console.log('query = ', $scope.domainQuery);
                        sites = $scope.sites;
                        queryDom = $scope.domainQuery;

                        angular.forEach(sites, function(value) {
                                console.log('query = ', queryDom);
                                console.log('value.domain = ', value.domain);
                                if (queryDom === value.domain) {
                                    redirect.siteId = value.siteId;
                                    console.log('value.siteId = ', value.siteId);
                                     //return redirect.siteId;
                                    redirect.domain = value.domain;

                                }
                            }
                        );
                        //here
                        domain = redirect.domain;
                        console.log('siteId = ', redirect.siteId);
                        $scope.redirect = redirect;
                        redirect.dirty = false;
                        console.log('redirect = ', redirect);


                        rcmApiLibService.put(
                            {
                                url: apiUrl.updateExistingRedirect,
                                urlParams: {id: redirect.redirectId},
                                data: redirect,
                                loading: function (loading) {
                                    var loadingInt = Number(!loading);
                                    rcmLoading.setLoading(
                                        'rcmRedirectAdminDirective',
                                        loadingInt
                                    );
                                },
                                success: function () {
                                    //here
                                    $scope.redirect.domain = '';
                                },
                                error: function () {
                                    console.log('update error');
                                }
                            }
                        );
                    };

                    $scope.saveButton = function (redirect) {
                        redirect.dirty = true;
                    };

                    function getRedirects() {
                        rcmApiLibService.get(
                            {
                                url: apiUrl.redirect,
                                loading: function (loading) {
                                    var loadingInt = Number(!loading);
                                    rcmLoading.setLoading(
                                        'rcmRedirectAdminGetRedirects',
                                        loadingInt
                                    );
                                },
                                success: function (data) {
                                    $scope.redirectQuery = '';
                                    $scope.redirectList = data.data;
                                    //console.log($scope.redirectList);
                                }
                                //error: function (response) {
                                //    onApiError(response);
                                //    onError(response);
                                //}
                            }
                        );
                    }

                    function getGlobalRedirects() {
                        rcmApiLibService.get(
                            {
                                url: apiUrl.globalRedirects,
                                loading: function (loading) {
                                    var loadingInt = Number(!loading);
                                    rcmLoading.setLoading(
                                        'rcmRedirectAdminGetRedirects',
                                        loadingInt
                                    );
                                },
                                success: function (data) {
                                    $scope.redirectQuery = '';
                                    $scope.redirectList = data.data;
                                    //console.log($scope.redirectList);
                                }
                                //error: function (response) {
                                //    onApiError(response);
                                //    onError(response);
                                //}
                            }
                        );
                    }
                    rcmApiLibService.get(
                        {
                            url: apiUrl.domains,
                            loading: function (loading) {
                                var loadingInt = Number(!loading);
                                rcmLoading.setLoading(
                                    'rcmRedirectAdminGetSites',
                                    loadingInt
                                );
                            },
                            success: function (data) {
                                $scope.letter = '';
                                $scope.sites = data.data.items;
                                //console.log(data.data.items)
                            }
                            //error: function (response) {
                            //    onApiError(response);
                            //    onError(response);
                            //}
                        }
                    );
                }

                return {
                    link: link,
                    templateUrl: "/modules/rcm-redirect-editor/directive/rcm-redirect-editor.html"
                }


            }
        ]
    )

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
                input, function (redirectList) {
                    if (compareStr(redirectList.requestUrl, query) || compareStr(redirectList.redirectUrl, query) ||
                        compareStr(redirectList.domain, query)) {
                        result[redirectList.redirectId] = redirectList;
                    }
                }
            );

            return result;
        };
    })
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
                input, function (sites) {
                    if (compareStr(sites.domain, query)) {
                        result[sites.siteId] = sites;
                    }
                }
            );

            return result;
        };
    });

if (typeof rcm != 'undefined') {
    // RCM is undefined in unit tests
    rcm.addAngularModule('rcmRedirectEditor');
}
