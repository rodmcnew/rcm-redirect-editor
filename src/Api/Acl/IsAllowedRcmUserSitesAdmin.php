<?php

namespace RcmRedirectEditor\Api\Acl;

use Psr\Http\Message\ServerRequestInterface;
use Rcm\Acl\ResourceName;
use RcmUser\Service\RcmUserService;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsAllowedRcmUserSitesAdmin implements IsAllowed
{
    /**
     * @var RcmUserService
     */
    protected $rcmUserService;

    /**
     * @param RcmUserService $rcmUserService
     */
    public function __construct(
        RcmUserService $rcmUserService
    ) {
        $this->rcmUserService = $rcmUserService;
    }

    /**
     * @param ServerRequestInterface $request
     * @param array                  $options
     *
     * @return bool
     */
    public function __invoke(
        ServerRequestInterface $request,
        array $options = []
    ): bool
    {
        return $this->rcmUserService->isAllowed(
            ResourceName::RESOURCE_SITES,
            'admin'
        );
    }
}
