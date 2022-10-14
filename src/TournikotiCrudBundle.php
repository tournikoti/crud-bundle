<?php

namespace Tournikoti\CrudBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Tournikoti\CrudBundle\DependencyInjection\Compiler\AdminRouteLoaderCompilerPass;
use Tournikoti\CrudBundle\DependencyInjection\TournikotiCrudExtension;

class TournikotiCrudBundle extends AbstractBundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new TournikotiCrudExtension();
    }

    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new AdminRouteLoaderCompilerPass());
    }
}