<?php

namespace Becklyn\OrderedFormBundle\Form\ResolvedType;

use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\ResolvedFormTypeFactoryInterface;
use Symfony\Component\Form\ResolvedFormTypeInterface;


class OrderedResolvedFormTypeFactory implements ResolvedFormTypeFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createResolvedType (
        FormTypeInterface $type,
        array $typeExtensions,
        ResolvedFormTypeInterface $parent = null
    )
    {
        return new OrderedResolvedFormType($type, $typeExtensions, $parent);
    }


}
