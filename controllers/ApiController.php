<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\EntDoctores;
use app\models\EntPacientes;
use yii\data\ActiveDataProvider;
use app\models\Utils;
use app\models\EntDosis;
use Spipu\Html2Pdf\Html2Pdf;

class ApiController extends Controller
{
    public $enableCsrfValidation = false;
    
    /**
    * Validación para registrar al usuario (doctor)
    */
    public function actionLogin(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $respuesta['error'] = true;
        $respuesta['message'] = 'Faltan datos';

        if(isset($_REQUEST['usuario']) && isset($_REQUEST['password'])){
            $usuario = $_REQUEST['usuario'];
            $password = $_REQUEST['password'];

            if($doctor = EntDoctores::getDoctor($usuario, $password)){
               $respuesta ['error'] = false;
               $respuesta ['message'] = 'Doctor encontrado';
               $respuesta ['doctor'] = $doctor;
            }else{
                $respuesta ['error'] = true;
                $respuesta ['message'] = 'Email y/o contraseña incorrecto(s)';
               
            }
        }

        return $respuesta;

    }

        
    public function actionCrearDoctor(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $respuesta['error'] = true;
        $respuesta['message'] = 'Faltan datos';
        $doctor =  new EntDoctores();

        if(isset($_REQUEST['nombre']) && isset($_REQUEST['apellido']) && isset($_REQUEST['email']) && isset($_REQUEST['password'])){
            $doctor->txt_nombre = $_REQUEST['nombre'];
            $doctor->txt_apellido_paterno = $_REQUEST['apellido'];
            $doctor->txt_email = $_REQUEST['email'];
            $doctor->txt_password = $_REQUEST['password'];
            if($doctor->save()){
                $respuesta ['error'] = false;
                $respuesta ['message'] = 'Doctor guardado';
                $respuesta ['doctor'] = $doctor;
            }else{
                $respuesta ['error'] = true;
                $respuesta ['message'] = 'Datos invalidos';
                $respuesta['errosDoc'] = $doctor->errors;
            }  
        }

        return $respuesta;
    }

    public function actionLeerDoctor($page = 0){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $respuesta['error'] = true;
        $respuesta['message'] = 'Faltan datos';
        //$doctores = EntDoctores::find()->where(['b_habilitado'=>1])->all();

        $dataProvider = new ActiveDataProvider([
			'query' => EntDoctores::find()->where(['b_habilitado'=>1]),
			'sort'=> ['defaultOrder' => ['txt_nombre'=>'asc']],
			'pagination' => [
				'pageSize' => 20,
				'page' => $page
			]
		]);

        if($dataProvider->getModels()){
            $respuesta ['error'] = false;
            $respuesta ['message'] = 'Doctores mostrados';
            $respuesta ['doctor'] = $dataProvider->getModels();
        }else{
            $respuesta ['error'] = true;
            $respuesta ['message'] = 'No hay datos';
        }

        return $respuesta;
    }

    public function actionActualizarDoctor($idDoctor = 0, $nombre = null, $apellido = null, $email = null, $password = null){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $respuesta['error'] = true;
        $respuesta['message'] = 'Faltan datos';

        if( (isset($_REQUEST['idDoctor']) && isset($_REQUEST['nombre'])) || (isset($_REQUEST['idDoctor']) && isset($_REQUEST['apellido'])) || 
        (isset($_REQUEST['idDoctor']) && isset($_REQUEST['email'])) || (isset($_REQUEST['idDoctor']) || isset($_REQUEST['password'])) ){
            $id = $_REQUEST['idDoctor'];
            $doctor = EntDoctores::find()->where(['id_doctor'=>$id])->andWhere(['b_habilitado'=>1])->one();           

            if($doctor){
                if(isset($_REQUEST['nombre'])){
                    $doctor->txt_nombre = $_REQUEST['nombre'];
                }
                if(isset($_REQUEST['apellido'])){
                    $doctor->txt_apellido_paterno = $_REQUEST['apellido'];
                }
                if(isset($_REQUEST['email'])){
                    $doctor->txt_email = $_REQUEST['email'];
                }
                if(isset($_REQUEST['password'])){
                    $doctor->txt_password = $_REQUEST['password'];
                }

                if($doctor->save()){
                    $respuesta ['error'] = false;
                    $respuesta ['message'] = 'Doctor actualizado';
                    $respuesta ['doctor'] = $doctor;
                }else{
                    $respuesta ['error'] = true;
                    $respuesta ['message'] = 'Datos invalidos';
                    $respuesta['errosDoc'] = $doctor->errors;
                }
                
            }else{
                $respuesta ['error'] = true;
                $respuesta ['message'] = 'No hay datos';
            }

        }else{
            $respuesta ['error'] = true;
            $respuesta ['message'] = 'No hay datos';
        }

        return $respuesta;
    }

