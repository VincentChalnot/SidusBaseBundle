<?php
/*
 * This file is part of the Sidus/BaseBundle package.
 *
 * Copyright (c) 2015-2021 Vincent Chalnot
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sidus\BaseBundle\Utilities;

use ReflectionClass;
use function in_array;

/**
 * Use this class to output the proper values for the magic __sleep method when you want to exclude certain properties
 *
 * @author Vincent Chalnot <vincent@sidus.fr>
 */
class SleepUtility
{
    /**
     * @param string $class
     * @param array  $excludedProperties
     *
     * @return array
     */
    public static function sleep($class, array $excludedProperties = [])
    {
        $propertyNames = [];
        $refl = new ReflectionClass($class);
        foreach ($refl->getProperties() as $property) {
            if (in_array($property->getName(), $excludedProperties, true)) {
                continue;
            }
            $propertyNames[] = $property->getName();
        }

        return $propertyNames;
    }
}
