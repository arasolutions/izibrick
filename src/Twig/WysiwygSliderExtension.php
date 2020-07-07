<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class WysiwygSliderExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('wysiwygSlider', [$this, 'parseWysiwygSlider']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('parseWysiwygSlider', [$this, 'parseWysiwygSlider']),
        ];
    }

    public function parseWysiwygSlider($value)
    {
        preg_match_all("/\<div.*carousel slide.*\>/", $value, $matches, PREG_OFFSET_CAPTURE);

        if (!empty($matches[0])) {

            $debSlider = substr($value, $matches[0][0][1]);

            preg_match_all("/\<div.*carousel-item.*\>/", $debSlider, $matchesEndItem, PREG_OFFSET_CAPTURE);

            preg_match_all("/\<\/div\>/", $debSlider, $matchesEnd, PREG_OFFSET_CAPTURE);

            $middleSlider = substr($debSlider, 0, $matchesEnd[0][sizeof($matchesEndItem[0]) * 2 + 1][1] + strlen("</div>"));

            $endSlider = substr($debSlider, $matchesEnd[0][sizeof($matchesEndItem[0]) * 2 + 1][1] + strlen("</div>"));

            return substr($value, 0, $matches[0][0][1]) . '</div>' . $middleSlider . '<div class="container">' . $endSlider;
        }

        return $value;
    }
}
