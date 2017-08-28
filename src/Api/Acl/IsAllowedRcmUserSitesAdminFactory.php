<?php

namespace RcmRedirectEditor\Api\Acl;

use Psr\Container\ContainerInterface;
use RcmUser\Service\RcmUserService;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class IsAllowedRcmUserSitesAdminFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return IsAllowedRcmUserSitesAdmin
     */
    public function __invoke($serviceContainer)
    {
        return new IsAllowedRcmUserSitesAdmin(
            $serviceContainer->get(RcmUserService::class)
        );
    }
}
