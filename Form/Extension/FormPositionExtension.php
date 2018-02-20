<?php

namespace Becklyn\OrderedFormBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class FormPositionExtension extends AbstractTypeExtension
{
    /**
     * @inheritDoc
     */
    public function configureOptions (OptionsResolver $resolver)
    {
        $resolver
            ->setDefined("position")
            ->setAllowedTypes("position", ["null", "string", "integer", "array"])
            ->setDefault("position", null);
    }


    /**
     * @inheritDoc
     */
    public function getExtendedType ()
    {
        return FormType::class;
    }
}
