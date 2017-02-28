<?php
/**
 * Created by PhpStorm.
 * User: TrickTrick
 * Date: 28-Feb-17
 * Time: 14:13
 */

namespace frontend\helpers\country;


class Country
{

    public static function getList()
    {
        $retArr = [];
        foreach (self::getObjectsCountriesState() as $object) {
            $retArr[$object->id] = $object->title;
        }
        return $retArr;
    }


    protected static function getObjectsCountriesState()
    {
        $path = __DIR__ . '/dist/countries.json';
        $file = file_get_contents($path);
        return json_decode($file) ? json_decode($file) : null ;
    }
}