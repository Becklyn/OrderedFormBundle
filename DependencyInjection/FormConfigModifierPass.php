<?php

namespace Becklyn\OrderedFormBundle\DependencyInjection;


use Becklyn\OrderedFormBundle\Form\ResolvedType\OrderedResolvedFormTypeFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;


class FormConfigModifierPass implements CompilerPassInterface
{
    /**
     * @inheritDoc
     */
    public function process (ContainerBuilder $container)
    {
        $container->getDefinition("form.resolved_type_factory")
            ->setArgument(0, new Reference(OrderedResolvedFormTypeFactory::class));
    }

}
