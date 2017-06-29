<?php

namespace RcmRedirectEditor\InputFilter;

use Zend\InputFilter\InputFilter;

/**
 * RedirectInputFilter.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   moduleNameHere
 * @author    author Brian Janish <bjanish@relivinc.com>
 * @copyright 2016 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */
class RedirectInputFilter extends InputFilter
{
    /**
     * @var array
     */
    protected $filterConfig
        = [
            'requestUrl' => [
                'name' => 'requestUrl',
                'required' => true,
                'filters' => [
                    ['name' => \Zend\Filter\StripTags::class],
                    ['name' => \Zend\Filter\StringTrim::class]
                ],
                'validators' => [
                    ['name' => \Zend\Validator\NotEmpty::class],
                    ['name' => \Zend\Validator\Uri::class],
                ],
            ],
            'redirectUrl' => [
                'name' => 'redirectUrl',
                'required' => true,
                'filters' => [
                    ['name' => \Zend\Filter\StripTags::class],
                    ['name' => \Zend\Filter\StringTrim::class]
                ],
                'validators' => [
                    ['name' => \Zend\Validator\NotEmpty::class],
                    ['name' => \Zend\Validator\Uri::class],
                ],
            ],
            'siteId' => [
                'name' => 'siteId',
                'required' => true,
                'allow_empty' => true,
                'continue_if_empty' => true,
                'filters' => [
                    ['name' => \RcmRedirectEditor\Filter\IntOrNull::class]
                ],
            ],
        ];

    /**
     * __construct
     */
    public function __construct()
    {
        $this->build();
    }

    /**
     * build input filter from config
     *
     * @return void
     */
    protected function build()
    {
        $factory = $this->getFactory();

        foreach ($this->filterConfig as $field => $config) {
            $this->add(
                $factory->createInput(
                    $config
                )
            );
        }
    }
}
