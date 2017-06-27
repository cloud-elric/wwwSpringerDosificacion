<?php

use app\models\Utils;

if (class_exists('yii\debug\Module')) {
    $this->off(\yii\web\View::EVENT_END_BODY, [\yii\debug\Module::getInstance(), 'renderToolbar']);
}
?>
<style>
    h1{
        color:red;
    }
</style>
<h1>Paciente: <?= $paciente->txt_nombre . ' ' . $paciente->txt_apellido_paterno . ' ' . $paciente->txt_apellido_materno ?> </h1>
<h2>Email: <?= $paciente->txt_email ?> </h2>

<p>Peso: <?= $dosis->num_peso ?> </p>
<p>Estatura: <?= $dosis->num_estatura ?> </p>
<p>Proxima visita: <?= Utils::changeFormatDate($dosis->fch_proxima_visita) ?> </p>