    public function actionEliminarDoctor($idDoctor = 0){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $respuesta['error'] = true;
        $respuesta['message'] = 'Faltan datos';

        if(isset($_REQUEST['idDoctor'])){
            $id = $_REQUEST['idDoctor'];
            $doctor = EntDoctores::find()->where(['id_doctor'=>$id])->andWhere(['b_habilitado'=>1])->one();

            if($doctor){
                $doctor->b_habilitado = 0;
                if($doctor->save()){
                    $respuesta ['error'] = false;
                    $respuesta ['message'] = 'Doctor eliminado';
                }else{
                    $respuesta ['error'] = true;
                    $respuesta ['message'] = 'Datos invalidos';
                    $respuesta['errosDoc'] = $doctor->errors;
                }
            }else{
                $respuesta ['error'] = true;
                $respuesta ['message'] = 'No hay datos';
            }

        }else{
            $respuesta ['error'] = true;
            $respuesta ['message'] = 'No hay datos por get';
        }

        return $respuesta;
    }

    public function actionCrearPaciente(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $respuesta['error'] = true;
        $respuesta['message'] = 'Faltan datos';
        $paciente = new EntPacientes;

        if(isset($_REQUEST['nombre']) && isset($_REQUEST['apellidoPat']) && isset($_REQUEST['apellidoMat']) && isset($_REQUEST['email']) && isset($_REQUEST['telefono']) && isset($_REQUEST['nacimiento'])){
            //Cambio de formato de fecha ej. "2/06/2000" a "2000-06-02" para guadarlo en la BD
            $fecha = str_replace('/', '-', $_REQUEST['nacimiento']);
            $fecha = date('Y-m-d', strtotime($fecha));

            $paciente->txt_nombre = $_REQUEST['nombre'];
            $paciente->txt_apellido_paterno = $_REQUEST['apellidoPat'];
            $paciente->txt_apellido_materno = $_REQUEST['apellidoMat'];
            $paciente->txt_email = $_REQUEST['email'];
            $paciente->txt_telefono_contacto = $_REQUEST['telefono'];
            $paciente->fch_nacimiento = $fecha;
            if($paciente->save()){
                $respuesta ['error'] = false;
                $respuesta ['message'] = 'Paciente guardado';
                $respuesta ['paciente'] = $paciente;
            }else{
                $respuesta ['error'] = true;
                $respuesta ['message'] = 'Datos invalidos';
                $respuesta['errosPac'] = $paciente->errors;
            }
        }else{
            $respuesta ['error'] = true;
            $respuesta ['message'] = 'No hay datos';
        }

        return $respuesta;
    }

    public function actionLeerPaciente($page = 0){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $respuesta['error'] = true;
        $respuesta['message'] = 'Faltan datos';
        //$pacientes = EntPacientes::find()->where(['b_habilitado'=>1])->all();
        
        $dataProvider = new ActiveDataProvider([
			'query' => EntPacientes::find()->where(['b_habilitado'=>1]),
			'sort'=> ['defaultOrder' => ['txt_nombre'=>'asc']],
			'pagination' => [
				'pageSize' => 20,
				'page' => $page
			]
		]);

        if($dataProvider->getModels()){
            $respuesta ['error'] = false;
            $respuesta ['message'] = 'Pacientes mostrados';
            $respuesta ['pacientes'] = $dataProvider->getModels();
        }else{
            $respuesta ['error'] = true;
            $respuesta ['message'] = 'No hay datos';
        }
        
        return $respuesta;
    }

