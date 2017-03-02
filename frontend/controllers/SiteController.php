<?php
namespace frontend\controllers;

use backend\helpers\UserPermissions;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;

/**
 * Site controller
 */
class SiteController extends Controller
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

        $manager = $auth->createRole(UserPermissions::ROLE_MANAGER);
        $auth->add($manager);
        $auth->addChild($manager, $viewUser);
        $auth->addChild($manager, $manageItems);

        $admin = $auth->createRole(UserPermissions::ROLE_ADMIN);
        $auth->add($admin);
        $auth->addChild($admin, $deleteItem);
        $auth->addChild($admin, $viewUser);
        $auth->addChild($admin, $updateUser);
        $auth->addChild($admin, $manageItems);
        $auth->addChild($admin, $manager);
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $modelLogin = new LoginForm();
        if ($modelLogin->load(Yii::$app->request->post()) && $modelLogin->login()) {
            \Yii::$app->session->setFlash('success', 'You are logged!');
        }

        $modelSignUp = new SignupForm();
        if ($modelSignUp->load(Yii::$app->request->post()) && $modelSignUp->validate()) {
            if ($user = $modelSignUp->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    \Yii::$app->session->setFlash('success', 'You are registered!');
                }
            }
        }

        return $this->render('index', ['modelSignUp' => $modelSignUp, 'modelLogin' => $modelLogin]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }


    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
