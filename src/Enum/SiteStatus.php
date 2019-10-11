<?php


namespace App\Enum;


class SiteStatus
{
    const A_CREER = array('name' => 'A_CREE');
    const INITIALISE = array('name' => 'INIT');

    public static function getByName($type)
    {
        return self::toArray()[$type];
    }

    private static function toArray()
    {
        return array(
            self::A_CREER['name'] => self::A_CREER,
            self::INITIALISE['name'] => self::INITIALISE
        );
    }
}