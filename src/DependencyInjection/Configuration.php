<?php

declare(strict_types=1);

namespace Dompat\StemmerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('dompat_stemmer');

        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();

        /** @var ArrayNodeDefinition $contextsNode */
        $contextsNode = $rootNode->children()->arrayNode('contexts');

        $contextsNode
            ->useAttributeAsKey('locale')
            ->scalarPrototype()->end();
        
        $contextsNode->info('Map locale to a driver class. Example: sk: Dompat\Stemmer\Driver\CzechDriver');

        return $treeBuilder;
    }
}
