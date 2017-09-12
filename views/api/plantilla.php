<?php

use app\models\Utils;

if (class_exists('yii\debug\Module')) {
    $this->off(\yii\web\View::EVENT_END_BODY, [\yii\debug\Module::getInstance(), 'renderToolbar']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Consentimiento Isotretinoina</title>

    <!-- Estilos-->
    <style>
        body {
            padding: 20px;
            padding-bottom: 0px;
            margin-bottom: 0px;
        }

        h1,
        h3,
        b,
        .number,
        .contenedor-nota p {
            color: #006298
        }

        p {
            color: #6D6E70;
        }

        header {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        ul li b {
            color: black;
        }

        .contenedor-nota {
            background-color: #D0ECEC;
            padding: 30px;
            padding-top: 5px;
            padding-bottom: 5px;
            border-radius: 8px;
        }

        .seccion-1,
        .seccion-2 {
            width: 45%;
            float: left;
            margin-top: 25px;
            padding-right: 5%;
        }

        .number {
            float: left;
            height: 30px;
            width: 30px;
            line-height: 30px;
            -moz-border-radius: 50%;
            border-radius: 50%;
            border: 2px solid #00B1B1;
            text-align: center;
            font-size: 1em;
            margin-right: 15px;
        }

        .seccion p {
            margin: 0;
        }

        .container-number {
            overflow: auto;
            margin-top: 10px;
        }

        .text-center {
            text-align: center;
        }

        .clear {
            clear: both;
        }
        .sign-line{
            margin-top: 20px;
            border-bottom: 1px solid #6D6E70;
            margin-bottom: 30px;
            min-height: 20px;
        }
        .bar-solid, .logo-footer{
            min-height: 50px;
            float:left;
        }
        .bar-solid{
            width: 80%;
            background: #006298;
        }

        .logo-footer{
            width: 20%;
        }
    </style>

</head>

<body>

    <header>
        <h1>CONSENTIEMIENTO INFORMADO</h1>
        <p>Para pacientes bajo tratamiento con <b>isotretinoína</b></p>
    </header>

    <div class="text-right">
        <p>México
            <u><?=$diaNombre?></u> a
            <u><?=$diaNumero?></u> de
            <u><?=$mes?></u> 20
            <u><?=$anio?></u>
        </p>
    </div>

    <div>
        <p>Por medio del presente CONSENTIMIENTO INFORMADO, se autoriza y acepta que el Médico Especialista
            <u><?=$nombreDoctor?></u>, con cédula profesional
            <u><?=$cedulaDoctor?></u> prescriba a
            <u><?=$nombrePaciente?></u> <b>isotretinoína</b>.</p>
    </div>

    <h3>Información y Advertencias Importantes</h3>
    <div>
        <p>La isotretinoína puede producir graves malformaciones fetales si se toma, incluso en pequeñas cantidades, durante
            el embarazo.</p>
        <p>El riesto de tener un hijo con una malformación grave es extremadamente alto:</p>
        <ul>
            <li><b>Si está embarazada al comenzar este tratamiento</b></li>
            <li>Si se <b>embaraza</b> durante el tratamiento</li>
            <li>Si se <b>embaraza</b> 1 mes después de finalizar el tratamiento
        </ul>
    </div>

    <h3>Debe ser llenado y firmado por la paciente, padre o tutor</h3>
    <div class="contenedor-nota">
        <p>
            Lea detenidamente cada uno de los siguientes puntos, y firme este formulario de consentimiento informado, si ha entendido
            completamente cada uno de los puntos y acepta seguir las instrucciones del especialista. En caso necesario, el
            padre o tutor debe firmar el formulario de consentimiento después de haberlo leído y entendido en su totalidad.
            No firme este consentimiento ni comience el tratamiento si hay algo que no entiende sobre la información que
            ha recibido acerca del uso de este medicamento.
        </p>
    </div>

    <div>
        <div class="seccion seccion-1">
            <div class="container-number">
                <div class="number">
                    <b>1</b>
                </div>
                <p>
                    Mi médico especialista me ha advertido sobre el riego de graves daños a mi futuro hijo(a) si estoy o voy a quedar embarazada
                    mientras tomo isotretinoína.
                </p>
            </div>

            <div class="container-number">
                <div class="number">
                    <b>2</b>
                </div>
                <p>
                    Entiendo que debo esperar hasta el segundo o tercer día de mi próximo periodo menstrual antes de empezar a tomar isotretinoína.
                </p>
            </div>

            <div class="container-number">
                <div class="number">
                    <b>3</b>
                </div>
                <p>
                    Entiendo que debo contar con una prueba de embarazo negativa antes de empezar mi tratamiento.
                </p>
            </div>

            <div class="container-number">
                <div class="number">
                    <b>4</b>
                </div>
                <p>
                    He sido informado que debo utilizar métodos de anticonceptivos eficaces y complementarios 1 mes antes de comenzar el tratamiento
                    durante y <b>1 mes después de
                    haber finalizado</b>. Deberé utilizar un método fiable de control de natalidad incluso aunque piense
                    que no puedo quedar embarazada.
                </p>
            </div>

            <div class="container-number">
                <div class="number">
                    <b>5</b>
                </div>
                <p>
                    Entiendo que dado que todos los métodos anticonceptivos pueden fallar, debo repetir la prueba de embarazo mensualmente durante
                    el tratamiento y 1 mes posterior a la suspensión del mismo.
                </p>
            </div>

        </div>
        <div class="seccion seccion-2">
            <div class="container-number">
                <div class="number">
                    <b>6</b>
                </div>
                <p>
                    Entiendo que determinados medicamentos pueden disminuir el efecto de los métodos anticonceptivos hormonales. Por ello, informare
                    a mi médico especialista sobre cualquier medicamento que esté tomando o pretenda tomar, durante el tratamiento
                    con isotretinoína.
                </p>
            </div>
            <div class="container-number">
                <div class="number">
                    <b>7</b>
                </div>
                <p>
                    Si me embarazo durante el tratamiento o al siguiente mes después de haberlo concluido, debo informar inmediatamente de ello
                    a mi médico especialista.
                </p>
            </div>
            <div class="container-number">
                <div class="number">
                    <b>8</b>
                </div>
                <p>
                    Entiendo que por ningún motivo debo dar o compartir mi medicamento con otras personas.
                </p>
            </div>
            <div class="container-number">
                <div class="number">
                    <b>9</b>
                </div>
                <p>
                    Entiendo que no debo ingerir bebidas alcohólicas, ya que pueden potencializar los efectos de la isotretinoína y reflejarse
                    en un incremento de las reacciones adversas.
                </p>
            </div>
        </div>
    </div>

    <div class="clear"></div>
    <br>
    <div class="contenedor-nota text-center">
        <p>Acepto recibir el tratamiento con isotretinoína.
            <br>Acepto el riego y las medidas de precaución que debo seguir y que mi médico especialista me han explicado
            a detalle.<br> Mi médico especialista ha contestado a todas mis preguntas sobre mi tratamiento.</p>
    </div>

    <div>
        <div class="seccion seccion-1">
            <p>Nombre del paciente:</p>
            <div class="sign-line">
            <?=$nombrePaciente?>
            </div>

            <p>Firma del paciente:</p>
            <div class="sign-line">
                
            </div>
        </div>

        <div class="seccion seccion-2">
                <p>Nombre del padre/tutor (en caso necesario):</p>
                <div class="sign-line"></div>
                <p>Nombre del padre/tutor (en caso necesario):</p>
                <div class="sign-line"></div>
        </div>
    </div>
    <div class="clear"></div>

    <footer>
        <div class="bar-solid">

        </div>
        <div class="logo-footer">

        </div>
    </footer>
    <div class="clear"></div>

</body>

</html>
