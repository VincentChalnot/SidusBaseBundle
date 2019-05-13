<?php
/*
 * This file is part of the Sidus/BaseBundle package.
 *
 * Copyright (c) 2015-2019 Vincent Chalnot
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sidus\BaseBundle\Serializer\Annotation;

use Sidus\BaseBundle\Serializer\Normalizer\NestedPropertyDenormalizer;
use Doctrine\Common\Annotations\Annotation;

/**
 * Used to denormalize embed data from a parent class
 *
 * @see NestedPropertyDenormalizer
 *
 * @Annotation()
 *
 * @Target("PROPERTY")
 */
class NestedClass
{
    /** @var string */
    public $targetClass;

    /** @var bool */
    public $multiple = false;

    /** @var bool */
    public $nullable = false;

    /**
     * @param array $config
     *
     * @throws \UnexpectedValueException
     */
    public function __construct($config)
    {
        if (array_key_exists('value', $config)) {
            $this->targetClass = (string) $config['value'];
        }
        if (array_key_exists('targetClass', $config)) {
            $this->targetClass = (string) $config['targetClass'];
        }
        if (array_key_exists('multiple', $config)) {
            $this->multiple = (bool) $config['multiple'];
        }
        if (array_key_exists('nullable', $config)) {
            $this->nullable = (bool) $config['nullable'];
        }
        if (!class_exists($this->targetClass)) {
            throw new \UnexpectedValueException("Missing class {$this->targetClass}");
        }
    }
}
