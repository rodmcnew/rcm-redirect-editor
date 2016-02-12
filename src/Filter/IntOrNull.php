<?php
/**
 * IntOrNull.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmRedirectEditor\Filter
 * @author    author Brian Janish <bjanish@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmRedirectEditor\Filter;

use Zend\Filter\ToInt;

class IntOrNull extends ToInt
{
    /**
     * Defined by Zend\Filter\FilterInterface
     *
     * Returns (int) $value
     *
     * If the value provided is non-scalar, the value will remain unfiltered
     *
     * @param  string $value
     * @return int|mixed
     */
    public function filter($value)
    {
        if ($value === null) {
            return $value;
        }
        return parent::filter($value);
    }
}
