<?php

namespace RcmRedirectEditor\ApiController;

use Interop\Container\ContainerInterface;
use Rcm\Api\Repository\Redirect\CreateRedirect;
use Rcm\Api\Repository\Redirect\FindAllSiteRedirects;
use Rcm\Api\Repository\Redirect\FindGlobalRedirects;
use Rcm\Api\Repository\Redirect\FindRedirects;
use Rcm\Api\Repository\Redirect\FindSiteRedirects;
use Rcm\Api\Repository\Redirect\RemoveRedirect;
use Rcm\Api\Repository\Redirect\UpdateRedirect;
use Rcm\Exception\RedirectException;
use Rcm\Tracking\Exception\TrackingException;
use RcmRedirectEditor\Api\Acl\IsAllowed;
use RcmRedirectEditor\Api\User\GetCurrentUserId;
use RcmRedirectEditor\InputFilter\RedirectInputFilter;
use Reliv\RcmApiLib\Controller\AbstractRestfulJsonController;
use Zend\Diactoros\ServerRequestFactory;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Model\JsonModel;

/**
 * RedirectController
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Redirect\ApiController
 * @author    Brian Janish <bjanish@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class RedirectController extends AbstractRestfulJsonController
{
    /**
     * @param array $options
     *
     * @return bool
     */
    protected function isAllowed(array $options = [])
    {
        /** @var IsAllowed $isAllowed */
        $isAllowed = $this->serviceLocator->get(IsAllowed::class);

        return $isAllowed->__invoke(
            ServerRequestFactory::fromGlobals(),
            $options
        );
    }

    /**
     * @return string
     * @throws TrackingException
     */
    protected function getCurrentUserId()
    {
        /** @var GetCurrentUserId $service */
        $service = $this->serviceLocator->get(GetCurrentUserId::class);;

        $userId = $service->__invoke(
            ServerRequestFactory::fromGlobals()
        );

        if (empty($userId)) {
            throw new TrackingException('A valid user is required in ' . self::class);
        }

        return (string)$userId;
    }

    /**
     * delete
     *
     * @param mixed $id
     *
     * @return \Reliv\RcmApiLib\Http\ApiResponse
     */
    public function delete($id)
    {
        if (!$this->isAllowed(['method' => __METHOD__])) {
            return $this->getApiResponse(
                null,
                401
            );
        }

        $id = (int)$id;

        /** @var RemoveRedirect $removeRedirect */
        $removeRedirect = $this->serviceLocator->get(RemoveRedirect::class);
        $removed = $removeRedirect->__invoke(
            $id,
            $this->getCurrentUserId(),
            'Remove redirect in ' . get_class($this)
        );

        if (!$removed) {
            return $this->getApiResponse(
                null,
                404
            );
        }

        return $this->getApiResponse(null);
    }

    /**
     * update
     *
     * @param mixed $id
     * @param mixed $data
     *
     * @return \Reliv\RcmApiLib\Http\ApiResponse
     */
    public function update($id, $data)
    {
        if (!$this->isAllowed(['method' => __METHOD__])) {
            return $this->getApiResponse(
                null,
                401
            );
        }

        $id = (int)$id;

        $inputFilter = new RedirectInputFilter();

        $inputFilter->setData($data);

        if (!$inputFilter->isValid()) {
            return $this->getApiResponse(
                null,
                400,
                $inputFilter
            );
        }

        $data = $inputFilter->getValues();

        /** @var UpdateRedirect $updateRedirect */
        $updateRedirect = $this->serviceLocator->get(UpdateRedirect::class);

        $redirectToUpdate = $updateRedirect->__invoke(
            $id,
            $data,
            $this->getCurrentUserId(),
            'Update redirect in ' . get_class($this)
        );

        if (empty($redirectToUpdate)) {
            return $this->getApiResponse(
                null,
                404
            );
        }

        return $this->getApiResponse(
            $redirectToUpdate
        );
    }

    /**
     * create
     *
     * @param mixed $data
     *
     * @return \Reliv\RcmApiLib\Http\ApiResponse
     */
    public function create($data)
    {
        if (!$this->isAllowed(['method' => __METHOD__])) {
            return $this->getApiResponse(
                null,
                401
            );
        }

        $inputFilter = new RedirectInputFilter();

        $inputFilter->setData($data);

        if (!$inputFilter->isValid()) {
            return $this->getApiResponse(
                null,
                400,
                $inputFilter
            );
        }

        $data = $inputFilter->getValues();

        /** @var CreateRedirect $createRedirect */
        $createRedirect = $this->serviceLocator->get(CreateRedirect::class);

        try {
            $newRedirect = $createRedirect->__invoke(
                $data,
                $this->getCurrentUserId(),
                'New redirect in ' . self::class
            );

        } catch (RedirectException $e) {
            return $this->getApiResponse(
                null,
                400,
                $e
            );
        }

        return $this->getApiResponse(
            $newRedirect
        );
    }

    /**
     * getList
     *
     * @return mixed|\Zend\Stdlib\ResponseInterface|JsonModel
     */
    public function getList()
    {
        /* ACL */
        if (!$this->isAllowed(['method' => __METHOD__])) {
            return $this->getApiResponse(
                null,
                401
            );
        }
        /* ***** filter by default redirects */
        $default = $this->params()->fromQuery('default-redirects');

        if ($default !== null) {
            $default = (bool)$default;
        }

        /* get list of default redirects */
        if ($default === true) {
            /** @var FindGlobalRedirects $findGlobalRedirects */
            $findGlobalRedirects = $this->serviceLocator->get(FindGlobalRedirects::class);

            $redirectList = $findGlobalRedirects->__invoke();

            return $this->getApiResponse($redirectList);
        }

        /* get list of all redirects that are NOT default redirects */
        if ($default === false) {
            /** @var FindAllSiteRedirects $findAllSiteRedirects */
            $findAllSiteRedirects = $this->serviceLocator->get(FindAllSiteRedirects::class);

            $redirectList = $findAllSiteRedirects->__invoke();

            return $this->getApiResponse($redirectList);
        }

        /* filter by siteId */
        $siteId = $this->params()->fromQuery('siteId');

        if ($siteId !== null) {
            $siteId = (int)$siteId;

            /** @var FindSiteRedirects $findSiteRedirects */
            $findSiteRedirects = $this->serviceLocator->get(FindSiteRedirects::class);

            $redirectList = $findSiteRedirects->__invoke($siteId);

            return $this->getApiResponse($redirectList);
        }

        /* all sites */
        /** @var FindRedirects $findRedirects */
        $findRedirects = $this->serviceLocator->get(FindRedirects::class);

        $redirectList = $findRedirects->__invoke();

        return $this->getApiResponse(
            $redirectList
        );
    }
}
