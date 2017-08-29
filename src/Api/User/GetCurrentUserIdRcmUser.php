<?php

namespace RcmRedirectEditor\Api\User;

use Psr\Http\Message\ServerRequestInterface;
use RcmUser\Service\RcmUserService;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class GetCurrentUserIdRcmUser implements GetCurrentUserId
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
     * @return int|string|null
     */
    public function __invoke(
        ServerRequestInterface $request,
        array $options = []
    ) {
        $user = $this->rcmUserService->getCurrentUser();

        if (empty($user)) {
            return null;
        }

        return $user->getId();
    }
}
