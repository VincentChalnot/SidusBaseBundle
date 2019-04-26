<?php
/*
 * This file is part of the Sidus/BaseBundle package.
 *
 * Copyright (c) 2015-2019 Vincent Chalnot
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sidus\BaseBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Allows to input the iterable values instead of only arrays for choice validation
 *
 * @author Vincent Chalnot <vincent@sidus.fr>
 *
 * @Annotation
 */
class ChoiceValidator extends \Symfony\Component\Validator\Constraints\ChoiceValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if ($value instanceof \Traversable) {
            $value = iterator_to_array($value);
        }

        parent::validate($value, $constraint);
    }
}
