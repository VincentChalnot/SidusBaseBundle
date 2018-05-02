<?php
/*
 * This file is part of the Sidus/BaseBundle package.
 *
 * Copyright (c) 2015-2018 Vincent Chalnot
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sidus\BaseBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Choice;

/**
 * Allows to input the same kind of data in a choice validator than in a ChoiceType (unwrap choice groups)
 *
 * @author Vincent Chalnot <vincent@sidus.fr>
 *
 * @Annotation
 */
class ChoiceUnwrapper extends Choice
{
    public $allowBlank = true;
}
