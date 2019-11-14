<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ParamExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('param', [$this, 'getParam']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getParam', [$this, 'getParam']),
        ];
    }

    public function getParam($siteName, $uri)
    {
        if (preg_match("/\/site\//", $uri)) {
            return array('siteName' => $siteName);
        }

        return array();
    }
}
