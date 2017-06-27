<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EntDoctoresSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ent-doctores-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_doctor') ?>

    <?= $form->field($model, 'txt_nombre') ?>

    <?= $form->field($model, 'txt_apellido_paterno') ?>

    <?= $form->field($model, 'txt_email') ?>

    <?= $form->field($model, 'txt_password') ?>

    <?php // echo $form->field($model, 'b_habilitado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
