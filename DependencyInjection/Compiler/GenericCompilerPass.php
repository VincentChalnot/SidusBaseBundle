<?php
/*
 * This file is part of the Sidus/BaseBundle package.
 *
 * Copyright (c) 2015-2018 Vincent Chalnot
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
    /** @var string */
    protected $registry;

    /** @var string */
    protected $tag;

    /** @var string */
    protected $method;

    /** @var bool */
    protected $withPriority;

    /**
     * @param string $registry
     * @param string $tag
     * @param string $method
     * @param bool   $withPriority
     */
    public function __construct($registry, $tag, $method, $withPriority = false)
    {
        $this->registry = $registry;
        $this->tag = $tag;
        $this->method = $method;
        $this->withPriority = $withPriority;
    }

    /**
     * Inject tagged services into defined registry
     *
     * @api
     *
     * @param ContainerBuilder $container
     *
     * @throws InvalidArgumentException
     */
    public function process(ContainerBuilder $container)
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

    /**
     * @param array $tags
     *
     * @return int
     */
    protected function resolvePriority(array $tags)
    {
        foreach ($tags as $tag) {
            if (array_key_exists('priority', $tag)) {
                return (int) $tag['priority'];
            }
        }

        return 0;
    }
}
