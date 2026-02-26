<?php

declare(strict_types=1);

namespace Dompat\StemmerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('dompat_stemmer');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('contexts')
                    ->useAttributeAsKey('locale')
                    ->scalarPrototype()->end()
                    ->info('Map locale to a driver class. Example: sk: Dompat\Stemmer\Driver\CzechDriver')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
