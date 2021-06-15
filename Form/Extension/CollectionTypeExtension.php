<?php
/*
 * This file is part of the Sidus/BaseBundle package.
 *
 * Copyright (c) 2015-2021 Vincent Chalnot
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sidus\BaseBundle\Form\Extension;

use InvalidArgumentException;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\DataMapper\PropertyPathMapper;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Fixes the use of the property accessor in collection types by injecting a specific path mapper.
 *
 * @see https://github.com/symfony/symfony/issues/29354
 *
 * @author Vincent Chalnot <vincent@sidus.fr>
 */
class CollectionTypeExtension extends AbstractTypeExtension
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @throws InvalidArgumentException
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Do not inject the form property accessor to ignore the $throwExceptionOnInvalidIndex settings
        $builder->setDataMapper($options['compound'] ? new PropertyPathMapper() : null);
    }

    /**
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType()
    {
        return CollectionType::class;
    }

    public static function getExtendedTypes(): iterable
    {
        return [CollectionType::class];
    }
}
