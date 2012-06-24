<?php

namespace OS\RssBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('os_rss');
        
        $rootNode
            ->children()
                ->scalarNode('title')->end()
                ->scalarNode('description')->end()
                ->scalarNode('language')->end()
                ->scalarNode('webMaster')->end()
                ->scalarNode('link')->end()
                ->arrayNode('item')
                    ->children()
                        ->scalarNode('entity')->end()
                        ->scalarNode('alias')->end()
                        ->scalarNode('where')->end()
                        ->scalarNode('limit')->end()
                        ->scalarNode('title')->end()
                        ->scalarNode('description')->end()
                        ->scalarNode('pubDate')->end()
                        ->scalarNode('guid')->end()
                        ->arrayNode('link')
                            ->children()
                                ->scalarNode('route')->end()
                                ->arrayNode('params')
                                    ->children()
                
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()            
            ->end()
        ->end();

        return $treeBuilder;
    }
}
