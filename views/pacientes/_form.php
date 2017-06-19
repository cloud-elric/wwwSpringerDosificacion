<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\models\EntPacientes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ent-pacientes-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'txt_nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'txt_apellido_paterno')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'txt_apellido_materno')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'txt_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'txt_telefono_contacto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fch_nacimiento')->textInput(['class'=>'form_datetime']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$this->registerJs ( "
	$.fn.datetimepicker.dates['es'] = {
		days: ['Domingo', 'Lunes', 'Martes', 'Mi�rcoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'],
		daysShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
		daysMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa', 'Do'],
		months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
		monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
		today: 'Hoy',
		suffix: [],
		meridiem: []
	};
		
	$('.form_datetime').datetimepicker({format: 'd-mm-yyyy', minView: 2, language: 'es', orientation: 'top'});
", View::POS_END );
?>