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
        return self::getExtendedTypes()[0];
    }

    /**
     * @inheritDoc
     */
    public static function getExtendedTypes ()
    {
        return [ FormType::class ];
    }
}
