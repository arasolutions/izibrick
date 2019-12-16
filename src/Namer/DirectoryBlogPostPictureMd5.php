<?php

namespace App\Namer;

use App\Entity\Post;
use App\Entity\Site;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\DirectoryNamerInterface;
use Vich\UploaderBundle\Naming\NamerInterface;

/**
 * Directory Namer class.
 */
class DirectoryBlogPostPictureMd5 implements DirectoryNamerInterface
{
    /**
     * @param Post $object
     * @param PropertyMapping $mapping
     * @return string
     */
    public function directoryName($object, PropertyMapping $mapping): string
    {
        return '/' . $object->getBlog()->getSite()->getId(). '/blog/post_picture';
    }

}