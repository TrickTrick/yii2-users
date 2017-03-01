<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use frontend\helpers\country\Country;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<div class="site-signup">
    <h1>Sign up</h1>

    <p>Please fill out the following fields to signup:</p>

    <div class="row">
        <div class="col-md-9">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

            <?= $form->field($model, 'name')->textInput() ?>

            <?= $form->field($model, 'email') ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'birthday')->input('date') ?>

            <?= $form->field($model, 'role')->radioList([
                \common\models\User::ROLE_USER => 'Default User',
                \common\models\User::ROLE_MANAGER => 'Manager',
                \common\models\User::ROLE_ADMINISTRATOR => 'Admin']) ?>

            <?= $form->field($model, 'country')->widget(\frontend\widget\DatalistWidget::className(),
                [
                    'options' => Country::getList(),
                    'placeholder' => 'Select country',
                ]
            ); ?>

            <div class="form-group">
                <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
