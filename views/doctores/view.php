<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\EntDoctores */

$this->title = $model->id_doctor;
$this->params['breadcrumbs'][] = ['label' => 'Doctores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ent-doctores-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id_doctor], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id_doctor], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Estas seguro de que quieres eliminar este elemento?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_doctor',
            'txt_nombre',
            'txt_apellido_paterno',
            'txt_email:email',
            'txt_password',
            //'b_habilitado',
        ],
    ]) ?>

</div>