    public function actionActualizarPaciente($idPaciente = 0, $nombre = null, $apellidoPat = null, $apellidoMat = null, $email = null, $telefono = null, $nacimiento = null){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $respuesta['error'] = true;
        $respuesta['message'] = 'Faltan datos';

        if( (isset($_REQUEST['idPaciente']) && isset($_REQUEST['nombre'])) || (isset($_REQUEST['idPaciente']) && isset($_REQUEST['apellidoPat'])) || (isset($_REQUEST['idPaciente']) && isset($_REQUEST['apellidoMat'])) || 
        (isset($_REQUEST['idPaciente']) && isset($_REQUEST['email'])) || (isset($_REQUEST['idPaciente']) && isset($_REQUEST['telefono'])) || (isset($_REQUEST['idPaciente']) && isset($_REQUEST['nacimiento'])) ){
            $id = $_REQUEST['idPaciente'];
            $paciente = EntPacientes::find()->where(['id_paciente'=>$id])->andWhere(['b_habilitado'=>1])->one();           

            if($paciente){
                if(isset($_REQUEST['nombre'])){
                    $paciente->txt_nombre = $_REQUEST['nombre'];
                }
                if(isset($_REQUEST['apellidoPat'])){
                    $paciente->txt_apellido_paterno = $_REQUEST['apellidoPat'];
                }
                if(isset($_REQUEST['apellidoMat'])){
                    $paciente->txt_apellido_materno = $_REQUEST['apellidoMat'];
                }
                if(isset($_REQUEST['email'])){
                    $paciente->txt_email = $_REQUEST['email'];
                }
                if(isset($_REQUEST['telefono'])){
                    $paciente->txt_telefono_contacto = $_REQUEST['telefono'];
                }
                if(isset($_REQUEST['nacimiento'])){
                    $paciente->fch_nacimiento = $_REQUEST['nacimiento'];
                }

                if($paciente->save()){
                    $respuesta ['error'] = false;
                    $respuesta ['message'] = 'Paciente actualizado';
                    $respuesta ['paciente'] = $paciente;
                }else{
                    $respuesta ['error'] = true;
                    $respuesta ['message'] = 'Datos invalidos';
                    $respuesta['errosPac'] = $paciente->errors;
                }
            }else{
                $respuesta ['error'] = true;
                $respuesta ['message'] = 'Paciente no encontrado';
            }  
        }else{
            $respuesta ['error'] = true;
            $respuesta ['message'] = 'No hay datos';
        }

        return $respuesta;
    }
    
    public function actionEliminarPaciente($idPaciente = 0){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $respuesta['error'] = true;
        $respuesta['message'] = 'Faltan datos';

        if(isset($_REQUEST['idPaciente'])){
            $id = $_REQUEST['idPaciente'];
            $paciente = EntPacientes::find()->where(['id_paciente'=>$id])->andWhere(['b_habilitado'=>1])->one();

            if($paciente){
                $paciente->b_habilitado = 0;
                if($paciente->save()){
                    $respuesta ['error'] = false;
                    $respuesta ['message'] = 'Paciente eliminado';
                }else{
                    $respuesta ['error'] = true;
                    $respuesta ['message'] = 'Datos invalidos';
                    $respuesta['errosPac'] = $paciente->errors;
                }
            }else{
                $respuesta ['error'] = true;
                $respuesta ['message'] = 'No hay datos';
            }

        }else{
            $respuesta ['error'] = true;
            $respuesta ['message'] = 'No hay datos';
        }

        return $respuesta;
    }

