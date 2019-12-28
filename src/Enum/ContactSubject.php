<?php


namespace App\Enum;


class ContactSubject
{
    const AUTHENTICATION = array(
        'id' => 1,
        'label' => 'Problème d\'authentification',
        'description' => 'Merci de nous préciser dans votre message votre compte et le message d\'erreur que vous rencontrer s\'il y en a un'
    );

    const DEMANDE_DEVIS = array(
        'id' => 2,
        'label' => 'Demande de devis',
        'description' => 'Merci de nous préciser la description de votre projet ainsi qu\'un numéro de téléphone pour échanger avec vous sur ce projet'
    );

    const DEMANDE_INFORMATIONS = array(
        'id' => 3,
        'label' => 'Demande d\'information',
        'description' => null
    );

    public static function getById($id)
    {
        return self::toArray()[$id];
    }

    public static function toArray()
    {
        return array(
            self::AUTHENTICATION['id'] => self::AUTHENTICATION,
            self::DEMANDE_DEVIS['id'] => self::DEMANDE_DEVIS,
            self::DEMANDE_INFORMATIONS['id'] => self::DEMANDE_INFORMATIONS,
        );
    }

    public static function toIdArray()
    {
        return array(
            self::AUTHENTICATION['id'],
            self::DEMANDE_DEVIS['id'],
            self::DEMANDE_INFORMATIONS['id']
        );
    }
}