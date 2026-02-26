<?php

declare(strict_types=1);

namespace Dompat\StemmerBundle\Tests\Factory;

use Dompat\Stemmer\Contract\DriverInterface;
use Dompat\Stemmer\Stemmer;
use Dompat\StemmerBundle\Factory\StemmerFactory;
use PHPUnit\Framework\TestCase;

class StemmerFactoryTest extends TestCase
{
    public function testCreateStemmer(): void
    {
        $driver = $this->createStub(DriverInterface::class);
        $driver->method('getLocale')->willReturn('en');

        $stemmer = StemmerFactory::createStemmer([$driver]);

        $this->assertInstanceOf(Stemmer::class, $stemmer);
        $this->assertSame($driver, $stemmer->getDriver('en'));
    }
}
