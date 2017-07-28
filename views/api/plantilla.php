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
<h1>Tratamineto: <?= $tratamiento->txt_nombre_tratamiento ?></h1>
<h2>Paciente: <?= $paciente->txt_nombre_completo ?> </h2>
<h3>Email: <?= $paciente->txt_email ?> </h3>

<p>Peso: <?= $dosis->num_peso ?> </p>
<p>Estatura: <?= $dosis->num_estatura ?> </p>
<p>Proxima visita: <?= Utils::changeFormatDate($dosis->fch_proxima_visita) ?> </p>