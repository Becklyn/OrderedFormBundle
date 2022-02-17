<?php declare(strict_types=1);

namespace Becklyn\OrderedFormBundle;

use Becklyn\OrderedFormBundle\DependencyInjection\BecklynOrderedFormExtension;
use Becklyn\OrderedFormBundle\DependencyInjection\FormConfigModifierPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BecklynOrderedFormBundle extends Bundle
{
    /**
     * @inheritDoc
     */
    public function build (ContainerBuilder $container) : void
    {
        $container->addCompilerPass(new FormConfigModifierPass());
    }


    /**
     * @inheritDoc
     */
    public function getContainerExtension () : ?ExtensionInterface
    {
        return new BecklynOrderedFormExtension();
    }
}
