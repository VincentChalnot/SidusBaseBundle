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

/**
 * Allows to input the iterable values instead of only arrays for choice validation
 *
 * @author Vincent Chalnot <vincent@sidus.fr>
 *
 * @Annotation
 */
class Choice extends \Symfony\Component\Validator\Constraints\Choice
{
    public $allowBlank = true;
}
