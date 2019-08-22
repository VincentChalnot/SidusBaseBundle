<?php
/*
 * This file is intentionally left without a copyright notice because the code comes from a ticket who belongs to
 * Emmanuel BALLERY https://github.com/emmanuelballery
 * https://github.com/symfony/symfony/issues/8834#issuecomment-330831471
 */

namespace Sidus\BaseBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

/**
 * Triggers the proper form events on inherited data forms
 *
 * @see https://github.com/symfony/symfony/issues/8834#issuecomment-330831471
 *
 * @author Emmanuel BALLERY
 */
class BubblePreSetDataEventExtension extends AbstractTypeExtension
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Only *_SET_DATA events are concerned
        foreach ([FormEvents::PRE_SET_DATA, FormEvents::POST_SET_DATA] as $eventName) {
            $builder->addEventListener(
                $eventName,
                static function (FormEvent $event) use ($eventName) {
                    // Look for "inherit_data" child
                    foreach ($event->getForm() as $child) {
                        if ($child instanceof FormInterface && $child->getConfig()->getInheritData()) {
                            // Create a new event and dispatch it to the child
                            $childEvent = new FormEvent($child, $event->getData());
                            $child->getConfig()->getEventDispatcher()->dispatch($eventName, $childEvent);
                        }
                    }
                }
            );
        }
    }

    /**
     * @return string
     */
    public function getExtendedType()
    {
        return FormType::class;
    }
}
