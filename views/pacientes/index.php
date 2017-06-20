<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Utils;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EntPacientesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pacientes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ent-pacientes-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Crear Paciente', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id_paciente',
            'txt_nombre',
            'txt_apellido_paterno',
            'txt_apellido_materno',
            'txt_email:email',
            'txt_telefono_contacto',
            [
                'attribute' => 'fch_nacimiento',
                'value' => function($model){
                    return Utils::changeFormatDate($model->fch_nacimiento);
                }
            ],
            // 'b_habilitado',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
