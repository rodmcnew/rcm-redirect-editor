<?php
/**
 * RedirectService.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   module\redirect\Service
 * @author    author Brian Janish <bjanish@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmRedirectEditor\Service;

use Doctrine\ORM\EntityManager;

class RedirectService
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(
        EntityManager $entityManager
    ) {
        $this->entityManager = $entityManager;

    }

    /**
     * getRedirectRepo
     *
     * @return array|object
     */
    public function getRedirectRepo()
    {
        return $this->entityManager->getRepository(
            'Rcm\Entity\Redirect'
        );
    }
}
