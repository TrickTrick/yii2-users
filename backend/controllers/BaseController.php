<?php
/**
 * Created by PhpStorm.
 ** User: Home alexeymarkov.x7@gmail.com
 *** Date: 01.03.2017
 **** Time: 22:32
 */

namespace backend\controllers;


use backend\helpers\UserPermissions;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class BaseController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => [UserPermissions::VIEW_ITEMS],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => [UserPermissions::CREATE_ITEMS],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => [UserPermissions::UPDATE_ITEMS],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => [UserPermissions::DELETE_ITEMS],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }


}