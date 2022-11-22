<?php
/*
 * This file is part of the Sidus/BaseBundle package.
 *
 * Copyright (c) 2015-2021 Vincent Chalnot
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

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
    public function __construct(
        private ContainerInterface $container,
        private ControllerResolverInterface $baseControllerResolver,
    ) {
    }

    public function getController(Request $request): callable|false
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

    public function getArguments(Request $request, $controller)
    {
        return $this->baseControllerResolver->getArguments($request, $controller);
    }
}
