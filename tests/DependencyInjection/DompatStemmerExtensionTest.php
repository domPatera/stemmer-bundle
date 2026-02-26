<?php

declare(strict_types=1);

namespace Dompat\StemmerBundle\Tests\DependencyInjection;

use Dompat\Stemmer\Contract\DriverInterface;
use Dompat\Stemmer\Driver\CzechDriver;
use Dompat\Stemmer\Stemmer;
use Dompat\StemmerBundle\DependencyInjection\DompatStemmerExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class DompatStemmerExtensionTest extends TestCase
{
    private ContainerBuilder $container;
    private DompatStemmerExtension $extension;

    protected function setUp(): void
    {
        $this->container = new ContainerBuilder();
        $this->extension = new DompatStemmerExtension();
    }

    public function testLoadDefaultConfiguration(): void
    {
        $this->extension->load([], $this->container);

        $this->assertTrue($this->container->hasDefinition(Stemmer::class));

        $stemmerDefinition = $this->container->getDefinition(Stemmer::class);
        $factory = $stemmerDefinition->getFactory();
        $this->assertInstanceOf(Reference::class, $factory[0]);
        $this->assertEquals('Dompat\StemmerBundle\Factory\StemmerFactory', (string) $factory[0]);
        $this->assertEquals('createStemmer', $factory[1]);
    }

    public function testLoadCustomContexts(): void
    {
        $configs = [
            'dompat_stemmer' => [
                'contexts' => [
                    'sk' => CzechDriver::class,
                ],
            ],
        ];

        $this->extension->load($configs, $this->container);

        $this->assertTrue($this->container->hasDefinition('dompat_stemmer.driver.sk'));
        
        $skDriverDefinition = $this->container->getDefinition('dompat_stemmer.driver.sk');
        $this->assertEquals(CzechDriver::class, $skDriverDefinition->getClass());
        $this->assertEquals('sk', $skDriverDefinition->getArgument('$locale'));
        
        $tags = $skDriverDefinition->getTag('dompat.stemmer.driver');
        $this->assertCount(1, $tags);
        $this->assertEquals(10, $tags[0]['priority']);
    }

    public function testAutoconfiguration(): void
    {
        $this->extension->load([], $this->container);

        $autoconfigured = $this->container->getAutoconfiguredInstanceof();
        $this->assertArrayHasKey(DriverInterface::class, $autoconfigured);
        
        $definition = $autoconfigured[DriverInterface::class];
        $this->assertTrue($definition->hasTag('dompat.stemmer.driver'));
        
        $tags = $definition->getTag('dompat.stemmer.driver');
        $this->assertEquals(0, $tags[0]['priority']);
    }
}
