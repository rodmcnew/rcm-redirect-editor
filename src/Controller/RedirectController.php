<?php

namespace RcmRedirectEditor\Controller;

use Rcm\Acl\ResourceName;
use RcmUser\Service\RcmUserService;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
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
     * indexAction
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        /** @var RcmUserService $rcmUserService */
        $rcmUserService = $this->serviceLocator->get(RcmUserService::class);

        if (!$rcmUserService->isAllowed(
            ResourceName::RESOURCE_SITES,
            'admin'
        )
        ) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);

            return $this->getResponse();
        }

        return new ViewModel();
    }
}
