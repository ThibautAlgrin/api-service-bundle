<?php

namespace ElevenLabs\ApiServiceBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ArrayNode;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * Class Configuration.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Whether to use the debug mode.
     *
     * @see https://github.com/doctrine/DoctrineBundle/blob/v1.5.2/DependencyInjection/Configuration.php#L31-L41
     *
     * @var bool
     */
    private $debug;

    /**
     * @param bool $debug
     */
    public function __construct($debug)
    {
        $this->debug = (bool) $debug;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('api_service');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('default_services')
                    ->addDefaultsIfNotSet()
                    ->info('Configure which services to use when generating API service classes')
                    ->children()
                        ->scalarNode('client')->defaultValue('httplug.client')->end()
                        ->scalarNode('message_factory')->defaultValue('httplug.message_factory')->end()
                        ->scalarNode('uri_factory')->defaultValue('httplug.uri_factory')->end()
                    ->end()
                ->end()
                ->arrayNode('cache')
                    ->info('Activate API schemas cache')
                    ->canBeEnabled()
                    ->children()
                        ->scalarNode('service')->info('The service Id that should be used for caching schemas')->isRequired()->end()
                    ->end()
                ->end()
                ->arrayNode('pagination')
                    ->info('Pagination providers')
                    ->useAttributeAsKey('name')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('page')->defaultValue('X-Page')->end()
                            ->scalarNode('perPage')->defaultValue('X-Per-Page')->end()
                            ->scalarNode('totalPages')->defaultValue('X-Total-Pages')->end()
                            ->scalarNode('totalItems')->defaultValue('X-Total-Items')->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('apis')
                    ->info('Declare API services')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                    ->children()
                        ->scalarNode('schema')->info('Absolute path to the OpenAPI/Swagger2.0 schema')->isRequired()->end()
                        ->scalarNode('client')->info('Use a specific HTTP client for an API Service')->defaultValue('api_service.client')->end()
                        ->arrayNode('config')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('baseUri')->defaultValue(null)->info('The uri of your service (ex: http://domain.tld)')->end()
                                ->scalarNode('validateRequest')->defaultTrue()->info('Validate the request before sending it')->end()
                                ->scalarNode('validateResponse')->defaultFalse()->info('Validate the response before sending it')->end()
                                ->scalarNode('returnResponse')->defaultFalse()->info('Return a Response object instead of a resource')->end()
                            ->end()
                        ->end()

                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
