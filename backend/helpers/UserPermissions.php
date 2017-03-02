<?php

namespace backend\helpers;
use common\models\User;

/**
 * Created by PhpStorm.
 ** User: Home alexeymarkov.x7@gmail.com
 *** Date: 02.03.2017
 **** Time: 0:42
 */
class UserPermissions
{
    const ROLE_ADMIN = 'admin';
    const ROLE_MANAGER = 'manager';

    const MANAGE_ITEMS = 'manageItems';
    const VIEW_ITEMS   = 'viewItem';
    const CREATE_ITEMS = 'createItem';
    const UPDATE_ITEMS = 'updateItem';
    const DELETE_ITEMS = 'deleteItem';

    public static function giveRole($role)
    {
        if($role == User::ROLE_ADMINISTRATOR){
            return UserPermissions::ROLE_ADMIN;
        }
        return UserPermissions::ROLE_MANAGER;
    }

}