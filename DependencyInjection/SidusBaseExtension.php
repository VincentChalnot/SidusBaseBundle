<?php
/*
 * This file is part of the Sidus/BaseBundle package.
 *
 * Copyright (c) 2015-2019 Vincent Chalnot
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sidus\BaseBundle\DependencyInjection;

use Sidus\BaseBundle\DependencyInjection\Loader\ServiceLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * You can directly inherit from this class to automatically load every files from your Resources/config/services dir.
 *
 * @author Vincent Chalnot <vincent@sidus.fr>
 */
class SidusBaseExtension extends Extension
{
    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $refl = new \ReflectionClass($this); // Supports for class extending this one
        $loader = new ServiceLoader($container);
        $serviceFolderPath = \dirname($refl->getFileName(), 2).'/Resources/config/services';
        $loader->loadFiles($serviceFolderPath);
    }
}
