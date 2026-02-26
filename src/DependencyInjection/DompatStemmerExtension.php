<?php

declare(strict_types=1);

namespace Dompat\StemmerBundle\DependencyInjection;

use Dompat\Stemmer\Contract\DriverInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

class DompatStemmerExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('services.yaml');

        $configuration = new Configuration();
        /** @var array{contexts?: array<string, string>} $config */
        $config = $this->processConfiguration($configuration, $configs);

        if (isset($config['contexts'])) {
            foreach ($config['contexts'] as $locale => $driverClass) {
                $definition = new Definition($driverClass);
                $definition->setArgument('$locale', $locale);

                $definition->setAutowired(true);
                $definition->setAutoconfigured(true);

                $definition->addTag('dompat.stemmer.driver', ['priority' => 10]);

                $container->setDefinition('dompat_stemmer.driver.' . $locale, $definition);
            }
        }

        $container->registerForAutoconfiguration(DriverInterface::class)
            ->addTag('dompat.stemmer.driver', ['priority' => 0]);
    }
}
