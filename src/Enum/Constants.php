<?php


namespace App\Enum;


class Constants
{
    /**
     * @var string Variable Session permettant de stocker l'identifiant du site
     */
    public const SESSION_SITE_ID = 'SITE_ID';

    /** @var float Seuil de passage de l'écriture à claire ou foncé */
    public const LUMINANCE_THRESHOLD = 0.22;
}