    public function actionGetPaciente(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $respuesta['error'] = true;
        $respuesta['message'] = 'No existe paciente';
        $idPaciente = 0;

        if(isset($_REQUEST['idPaciente'])){
            $idPaciente = $_REQUEST['idPaciente'];

            $paciente = EntPacientes::find()->where(['id_paciente'=>$idPaciente])->one();

            if($paciente){
                $respuesta['error'] = false;
                $respuesta['message'] = 'Paciente encontrado';
                $respuesta['paciente'] = $paciente;
            }
        }

        return $respuesta;
    }

    public function actionBuscarPaciente(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $respuesta['error'] = true;
        $respuesta['message'] = 'Faltan datos';
        
        if( (isset($_REQUEST['nombre']) || isset($_REQUEST['apPaterno']) || isset($_REQUEST['apMaterno']) || isset($_REQUEST['email']) || isset($_REQUEST['tel']) || isset($_REQUEST['fecha'])) && isset($_REQUEST['page']) ){
            $nombre = $_REQUEST['nombre'];
            $apPaterno = $_REQUEST['apPaterno'];
            $apMaterno = $_REQUEST['apMaterno'];
            $email = $_REQUEST['email'];
            $tel = $_REQUEST['tel'];
            if($_REQUEST['fecha']){
                $fecha = Utils::changeFormatDateInput($_REQUEST['fecha']);
            }else{
                $fecha = $_REQUEST['fecha'];
            }
            $page = $_REQUEST['page'];

            $query = EntPacientes::find()->where(['b_habilitado'=>1]);
            // add conditions that should always apply here
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'sort'=> ['defaultOrder' => ['txt_nombre'=>'asc']],
                'pagination' => [
                    'pageSize' => 3,
                    'page' => $page
                ]
            ]);

            // grid filtering conditions
            $query->andFilterWhere([
                //'id_paciente' => $query->id_paciente,
                'fch_nacimiento' => $fecha,
                //'b_habilitado' => $query->b_habilitado,
            ]);

            $query->andFilterWhere(['like', 'txt_nombre', $nombre])
                ->andFilterWhere(['like', 'txt_apellido_paterno', $apPaterno])
                ->andFilterWhere(['like', 'txt_apellido_materno', $apMaterno])
                ->andFilterWhere(['like', 'txt_email', $email])
                ->andFilterWhere(['like', 'txt_telefono_contacto', $tel]);
            
