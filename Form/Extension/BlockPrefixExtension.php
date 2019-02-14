<?php
/*
 * This file is part of the Sidus/BaseBundle package.
 *
 * Copyright (c) 2015-2018 Vincent Chalnot
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sidus\BaseBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Adds a custom block_prefix option to adds a block prefix to the automatically computed ones
 *
 * @author Vincent Chalnot <vincent@sidus.fr>
 */
class BlockPrefixExtension extends AbstractTypeExtension
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'block_prefix' => null,
            ]
        );
    }

    /**
     * @param FormView      $view
     * @param FormInterface $form
     * @param array         $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if (null !== $options['block_prefix']) {
            array_splice(
                $view->vars['block_prefixes'],
                -1,
                0,
                [$options['block_prefix']]
            );
        }
    }

    /**
     * @return string
     */
    public function getExtendedType(): string
    {
        return FormType::class;
    }
}
