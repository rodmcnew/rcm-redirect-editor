<?php

namespace RcmRedirectEditor\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Http\Response;

//use Reliv\Redirect\Service\RedirectService;

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

        if (!$this->rcmIsAllowed(
            'sites',
            'admin'
        )
        ) {
            $this->getResponse()->setStatusCode(Response::STATUS_CODE_401);
            return $this->getResponse();
        }
        return new ViewModel();

    }
}
