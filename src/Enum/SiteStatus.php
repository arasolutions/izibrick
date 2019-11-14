<?php


namespace App\Enum;


class SiteStatus
{
    const INITIALISE = array('name' => 'INIT');
    const ACTIF = array('name' => 'ACTIF');

    public static function getByName($type)
    {
        return self::toArray()[$type];
    }

    private static function toArray()
    {
        return array(
            self::INITIALISE['name'] => self::INITIALISE,
            self::ACTIF['name'] => self::ACTIF
        );
    }
}