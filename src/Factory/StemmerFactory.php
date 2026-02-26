<?php

declare(strict_types=1);

namespace Dompat\StemmerBundle\Factory;

use Dompat\Stemmer\Contract\DriverInterface;
use Dompat\Stemmer\Stemmer;

class StemmerFactory
{
    /**
     * @param iterable<DriverInterface> $drivers
     */
    public static function createStemmer(iterable $drivers): Stemmer
    {
        $driversArray = iterator_to_array($drivers);
        $reversedDrivers = array_reverse($driversArray);

        return new Stemmer($reversedDrivers);
    }
}
