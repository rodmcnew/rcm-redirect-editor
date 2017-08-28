<?php

namespace RcmRedirectEditor\Api\User;

use Psr\Container\ContainerInterface;
use RcmUser\Service\RcmUserService;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetCurrentUserIdRcmUserFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return GetCurrentUserIdRcmUser
     */
    public function __invoke($serviceContainer)
    {
        return new GetCurrentUserIdRcmUser(
            $serviceContainer->get(RcmUserService::class)
        );
    }
}
