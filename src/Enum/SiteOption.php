<?php


namespace App\Enum;


class SiteOption
{
    const NEW_DOMAIN = array(
        'id' => 1,
        'label' => 'Nouveau nom de domaine',
        'description' => 'C\'est votre premier site, ou bien vous souhaitez avoir un nouveau nom de domaine',
        'price' => array(
            'label' => 'Inclus dans l\'offre',
            'double' => 0
        )
    );

    const EXISTING_DOMAIN = array(
        'id' => 2,
        'label' => 'Nom de domaine existant',
        'description' => 'Vous avez déjà votre site, vous souhaitez le conserver et nous en donner l\'administration.',
        'price' => array(
            'label' => '99 €',
            'double' => 99
        ));

    const TRANSFER_DOMAIN = array(
        'id' => 3,
        'label' => 'Transfert de nom de domaine',
        'description' => 'Vous avez déjà votre site, vous souhaitez le conserver et en garder l\'administration.',
        'price' => array(
            'label' => '99 €',
            'double' => 99
        ));

    public static function getById($id)
    {
        return self::toArray()[$id];
    }

    public static function toArray()
    {
        return array(
            self::NEW_DOMAIN['id'] => self::NEW_DOMAIN,
            self::EXISTING_DOMAIN['id'] => self::EXISTING_DOMAIN,
            self::TRANSFER_DOMAIN['id'] => self::TRANSFER_DOMAIN,
        );
    }

    public static function toIdArray()
    {
        return array(
            self::NEW_DOMAIN['id'],
            self::EXISTING_DOMAIN['id'],
            self::TRANSFER_DOMAIN['id']
        );
    }
}