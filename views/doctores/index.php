<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EntDoctoresSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Doctores';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ent-doctores-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Crear Doctor', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_doctor',
            'txt_nombre',
            'txt_apellido_paterno',
            'txt_email:email',
            'txt_password',
            // 'b_habilitado',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
