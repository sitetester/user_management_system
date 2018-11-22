<?php
declare(strict_types=1);

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * https://symfony.com/doc/current/templating/twig_extension.html
 */
class AppAdjustLengthExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('adjustLength', [$this, 'adjustLength']),
        ];
    }

    public function adjustLength($text, int $maxLength): string
    {
        return \strlen($text) > $maxLength ? substr($text, 0, $maxLength) : $text;
    }
}
