<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\EntPacientes */

$this->title = 'Crear Paciente';
$this->params['breadcrumbs'][] = ['label' => 'Pacientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ent-pacientes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
