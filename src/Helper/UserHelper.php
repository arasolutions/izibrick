<?php


namespace App\Helper;


class UserHelper
{
    /**
     * @param $firstName
     * @param $lastName
     * @return string
     */
    public  static function generateUsername($firstName, $lastName){
        return $firstName.$lastName;
    }

    /**
     * @return string
     */
    public static function generatePassword(){
        return "admin";
    }
}