<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class WysiwygBannerExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('wysiwygBanner', [$this, 'parseWysiwygBanner']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('wysiwygBanner', [$this, 'parseWysiwygBanner']),
        ];
    }

    public function parseWysiwygBanner($value)
    {
        preg_match_all("/\<section.*page-header.*\>/", $value, $matches, PREG_OFFSET_CAPTURE);

        if (!empty($matches[0])) {

            $debBanner = substr($value, $matches[0][0][1]);

            preg_match_all("/\<\/section\>/", $debBanner, $matchesEnd, PREG_OFFSET_CAPTURE);

            $middleBanner = substr($debBanner, 0, $matchesEnd[0][0][1] + strlen("</section>"));

            $endBanner = substr($debBanner, $matchesEnd[0][0][1] + strlen("</section>"));

            return substr($value, 0, $matches[0][0][1]) . '</div>' . $middleBanner . '<div class="container">' . $endBanner;
        }

        return $value;
    }
}
