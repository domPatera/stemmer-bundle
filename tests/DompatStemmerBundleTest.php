<?php

declare(strict_types=1);

namespace Dompat\StemmerBundle\Tests;

use Dompat\StemmerBundle\DompatStemmerBundle;
use PHPUnit\Framework\TestCase;

class DompatStemmerBundleTest extends TestCase
{
    public function testBundle(): void
    {
        $bundle = new DompatStemmerBundle();
        $this->assertInstanceOf(DompatStemmerBundle::class, $bundle);
    }
}
