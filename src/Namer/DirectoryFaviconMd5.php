<?php

namespace App\Namer;

use App\Entity\Site;
use App\Helper\SiteHelper;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\DirectoryNamerInterface;
use Vich\UploaderBundle\Naming\NamerInterface;

/**
 * Namer class.
 */
class DirectoryFaviconMd5 implements DirectoryNamerInterface
{
    /**
     * @param Site $object
     * @param PropertyMapping $mapping
     * @return string
     */
    public function directoryName($object, PropertyMapping $mapping): string
    {
        return '/' . SiteHelper::getuniqueKeySite($object). '/.favicon';
    }

}