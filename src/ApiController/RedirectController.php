<?php

namespace RcmRedirectEditor\ApiController;

use Rcm\Entity\Redirect;
use Rcm\Entity\Site;
use Rcm\Exception\RedirectException;
use Reliv\RcmApiLib\Controller\AbstractRestfulJsonController;
use Zend\Http\Response;
use Zend\View\Model\JsonModel;
use Doctrine\ORM\EntityRepository;

/**
 * RedirectController
 *
 * LongDescHere
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
//    /**
//     * getUserService
//     *
//     * @return \RcmUser\Service\RcmUserService
//     */
//    protected function getUserService()
//    {
//        return $this->getServiceLocator()->get('RcmUser\Service\RcmUserService');
//    }
//
    /**
     * getEntityManager
     *
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager()
    {
        return $this->serviceLocator->get('Doctrine\ORM\EntityManager');
    }

    public function delete($id)
    {
        //
        $em = $this->getEntityManager();

        $redirectRepo = $em->getRepository(
            '\Rcm\Entity\Redirect'
        );

        $redirectToUpdate = $redirectRepo->findOneBy(
            ['redirectId' => $id]
        );

        if (!$redirectToUpdate) {
            return $this->getApiResponse(
                $redirectToUpdate,
                404
            );
        }

        $em->remove($redirectToUpdate);
        $em->flush();

//        $this->getList();

        return $this->getApiResponse(
            $redirectToUpdate
        );
    }

    /**
     * update
     *
     * @param mixed $id
     * @param mixed $data
     * @return \Reliv\RcmApiLib\Http\ApiResponse
     */
    public function update($id, $data)
    {
        $em = $this->getEntityManager();

        $redirectRepo = $em->getRepository(
            '\Rcm\Entity\Redirect'
        );

        $redirectToUpdate = $redirectRepo->find($id);

        if (!$redirectToUpdate) {
            return $this->getApiResponse(
                $redirectToUpdate,
                404
            );
        }

        $redirectToUpdate->setRedirectUrl($data['redirectUrl']);
        $redirectToUpdate->setRequestUrl($data['requestUrl']);
        $redirectToUpdate->setSiteId($data['siteId']);

        $em->flush();

        return $this->getApiResponse(
            $redirectToUpdate
        );

    }

    /**
     * create
     *
     * @param mixed $data
     * @return \Reliv\RcmApiLib\Http\ApiResponse
     */
    public function create($data)
    {
        $newRedirect = new Redirect();

        // @TODO filter data
        $newRedirect->populate($data);
//        var_dump($newRedirect->getSiteId()); die;
        $entityManager = $this->getEntityManager();

        try {
            $redirectRepo = $entityManager->getRepository(
                '\Rcm\Entity\Redirect'
            );
            $redirectRepo->save($newRedirect);

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

//        if (!$this->getUserService()->isAllowed('conference', 'manage', 'Rcm\Acl\ResourceProvider')) {
//            return new UnauthorizedResponse($this->getRequest());
//        }

        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getEntityManager();

        $siteId = $this->params()->fromQuery('siteId');

        if ($siteId === 'global') {
            $site = $em->getRepository('Rcm\Entity\Site')->find($siteId);

            $redirectList = $em->getRepository('Rcm\Entity\Redirect')->findBy(
                ["site" => $site]
            );

            return $this->getApiResponse($redirectList);
        }

//        $site = $em->getRepository('Rcm\Entity\Site')->find($siteId);
        $redirectList = $em->getRepository('Rcm\Entity\Redirect')->findAll();


        return $this->getApiResponse($redirectList);

    }

}
