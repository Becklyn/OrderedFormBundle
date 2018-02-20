<?php

namespace Becklyn\OrderedFormBundle\Form\Extension;


use Symfony\Component\Form\Extension\Core\Type\ButtonType;


class ButtonPositionExtension extends FormPositionExtension
{
    /**
     * @inheritDoc
     */
    public function getExtendedType ()
    {
        return ButtonType::class;
    }
}
