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

    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        $manageItems = $auth->createPermission(UserPermissions::MANAGE_ITEMS);
        $manageItems->description = 'Manage items';
        $auth->add($manageItems);

        $deleteItem = $auth->createPermission(UserPermissions::DELETE_ITEMS);
        $deleteItem->description = 'Delete item';
        $auth->add($deleteItem);

        $updateUser = $auth->createPermission(UserPermissions::UPDATE_ITEMS);
        $updateUser->description = 'Edit a user';
        $auth->add($updateUser);

        $viewUser = $auth->createPermission(UserPermissions::VIEW_ITEMS);
        $viewUser->description = 'View user';
        $auth->add($viewUser);

        $manager = $auth->createRole('manager');
        $auth->add($manager);
        $auth->addChild($manager, $viewUser);
        $auth->addChild($manager, $manageItems);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $deleteItem);
        $auth->addChild($admin, $viewUser);
        $auth->addChild($admin, $updateUser);
        $auth->addChild($admin, $manageItems);
        $auth->addChild($admin, $manager);

        $auth->assign($admin, 4);
        $auth->assign($manager, 5);
    }

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
                        'actions' => ['logout', 'index', 'init', 'update', 'view', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => [UserPermissions::MANAGE_ITEMS],
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