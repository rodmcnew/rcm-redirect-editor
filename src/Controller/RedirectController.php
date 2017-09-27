<?php

namespace RcmRedirectEditor\Controller;

use Interop\Container\ContainerInterface;
use RcmRedirectEditor\Api\Acl\IsAllowed;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Model\ViewModel;

/**
 * RedirectController
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Redirect\Controller
 * @author    author Brian Janish <bjanish@relivinc.com>
 * @copyright ${YEAR} Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class RedirectController extends AbstractActionController
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface $serviceLocator
     */
    public function __construct(
        $serviceLocator = null
    ) {
        if ($serviceLocator) {
            $this->serviceLocator = $serviceLocator;
        }
    }

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
     * @return \Zend\Stdlib\ResponseInterface|ViewModel|Response
     */
    public function indexAction()
    {
        if (!$this->isAllowed(['method' => __METHOD__])) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);

            return $this->getResponse();
        }

        return new ViewModel();
    }
}
