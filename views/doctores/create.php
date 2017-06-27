<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\EntDoctores */

$this->title = 'Crear Doctor';
$this->params['breadcrumbs'][] = ['label' => 'Doctores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ent-doctores-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
