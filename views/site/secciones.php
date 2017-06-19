<?php 
$this->title = 'Secciones';
?>

<div class="row">
	<div class="col-md-4">
		<a href="<?=Yii::$app->urlManager->createAbsoluteUrl ( ['doctores/index'] );?>">
			<div class="widget widget-shadow" id="widgetLineareaOne">
				<div class="widget-content">
					<div class="padding-20 padding-top-10">
						<div class="clearfix">
							<div class="grey-800 pull-left padding-vertical-10">
								<i class="glyphicon glyphicon-user"></i> Doctores
							</div>
						</div>
					</div>
				</div>
			</div>
		</a>
	</div>

	<div class="col-md-4">
		<a href="<?=Yii::$app->urlManager->createAbsoluteUrl ( ['pacientes/index'] );?>">
			<div class="widget widget-shadow" id="widgetLineareaOne">
				<div class="widget-content">
					<div class="padding-20 padding-top-10">
						<div class="clearfix">
							<div class="grey-800 pull-left padding-vertical-10">
								<i class="glyphicon glyphicon-user"></i> Pacientes
							</div>
						</div>
					</div>
				</div>
			</div>
		</a>
	</div>
</div>	