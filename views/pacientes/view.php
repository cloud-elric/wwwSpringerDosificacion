<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Utils;

/* @var $this yii\web\View */
/* @var $model app\models\EntPacientes */

$this->title = $model->id_paciente;
$this->params['breadcrumbs'][] = ['label' => 'Pacientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ent-pacientes-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id_paciente], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id_paciente], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Estas seguro de que quieres eliminar al paciente?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_paciente',
            'txt_nombre',
            'txt_apellido_paterno',
            'txt_apellido_materno',
            'txt_email:email',
            'txt_telefono_contacto',
            [
                'attribute' => 'fch_nacimiento',
                'value' => Utils::changeFormatDate($model->fch_nacimiento)
            ]
            //'b_habilitado',
        ],
    ]) ?>

</div>
