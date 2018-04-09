<?php
/*
 * This file is part of the Sidus/BaseBundle package.
 *
 * Copyright (c) 2015-2018 Vincent Chalnot
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sidus\BaseBundle\Action;

use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;

/**
 * Tries to resolve the current controller from the route name
 *
 * @author Vincent Chalnot <vincent@sidus.fr>
 */
class ControllerResolver implements ControllerResolverInterface
{
    /** @var ContainerInterface */
    protected $container;

    /** @var ControllerResolverInterface */
    protected $baseControllerResolver;

    /**
     * @param ContainerInterface          $container
     * @param ControllerResolverInterface $baseControllerResolver
     */
    public function __construct(ContainerInterface $container, ControllerResolverInterface $baseControllerResolver)
    {
        $this->container = $container;
        $this->baseControllerResolver = $baseControllerResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function getController(Request $request)
    {
        if (!$request->attributes->get('_controller')) {
            $route = $request->attributes->get('_route');
            if (!$route || !$this->container->has($route)) {
                return false;
            }

            $request->attributes->set('_controller', $route);
        }

        return $this->baseControllerResolver->getController($request);
    }

    /**
     * {@inheritdoc}
     */
    public function getArguments(Request $request, $controller)
    {
        return $this->baseControllerResolver->getArguments($request, $controller);
    }
}
