<?php
/*
 * This file is part of the Sidus/BaseBundle package.
 *
 * Copyright (c) 2015-2018 Vincent Chalnot
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sidus\BaseBundle\Request\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Common pattern for param converters
 *
 * @author Vincent Chalnot <vincent@sidus.fr>
 */
abstract class AbstractParamConverter implements ParamConverterInterface
{
    /**
     * Stores the object in the request.
     *
     * @param Request        $request
     * @param ParamConverter $configuration Contains the name, class and options of the object
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \InvalidArgumentException
     *
     * @return bool True if the object has been successfully set, else false
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $param = $this->getRequestAttributeName($request, $configuration);

        if (!$request->attributes->has($param)) {
            return false;
        }

        $value = $request->attributes->get($param);

        if (!$value && $configuration->isOptional()) {
            return false;
        }

        $convertedValue = $this->convertValue($value, $configuration);

        if (null === $convertedValue && false === $configuration->isOptional()) {
            throw new NotFoundHttpException(
                "Unable to find '{$configuration->getClass()}' with identifier '{$value}' not found"
            );
        }

        $request->attributes->set($configuration->getName(), $convertedValue);

        return true;
    }

    /**
     * Checks if the object is supported.
     *
     * @param ParamConverter $configuration Should be an instance of ParamConverter
     *
     * @return bool True if the object is supported, else false
     */
    public function supports(ParamConverter $configuration)
    {
        return $configuration->getClass() && is_a($configuration->getClass(), $this->getClass(), true);
    }

    /**
     * @param Request        $request
     * @param ParamConverter $configuration
     *
     * @return string
     */
    protected function getRequestAttributeName(Request $request, ParamConverter $configuration)
    {
        $param = $configuration->getName();
        if (array_key_exists('id', $configuration->getOptions())) {
            $param = $configuration->getOptions()['id'];
        }

        return $param;
    }

    /**
     * @param mixed          $value
     * @param ParamConverter $configuration
     *
     * @return mixed
     */
    abstract protected function convertValue($value, ParamConverter $configuration);

    /**
     * @return mixed
     */
    abstract protected function getClass();
}
