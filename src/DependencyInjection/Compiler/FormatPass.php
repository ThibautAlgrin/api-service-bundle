<?php

namespace ElevenLabs\ApiServiceBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class FormatPass.
 */
class FormatPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('api_service.decoder.symfony');

        $ids = [];
        foreach ($container->findTaggedServiceIds('serializer.encoder') as $id => $configs) {
            $ids[] = $container->getDefinition($id);
        }
        $definition->setArgument(0, $ids);
    }
}
