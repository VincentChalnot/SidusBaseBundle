<?php
/*
 * This file is part of the Sidus/BaseBundle package.
 *
 * Copyright (c) 2015-2019 Vincent Chalnot
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sidus\BaseBundle\Serializer\Normalizer;

use Sidus\BaseBundle\Serializer\Annotation\NestedClass;
use Sidus\BaseBundle\Serializer\Annotation\NestedPropertyDenormalizer as NestedPropertyDenormalizerAnnotation;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;

/**
 * During denormalization: reads the NestedClass annotation to hydrate properties properly
 *
 * Utilise la réflection pour hydrater les valeurs de la même manière que le PropertyNormalizer
 *
 * @see PropertyNormalizer For global denormalization logic
 * @see NestedClass        The annotation used for type hinting
 *
 * @property NormalizerInterface|DenormalizerInterface $serializer
 */
class NestedPropertyDenormalizer extends PropertyNormalizer
{
    /** @var AnnotationReader */
    protected $annotationReader;

    /**
     * @param AnnotationReader $annotationReader
     */
    public function setAnnotationReader(AnnotationReader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    /**
     * Only supports class with the NestedPropertyDenormalizer annotation
     *
     * {@inheritDoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        if (!\class_exists($type)) {
            return false;
        }
        $classAnnotation = $this->annotationReader->getClassAnnotation(
            new \ReflectionClass($type),
            NestedPropertyDenormalizerAnnotation::class
        );

        return $classAnnotation instanceof NestedPropertyDenormalizerAnnotation;
    }

    /**
     * Never supports normalization, only denormalization
     *
     * {@inheritDoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return false;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \UnexpectedValueException
     */
    protected function setAttributeValue($object, $attribute, $value, $format = null, array $context = [])
    {
        try {
            $reflectionProperty = new \ReflectionProperty(get_class($object), $attribute);
        } catch (\ReflectionException $reflectionException) {
            return;
        }

        if ($reflectionProperty->isStatic()) {
            return;
        }

        // Override visibility
        if (!$reflectionProperty->isPublic()) {
            $reflectionProperty->setAccessible(true);
        }

        $nestedClassAnnotation = $this->annotationReader->getPropertyAnnotation(
            $reflectionProperty,
            NestedClass::class
        );
        if ($nestedClassAnnotation instanceof NestedClass) {
            $value = $this->denormalizeNested($nestedClassAnnotation, $value);
        }

        $reflectionProperty->setValue($object, $value);
    }

    /**
     * @param NestedClass $nestedClassAnnotation
     * @param mixed       $value
     *
     * @throws \UnexpectedValueException
     *
     * @return mixed
     */
    protected function denormalizeNested(NestedClass $nestedClassAnnotation, $value)
    {
        if (!$nestedClassAnnotation->multiple) {
            if (null === $value && $nestedClassAnnotation->nullable) {
                return null;
            }

            return $this->serializer->denormalize($value, $nestedClassAnnotation->targetClass);
        }

        if (!\is_iterable($value)) {
            if (null === $value && $nestedClassAnnotation->nullable) {
                return null;
            }
            throw new \UnexpectedValueException('Value should be an array');
        }
        $values = [];
        /** @var array $value */
        foreach ($value as $item) {
            $values[] = $this->serializer->denormalize($item, $nestedClassAnnotation->targetClass);
        }

        return $values;
    }
}
