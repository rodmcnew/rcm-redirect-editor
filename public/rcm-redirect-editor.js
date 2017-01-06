angular.module('rcmRedirectEditor')
    .directive(
        'rcmRedirectAdminDirective',
        [
            'rcmApiLibService',
            'rcmApiLibMessageService',
            'rcmLoading',
            function (rcmApiLibService, rcmApiLibMessageService, rcmLoading) {
                function link($scope, $modalInstance) {

                    /**
                     * apiUrl
                     *
                     * @type {{redirect: string,
                 *         defaultRedirects: string,
                 *         domains: string,
                 *         updateExistingRedirect: string}}
                     */
                    var apiUrl = {
                        redirect: '/api/redirect',
                        defaultRedirects: '/api/redirect?default-redirects=true',
                        domains: '/api/admin/manage-sites?page_size=-1',
                        updateExistingRedirect: '/api/redirect/{id}'
                    };

                    // Save as a default redirect
                    $scope.makeDefault = function () {
                        $scope.default = false;
                        $scope.chosenRedirect = '';
                        $scope.redirect.siteId = null;
                        $scope.domainQuery = '';
                    };

                    // Toggle between entire list and default list
                    $scope.defaultList = function () {
                        getDefaultRedirects()
                    };

                    // Show complete list of redirects
                    $scope.completeList = function () {
                        getRedirects();
                    };

                    $scope.closeModal = function () {
                        $modalInstance.close();
                    };
                    // Initialize modal
                    /**
                     * modalTitle
                     * @type {string}
                     */
                    $scope.modalTitle = "";

                    /**
                     * showModal
                     * @type {boolean}
                     */
                    $scope.showModal = false;


                    $scope.toggleModal = function () {
                        $scope.showModal = !$scope.showModal;
                    };

                    // Setup modal for adding redirect
                    $scope.setupAddRedirectModal = function () {
                        $scope.modalTitle = "Add Redirect";
                        $scope.domainQuery = '';
                        $scope.chosenRedirect = '';
                        $scope.redirect = {
                            siteId: null,
                            dirty: false,
                            domain: $scope.domainQuery
                        };
                        //$scope.chosenRedirect = $scope.redirect.domain;
                        $scope.showSaveLink = false;
                        $scope.showAddButton = true;
                        $scope.redirectMessage = '';
                    };

                    /**
                     * delete Redirect
                     *
                     * @param redirect
                     * @param success
                     * @param error
                     */
                    $scope.deleteRedirect = function (redirect, success, error) {

                        // Call API to delete redirect
                        rcmApiLibService.del(
                            {
                                url: apiUrl.updateExistingRedirect,
                                urlParams: {id: redirect.redirectId},
                                data: redirect.redirectId,
                                loading: function (loading) {
                                    var loadingInt = Number(!loading);
                                    rcmLoading.setLoading(
                                        'rcmRedirectDelete',
                                        loadingInt
                                    );
                                },
                                success: function () {
                                    var index = $scope.redirectList.indexOf(redirect);

                                    if (index > -1) {
                                        $scope.redirectList.splice(index, 1);
                                    }
                                },
                                error: function (response) {
                                    rcmApiLibMessageService.addPrimaryMessage(
                                        response.messages,
                                        'rcmRedirectAdminDirective'
                                    );
                                }
                            }
                        );
                    };

                    /**
                     * showList
                     *
                     * @type {boolean}
                     */
                    $scope.showList = true;

                    //set siteId of domain that was selected from the list
                    $scope.saveSiteId = function (site) {
                        $scope.domainQuery = site.domainName;
                        $scope.chosenRedirect = site.domainName;
                        $scope.redirect.domain = site.domainName;
                        $scope.redirect['siteId'] = site.siteId;
                    };

                    /**
                     * redirect
                     *
                     * @type {{}}
                     */
                    $scope.redirect = {};


                    /**
                     * setupUpdateRedirectModal
                     *
                     * Setup modal for updating redirect
                     *
                     * @param redirect
                     */
                    $scope.setupUpdateRedirectModal = function (redirect) {
                        $scope.domainQuery = redirect.domain;
                        $scope.redirect = redirect;
                        $scope.chosenRedirect = redirect.domain;
                        $scope.showAddButton = false;
                        $scope.showSaveLink = true;
                        $scope.showList = true;
                        $scope.modalTitle = "Update Redirect";
                        $scope.redirectMessage = '';
                    };

                    /**
                     * updateRedirect
                     *
                     * @param redirectId
                     * @param redirect
                     */
                    $scope.updateRedirect = function (redirectId, redirect) {

                        var sites = $scope.sites;
                        var queryDom = $scope.domainQuery;
                        $scope.redirectMessage = '';

                        /**
                         * Check to see if the domain entered is one of our sites.
                         */

                            //Run through sites object to find siteId
                        angular.forEach(sites, function (value) {
                                if (queryDom === value.domainName) {
                                    redirect.siteId = value.siteId;
                                    $scope.redirect.domain = value.domainName;
                                }
                            }
                        );

                        $scope.redirect = redirect;
                        redirect.dirty = false;

                        //Call API to update redirect
                        rcmApiLibService.put(
                            {
                                url: apiUrl.updateExistingRedirect,
                                urlParams: {id: redirect.redirectId},
                                data: redirect,
                                loading: function (loading) {
                                    var loadingInt = Number(!loading);
                                    rcmLoading.setLoading(
                                        'rcmRedirectUpdate',
                                        loadingInt
                                    );
                                },
                                success: function () {
                                    $scope.redirect.domain = redirect.domain;
                                    if ($scope.redirect.siteId === null) {
                                        $scope.redirect.domain = '';
                                    }
                                    $scope.redirectMessage = 'Saved';

                                },
                                error: function (response) {
                                    rcmApiLibMessageService.addPrimaryMessage(
                                        response.messages,
                                        'rcmRedirectAdminDirective'
                                    );
                                    $scope.redirect.dirty = null;
                                }
                            }
                        );
                    };

                    /**
                     *  addRedirect
                     *
                     * @param redirect
                     */
                    $scope.addRedirect = function (redirect) {

                        $scope.chosenRedirect = '';

                        // Call API to add new redirect entry
                        rcmApiLibService.post(
                            {
                                url: apiUrl.redirect,
                                data: redirect,
                                loading: function (loading) {
                                    var loadingInt = Number(!loading);
                                    rcmLoading.setLoading(
                                        'rcmRedirectAdd',
                                        loadingInt
                                    );
                                },
                                success: function (response) {
                                    $scope.redirectList.unshift(response.data);
                                    $scope.redirect = {
                                        siteId: null,
                                        domain: $scope.domainQuery,
                                        dirty: false
                                    };
                                    $scope.redirect.siteId = null;
                                    $scope.domainQuery = '';
                                    $scope.showList = true;
                                    $scope.redirectMessage = 'Saved';
                                },
                                error: function (response) {
                                    rcmApiLibMessageService.addPrimaryMessage(
                                        response.messages,
                                        'rcmRedirectAdminDirective'
                                    );

                                }
                            }
                        );
                    };

                    $scope.saveRedirect = function (redirect) {
                        redirect.dirty = true;
                    };

                    /**
                     * getRedirects
                     *
                     * Get ALL redirects
                     */
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
                                },
                                error: function (response) {
                                    rcmApiLibMessageService.addPrimaryMessage(
                                        response.messages,
                                        'rcmRedirectAdminDirective'
                                    );
                                }
                            }
                        );
                    }

                    /**
                     * getDefaultRedirects
                     *
                     * returns all redirects with siteId of null
                     */
                    function getDefaultRedirects() {
                        rcmApiLibService.get(
                            {
                                url: apiUrl.defaultRedirects,
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
                                },
                                error: function (response) {
                                    rcmApiLibMessageService.addPrimaryMessage(
                                        response.messages,
                                        'rcmRedirectAdminDirective'
                                    );
                                }
                            }
                        );
                    }

                    /**
                     * getDomains
                     *
                     * Get list of all domains
                     */
                    function getDomains() {
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

                                    var sites = data.data;
                                    angular.forEach(sites, function(value) {
                                        $scope.sites = value;
                                    });

                                    $scope.letter = '';
                                },
                                error: function (response) {
                                    rcmApiLibMessageService.addPrimaryMessage(
                                        response.messages,
                                        'rcmRedirectAdminDirective'
                                    );
                                }
                            }
                        );
                    }

                    var init = function () {
                        $scope.redirectError = '';
                        $scope.sites = [];
                        $scope.redirect = {};
                        $scope.redirectList = [];
                        $scope.showAddButton = false;
                        $scope.showSaveLink = false;
                        $scope.redirectMessage = '';
                        
                        getRedirects();

                        getDomains();

                    };

                    init();

                }

                return {
                    link: link,
                    templateUrl: "/modules/rcm-redirect-editor/rcm-redirect-editor.html"
                }

            }
        ]
    );