            if($dataProvider->getModels()){
                $respuesta ['error'] = false;
                $respuesta ['message'] = 'Paciente mostrado';
                $respuesta ['paciente'] = $dataProvider->getModels();
                $respuesta ['request'] = $_REQUEST;
            }else{
                $respuesta ['error'] = true;
                $respuesta ['message'] = 'No hay datos';
            }
        }

        return $respuesta;
    }

    public function actionMandarPassword(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $respuesta['error'] = true;
        $respuesta['message'] = 'No existe doctor';

        if( isset($_REQUEST['correo']) ){
            $correo = $_REQUEST['correo'];
            $doctor = EntDoctores::find()->where(['txt_email'=>$correo])->one();
            if($doctor){
                $utils = new Utils();
                $parametrosEmail = [
                    'nombre' => $doctor->txt_nombre,
                    'apellido' => $doctor->txt_apellido_paterno,
                    'password'=>$doctor->txt_password,
                    'email'=>$doctor->txt_email,
                ];
                if($utils->sendCorreoPassword ( $doctor->txt_email, $parametrosEmail )){
                    $respuesta['error'] = false;
                    $respuesta['message'] = 'Correo enviado correctamente';
                }else{
                    $respuesta['error'] = true;
                    $respuesta['message'] = 'Correo no enviado';
                }
            }else{
                $respuesta['error'] = true;
                $respuesta['message'] = 'Email no registrado';
            }
        }

        return $respuesta;
    }

    public function actionGetDosisPaciente(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $respuesta['error'] = true;
        $respuesta['message'] = 'No hay dosis asignada';

        if( isset($_REQUEST['idPaciente']) ){
            $id = $_REQUEST['idPaciente'];
            $dosis = EntDosis::find()->where(['id_paciente'=>$id])->all();

            if($dosis){
                $respuesta['error'] = false;
                $respuesta['message'] = 'Datos encontrados';
                $respuesta['dosis'] = $dosis;
            }else{
                $respuesta['error'] = true;
                $respuesta['message'] = 'No se encontro paciente';
            }
        }

        return $respuesta;
    }

    public function actionGenerarPdf(){
        require __DIR__.'\..\vendor\autoload.php';
        $utils = new Utils();
        $respuesta['error'] = true;
        $respuesta['message'] = 'Faltan datos';

        if(isset($_REQUEST['id_doctor']) && isset($_REQUEST['id_paciente']) && isset($_REQUEST['num_peso']) && isset($_REQUEST['num_estatura']) && isset($_REQUEST['fch_visita']) ){
            $dosis = new EntDosis();
            $dosis->id_doctor = $_REQUEST['id_doctor'];
            $dosis->id_paciente = $_REQUEST['id_paciente'];
            $dosis->num_peso = $_REQUEST['num_peso'];
            $dosis->num_estatura = $_REQUEST['num_estatura'];
            $dosis->txt_token = $utils->generateToken();
            $dosis->fch_proxima_visita = Utils::changeFormatDateInput($_REQUEST['fch_visita']);

            $paciente = EntPacientes::find()->where(['id_paciente'=>$_REQUEST['id_paciente']])->one();
            $doctor = EntDoctores::find()->where(['id_doctor'=>$_REQUEST['id_doctor']])->one();

            if($dosis->save() && $doctor && $paciente){
                $html2pdf = new Html2Pdf();
                $vistaHtml = $this->renderAjax('plantilla', [
                    'dosis' => $dosis,
                    'paciente' => $paciente
                ]);
                
                $carpeta = 'pdfDosis/'. $paciente->txt_token;
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }

                $html2pdf->writeHTML($vistaHtml);
                $html2pdf->output($carpeta . '/' . $dosis->txt_token . '.pdf', 'F');
                
                /* *********CORREO CON ARCHIVO ADJUNTO**********  */

                $pathArchivo = $carpeta . '/' . $dosis->txt_token . '.pdf';
                $utils = new Utils();
                $parametrosEmail = [
                    'nombre' => $doctor->txt_nombre,
                    'apellido' => $doctor->txt_apellido_paterno,
                    'password'=>$doctor->txt_password,
                    'email'=>$doctor->txt_email,
                ];
                if($utils->sendCorreoArchivo($doctor->txt_email, $parametrosEmail, $pathArchivo)){
                    $respuesta['error'] = false;
                    $respuesta['message'] = 'Email enviado correctamente';                   
                }else{
                    $respuesta['error'] = true;
                    $respuesta['message'] = 'Error al enviar email';
                }
            }else{
                $respuesta['error'] = true;
                $respuesta['message'] = 'Error al guardar o no existe paciente o no existe doctor';
            }
        }
        return $respuesta;
    }

    public function actionDownloadPdf(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $respuesta['error'] = true;
        $respuesta['message'] = 'Datos incorrectos';

        if(isset($_REQUEST['token'])){
            $dosis = EntDosis::find()->where(['txt_token'=>$_REQUEST['token']])->one();
            $paciente = $dosis->idPaciente;

            $path = Yii::$app->homeUrl . 'pdfDosis/';
            $file = 'pdfDosis/' . $paciente->txt_token . '/' . $_REQUEST['token'] . '.pdf';
            
            header("Content-Disposition: attachment; filename=" . urlencode($file));   
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Description: File Transfer");            
            header("Content-Length: " . filesize($file));
            flush(); // this doesn't really matter.
            $fp = fopen($file, "r");
            while (!feof($fp))
            {
                echo fread($fp, 65536);
                flush(); // this is essential for large downloads
            } 
            fclose($fp);
        }else{
            return $respuesta;
        }
    }
}
