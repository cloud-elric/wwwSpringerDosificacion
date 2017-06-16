<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<div class="row">
	<div class="col-md-4 col-md-offset-4">
    <div class="panel">
    <div class="panel-body">
		<?php
		
$form3 = ActiveForm::begin ( [ 
	'action' => Url::base () . '/site/doctor-login' 
] );
		?>
	    <div
			class="form-group">
			<label class="control-label" for="usu">Email</label>
			<input type="text" name="usu" id="usu" class="form-control">

		</div>
		
		 <div
			class="form-group">
			<label class="control-label" for="pass">Contraseña</label>
			<input type="password" name="pass" id="pass" class="form-control">

		</div>

		<div class="col-md-12">
    <?php if(Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger" role="alert">
            <?= Yii::$app->session->getFlash('error') ?>
        </div>
    <?php endif; ?>
</div>
		<div class="form-group">
			<input type="submit" class="btn btn_primary" style="width: 100%">
		</div>
		<?php ActiveForm::end(); ?>
		
		<div
			class="form-group text-center">
			<?=Html::a('Recuperar contraseña', ['site/recovery-pass-form'])?>
		</div>
		</div>
		</div>
	</div>
</div>
