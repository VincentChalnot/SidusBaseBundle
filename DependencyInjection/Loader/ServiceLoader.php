<?php
/*
 * This file is part of the Sidus/BaseBundle package.
 *
 * Copyright (c) 2015-2018 Vincent Chalnot
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sidus\BaseBundle\DependencyInjection\Loader;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Finder\Finder;

/**
 * Loads all configuration files from a folder
 *
 * @author Vincent Chalnot <vincent@sidus.fr>
 */
class ServiceLoader
{
    /** @var ContainerBuilder */
    protected $container;

    /**
     * @param ContainerBuilder $container
     */
    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $path
     * @param string $extension
     *
     * @throws \Exception
     */
    public function loadFiles(string $path, $extension = 'yml')
    {
        $finder = new Finder();
        $finder->in($path)->name('*.'.$extension)->files();
        $loader = new YamlFileLoader($this->container, new FileLocator($path));
        foreach ($finder as $file) {
            $loader->load($file->getFilename());
        }
    }
}
