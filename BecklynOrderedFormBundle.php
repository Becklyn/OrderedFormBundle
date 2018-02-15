<?php

namespace Becklyn\OrderedFormBundle;

use Becklyn\OrderedFormBundle\DependencyInjection\BecklynOrderedFormExtension;
use Becklyn\OrderedFormBundle\DependencyInjection\FormConfigModifierPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;


class BecklynOrderedFormBundle extends Bundle
{
    /**
     * @inheritDoc
     */
    public function build (ContainerBuilder $container)
    {
        $container->addCompilerPass(new FormConfigModifierPass());
    }


    /**
     * @inheritDoc
     */
    public function getContainerExtension ()
    {
        return new BecklynOrderedFormExtension();
    }
}
