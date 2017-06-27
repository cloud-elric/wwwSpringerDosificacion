<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EntUsuarios */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ent-usuarios-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'txt_username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'txt_apellido_paterno')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'txt_apellido_materno')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'txt_email')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'repeatPassword')->passwordInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
