<?php
/**
 * Created by PhpStorm.
 * User: TrickTrick
 * Date: 02-Mar-17
 * Time: 10:52
 */

namespace common\models;


use yii\db\ActiveRecord;

/**
 * @property  $user_id
 * @property  $ip
 **/
class UserLog extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%ip_list}}';
    }

    public static function add(User $user)
    {
        $log = new self();
        $log->user_id = $user->id;
        $log->ip = \Yii::$app->request->getUserIP();
        return $log->save()? $log : null;
    }

    public static function getDiffIps(User $user)
    {
        $userLog = self::find()->where(['user_id' => $user->id])->groupBy('ip')->count();
        return (int)$userLog;
    }

    public static function isItNewIp(User $user)
    {
        $ip = \Yii::$app->request->getUserIP();
        return $user->ip !== $ip ? true : false;
    }

}