<?php declare(strict_types=1);

namespace Becklyn\OrderedFormBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormPositionExtension extends AbstractTypeExtension
{
    /**
     * @inheritDoc
     */
    public function configureOptions (OptionsResolver $resolver) : void
    {
        $resolver
            ->setDefined("position")
            ->setAllowedTypes("position", ["null", "string", "integer", "array"])
            ->setDefault("position", null);
    }


    /**
     * @inheritDoc
     */
    public static function getExtendedTypes () : iterable
    {
        return [
            FormType::class,
            ButtonType::class,
        ];
    }
}
