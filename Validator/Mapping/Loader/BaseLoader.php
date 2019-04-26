<?php
/*
 * This file is part of the Sidus/BaseBundle package.
 *
 * Copyright (c) 2015-2019 Vincent Chalnot
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sidus\BaseBundle\Validator\Mapping\Loader;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\MappingException;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Mapping\Loader\YamlFileLoader;

/**
 * Custom loader to manually call constraints on values based on their attributes
 *
 * @author Vincent Chalnot <vincent@sidus.fr>
 */
class BaseLoader extends YamlFileLoader
{
    /**
     * Overriding file loader constructor
     * There is no need for a file
     */
    public function __construct()
    {
    }

    /**
     * Loads validation metadata into a {@link ClassMetadata} instance.
     *
     * @param ClassMetadata $metadata The metadata to load
     *
     * @return bool Whether the loader succeeded
     */
    public function loadClassMetadata(ClassMetadata $metadata)
    {
        return false; // throw an exception ?
    }

    /**
     * @param array $constraints
     *
     * @throws MappingException
     *
     * @return Constraint[]
     */
    public function loadCustomConstraints(array $constraints)
    {
        return $this->parseNodes($constraints);
    }
}
