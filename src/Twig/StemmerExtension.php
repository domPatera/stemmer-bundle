<?php

declare(strict_types=1);

namespace Dompat\StemmerBundle\Twig;

use Dompat\Stemmer\Enum\StemmerMode;
use Dompat\Stemmer\Contract\StemmerModeInterface;
use Dompat\Stemmer\Stemmer;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class StemmerExtension extends AbstractExtension
{
    public function __construct(
        private readonly Stemmer $stemmer
    ) {}

    public function getFilters(): array
    {
        return [
            new TwigFilter('stem', [$this, 'stemFilter']),
        ];
    }

    public function stemFilter(string $word, string $locale, string|StemmerModeInterface $mode = StemmerMode::LIGHT): string
    {
        if (is_string($mode)) {
            $mode = StemmerMode::tryFrom(strtolower($mode)) ?? StemmerMode::LIGHT;
        }

        return $this->stemmer->stem($word, $locale, $mode);
    }
}
