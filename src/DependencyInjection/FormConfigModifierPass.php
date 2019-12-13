<?php declare(strict_types=1);

namespace Becklyn\OrderedFormBundle\DependencyInjection;

use Becklyn\OrderedFormBundle\Form\ResolvedType\OrderedResolvedFormTypeFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Form\Extension\DataCollector\Proxy\ResolvedTypeFactoryDataCollectorProxy;

class FormConfigModifierPass implements CompilerPassInterface
{
    /**
     * @inheritDoc
     */
    public function process (ContainerBuilder $container) : void
    {
        $service = $container->getDefinition("form.resolved_type_factory");

        if (ResolvedTypeFactoryDataCollectorProxy::class === $service->getClass())
        {
            // keep the data collector proxy, so that the profiler still works
            $service
                ->setArgument(0, new Reference(OrderedResolvedFormTypeFactory::class));
        }
        else
        {
            // just overwrite the service
            $service->setClass(OrderedResolvedFormTypeFactory::class);
        }
    }

}
