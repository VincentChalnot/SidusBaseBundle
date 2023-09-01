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

namespace Sidus\BaseBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Generic compiler pass to add tagged services to another service
 *
 * @author Vincent Chalnot <vincent@sidus.fr>
 */
class GenericCompilerPass implements CompilerPassInterface
{
    public function __construct(
        private string $registry,
        private string $tag,
        private string $method,
        private bool $withPriority = false
    ) {
    }

    public function process(ContainerBuilder $container): void
    {
        if (!$container->has($this->registry)) {
            return;
        }

        $definition = $container->findDefinition($this->registry);
        $taggedServices = $container->findTaggedServiceIds($this->tag);

        foreach ($taggedServices as $id => $tags) {
            $arguments = [new Reference($id)];
            if ($this->withPriority) {
                $arguments[] = $this->resolvePriority($tags);
            }
            $definition->addMethodCall($this->method, $arguments);
        }
    }

    private function resolvePriority(array $tags): int
    {
        foreach ($tags as $tag) {
            if (array_key_exists('priority', $tag)) {
                return (int) $tag['priority'];
            }
        }

        return 0;
    }
}
