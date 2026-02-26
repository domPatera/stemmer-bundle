<?php

declare(strict_types=1);

namespace Dompat\StemmerBundle\Tests\Twig;

use Dompat\Stemmer\Contract\DriverInterface;
use Dompat\Stemmer\Enum\StemmerMode;
use Dompat\Stemmer\Stemmer;
use Dompat\StemmerBundle\Twig\StemmerExtension;
use PHPUnit\Framework\TestCase;

class StemmerExtensionTest extends TestCase
{
    private Stemmer $stemmer;
    private StemmerExtension $extension;

    protected function setUp(): void
    {
        $this->stemmer = new Stemmer();
        $this->extension = new StemmerExtension($this->stemmer);
    }

    public function testGetFilters(): void
    {
        $filters = $this->extension->getFilters();
        $this->assertCount(1, $filters);
        $this->assertSame('stem', $filters[0]->getName());
    }

    public function testStemFilter(): void
    {
        $driver = $this->createMock(DriverInterface::class);
        $driver->method('getLocale')->willReturn('en');
        $driver->expects($this->once())
            ->method('stem')
            ->with('working', StemmerMode::LIGHT)
            ->willReturn('work');

        $this->stemmer->addDriver($driver);

        $result = $this->extension->stemFilter('working', 'en');
        $this->assertSame('work', $result);
    }

    public function testStemFilterWithMode(): void
    {
        $driver = $this->createMock(DriverInterface::class);
        $driver->method('getLocale')->willReturn('en');
        $driver->expects($this->once())
            ->method('stem')
            ->with('working', StemmerMode::AGGRESSIVE)
            ->willReturn('work');

        $this->stemmer->addDriver($driver);

        $result = $this->extension->stemFilter('working', 'en', StemmerMode::AGGRESSIVE);
        $this->assertSame('work', $result);
    }

    public function testStemFilterWithStringMode(): void
    {
        $driver = $this->createMock(DriverInterface::class);
        $driver->method('getLocale')->willReturn('cs');
        $driver->expects($this->once())
            ->method('stem')
            ->with('working', StemmerMode::AGGRESSIVE)
            ->willReturn('work');

        $this->stemmer->addDriver($driver);

        $result = $this->extension->stemFilter('working', 'cs', 'aggressive');
        $this->assertSame('work', $result);
    }
}
