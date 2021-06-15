<?php
/*
 * This file is part of the Sidus/BaseBundle package.
 *
 * Copyright (c) 2015-2021 Vincent Chalnot
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sidus\BaseBundle\Doctrine;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use UnexpectedValueException;

/**
 * Simplified access to entities repository with proper exception management
 *
 * This class is not defined as a service so you can use this bundle without Doctrine
 *
 * @author Vincent Chalnot <vincent@sidus.fr>
 */
class RepositoryFinder
{
    /** @var ManagerRegistry */
    protected $doctrine;

    /**
     * @param ManagerRegistry $doctrine
     */
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @param string $className
     *
     * @return EntityRepository
     */
    public function getRepository($className)
    {
        $entityManager = $this->doctrine->getManagerForClass($className);
        if (!$entityManager instanceof EntityManagerInterface) {
            throw new UnexpectedValueException("No manager found for class {$className}");
        }
        $repository = $entityManager->getRepository($className);
        if (!$repository instanceof EntityRepository) {
            throw new UnexpectedValueException("No repository found for class {$className}");
        }

        return $repository;
    }
}
