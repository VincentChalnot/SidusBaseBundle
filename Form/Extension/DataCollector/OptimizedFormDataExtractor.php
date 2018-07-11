<?php
/*
 * This file is part of the Sidus/BaseBundle package.
 *
 * Copyright (c) 2015-2018 Vincent Chalnot
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sidus\BaseBundle\Form\Extension\DataCollector;

use Symfony\Component\Form\Extension\DataCollector\FormDataExtractor;
use Symfony\Component\Form\FormView;
use Symfony\Component\VarDumper\Caster\CutStub;

/**
 * Removing redondant 'form' variable from view variables to massively reduce memory footprint
 *
 * @author Vincent Chalnot <vincent@sidus.fr>
 */
class OptimizedFormDataExtractor extends FormDataExtractor
{
    /**
     * {@inheritdoc}
     */
    public function extractViewVariables(FormView $view)
    {
        $data = parent::extractViewVariables($view);

        if (isset($data['view_vars']['form'])) {
            $data['view_vars']['form'] = new CutStub($data['view_vars']['form']);
        }

        return $data;
    }
}
