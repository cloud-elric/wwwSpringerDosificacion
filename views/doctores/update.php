<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EntDoctores */

$this->title = 'Actualizar Doctor: ' . $model->id_doctor;
$this->params['breadcrumbs'][] = ['label' => 'Doctores', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_doctor, 'url' => ['view', 'id' => $model->id_doctor]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="ent-doctores-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
