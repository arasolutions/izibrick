<?php


namespace App\Helper;


/**
 * Class ColorHelper
 * @package App\Helper
 */
class ColorHelper
{
    /**
     * Transformation d'un code type #FFFFFF en RGB
     * @param $hexa string
     * @return array
     */
    static function hexaToRgb($hexa)
    {
        if ($hexa == null) {
            return null;
        }

        if (strlen($hexa) != 4 && strlen($hexa) != 7) {
            return null;
        }

        if (strlen($hexa) == 4) {
            $r = $hexa[1] . $hexa[1];
            $g = $hexa[2] . $hexa[2];
            $b = $hexa[3] . $hexa[3];
            return array(hexdec($r), hexdec($g), hexdec($b));
        }

        if (strlen($hexa) == 7) {
            $r = substr($hexa, 1, 2);
            $g = substr($hexa, 3, 2);
            $b = substr($hexa, 5, 2);
            return array(hexdec($r), hexdec($g), hexdec($b));
        }

        return null;

    }

    /**
     * @param $rgb array
     * @return float
     */
    static function getLuminance($rgb)
    {
        return (0.299 * $rgb[0] + 0.587 * $rgb[1] + 0.114 * $rgb[2]) / 255;
    }
}