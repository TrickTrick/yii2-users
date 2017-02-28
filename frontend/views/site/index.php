<?php

/* @var $this yii\web\View */

$this->title = 'Lets Fun';
?>
<div class="site-index">


    <div class="container">
        <div class="col-md-6">
            <?= $this->render('login', ['model' => $modelLogin]);?>
        </div>
        <div class="col-md-6">
            <?= $this->render('signup', ['model' => $modelSignUp]);?>
        </div>
    </div>
</div>
