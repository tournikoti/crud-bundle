<?php

namespace Tournikoti\CrudBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Tournikoti\CrudBundle\Route\AdminRouteLoader;

class AdminRouteLoaderCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(AdminRouteLoader::class)) {
            return;
        }

        $definition = $container->findDefinition(AdminRouteLoader::class);

        $taggedServices = $container->findTaggedServiceIds('admin.crud');

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $tag) {
                $definition->addMethodCall('addAdminCrud', [new Reference($id), $tag]);
            }
        }
    }
}