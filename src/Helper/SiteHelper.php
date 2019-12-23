<?php


namespace App\Helper;


use App\Entity\Site;

class SiteHelper
{
    /**
     * @param Site $site
     * @return string
     */
    public static function generateInternalName($site)
    {
        $siteNameChanged = StringHelper::skipAccents($site->getName());
        $siteNameChanged = preg_replace('/\s/', '', $siteNameChanged);

        return strtolower($siteNameChanged);
    }

    /**
     * Crée la clé unique du site
     * @param Site $site
     * @return string
     */
    public static function getuniqueKeySite(Site $site)
    {
        return $site->getId() . '-' . $site->getCreatedAt()->getTimestamp();
    }
}