<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class RouteExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('route', [$this, 'getRoute']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getRoute', [$this, 'getRoute']),
        ];
    }

    public function getRoute($value, $uri)
    {
        if (!preg_match("/\/site\//", $uri)) {
            return $value;
        }

        return 'site_' . $value;
    }
}
