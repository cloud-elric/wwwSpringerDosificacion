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
use app\models\CatClaves;
use Spipu\Html2Pdf\Html2Pdf;
use app\models\EntTratamiento;
use app\models\RelPacienteAviso;


class ApiController extends Controller
{
    public $enableCsrfValidation = false;
    //Variable para verificar si se quere seguridad en los servicios
    private $seguridad = true;

    public function beforeAction($action){

        if($this->seguridad == true){
            if($action->id != "login" || $action->id != "mandar-password" || $action->id != "crear-doctor")
            {
                if(isset($_REQUEST['txt_token_seguridad'])){
                    $doctor = EntDoctores::find()->where(['txt_token_seguridad'=>$_REQUEST['txt_token_seguridad']])->one();
                    if($doctor){
                        return parent::beforeAction($action);         
                    }
                }
            }
        }else{
            return parent::beforeAction($action);
        }
        echo 'No tienes permiso para acceder a esa info.';
        exit();
   }
    
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
        $utils = new Utils();

        if(isset($_REQUEST['nombre']) && isset($_REQUEST['apellido']) && isset($_REQUEST['email']) && isset($_REQUEST['password']) && isset($_REQUEST['clave'])){
            $doctor->txt_nombre = $_REQUEST['nombre'];
            $doctor->txt_apellido_paterno = $_REQUEST['apellido'];
            $doctor->txt_email = $_REQUEST['email'];
            $doctor->txt_password = $_REQUEST['password'];
            $doctor->txt_token = $utils->generateToken();
            if(isset($_REQUEST['cedula']))
                $doctor->txt_cedula = $_REQUEST['cedula'];

            $clave = CatClaves::find()->where(['txt_clave'=>$_REQUEST['clave']])->one();
            if($clave && $clave->b_usado == 0){
                $clave->b_usado = 1;
                $doctor->id_clave = $clave->id_clave;   
            }else{
                $respuesta ['error'] = true;
                $respuesta ['message'] = 'Clave invalida';

                return $respuesta;
            }

            if($doctor->save()){
                if($clave->save()){
                    $respuesta ['error'] = false;
                    $respuesta ['message'] = 'Doctor guardado';
                    $respuesta ['doctor'] = $doctor;
                }else{
                    $respuesta ['error'] = true;
                    $respuesta ['message'] = 'Datos invalidos';
                    $respuesta['errosClave'] = $clave->errors;
                }
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

    public function actionActualizarDoctor(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $respuesta['error'] = true;
        $respuesta['message'] = 'Faltan datos';

        if( (isset($_REQUEST['idDoctor']) && isset($_REQUEST['nombre'])) || (isset($_REQUEST['idDoctor']) && isset($_REQUEST['apellido'])) || 
        (isset($_REQUEST['idDoctor']) && isset($_REQUEST['email'])) || (isset($_REQUEST['idDoctor']) || isset($_REQUEST['password'])) ||
        (isset($_REQUEST['idDoctor']) || isset($_REQUEST['cedula'])) ){
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
                if(isset($_REQUEST['cedula'])){
                    $doctor->txt_cedula = $_REQUEST['cedula'];
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

    public function actionEliminarDoctor(){
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
        $utils = new Utils();        

        if( (isset($_REQUEST['nombre']) && isset($_REQUEST['apPaterno']) && isset($_REQUEST['email']) && isset($_REQUEST['edad'])  && isset($_REQUEST['sexo']) && isset($_REQUEST['id_doctor'])) ||
        (isset($_REQUEST['nombre']) && isset($_REQUEST['apPaterno']) && isset($_REQUEST['email']) && isset($_REQUEST['edad'])  && isset($_REQUEST['sexo']) && isset($_REQUEST['id_doctor']) && isset($_REQUEST['apMaterno'])) || 
        (isset($_REQUEST['nombre']) && isset($_REQUEST['apPaterno']) && isset($_REQUEST['email']) && isset($_REQUEST['edad'])  && isset($_REQUEST['sexo']) && isset($_REQUEST['id_doctor']) && isset($_REQUEST['telefono'])) || 
        (isset($_REQUEST['nombre']) && isset($_REQUEST['apPaterno']) && isset($_REQUEST['email']) && isset($_REQUEST['edad'])  && isset($_REQUEST['sexo']) && isset($_REQUEST['id_doctor']) && isset($_REQUEST['apMaterno']) && isset($_REQUEST['telefono'])) ){
            
            $paciente->id_doctor = $_REQUEST['id_doctor'];
            $paciente->txt_nombre = $_REQUEST['nombre'];
            $paciente->txt_apellido_paterno = $_REQUEST['apPaterno'];
            if(isset($_REQUEST['apMaterno'])){
                $paciente->txt_apellido_materno = $_REQUEST['apMaterno'];
            }
            $paciente->txt_email = $_REQUEST['email'];
            if(isset($_REQUEST['telefono'])){
                $paciente->txt_telefono_contacto = $_REQUEST['telefono'];
            }
            $paciente->txt_token = $utils->generateToken();
            $paciente->txt_sexo = $_REQUEST['sexo'];
            $paciente->num_edad = $_REQUEST['edad'];
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

    public function actionActualizarPaciente(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $respuesta['error'] = true;
        $respuesta['message'] = 'Faltan datos';

        if( (isset($_REQUEST['idPaciente']) && isset($_REQUEST['nombre'])) || (isset($_REQUEST['idPaciente']) && isset($_REQUEST['apPaterno'])) || (isset($_REQUEST['idPaciente']) && isset($_REQUEST['email'])) ||
        (isset($_REQUEST['idPaciente']) && isset($_REQUEST['apMaterno'])) || (isset($_REQUEST['idPaciente']) && isset($_REQUEST['telefono'])) || (isset($_REQUEST['idPaciente']) && isset($_REQUEST['edad'])) || 
        (isset($_REQUEST['idPaciente']) && isset($_REQUEST['sexo'])) ){
            $id = $_REQUEST['idPaciente'];
            $paciente = EntPacientes::find()->where(['id_paciente'=>$id])->andWhere(['b_habilitado'=>1])->one();           

            if($paciente){
                if(isset($_REQUEST['nombre'])){
                    $paciente->txt_nombre = $_REQUEST['nombre'];
                }
                if(isset($_REQUEST['apPaterno'])){
                    $paciente->txt_apellido_paterno = $_REQUEST['apPaterno'];
                }
                if(isset($_REQUEST['apMaterno'])){
                    $paciente->txt_apellido_materno = $_REQUEST['apMaterno'];
                }
                if(isset($_REQUEST['email'])){
                    $paciente->txt_email = $_REQUEST['email'];
                }
                if(isset($_REQUEST['telefono'])){
                    $paciente->txt_telefono_contacto = $_REQUEST['telefono'];
                }
                if(isset($_REQUEST['edad'])){
                    //$fecha = str_replace('/', '-', $_REQUEST['nacimiento']);
                    //$fecha = date('Y-m-d', strtotime($fecha));
                    //$paciente->fch_nacimiento = $fecha;
                    $paciente->num_edad = $_REQUEST['edad'];
                }
                if(isset($_REQUEST['sexo'])){
                    $paciente->txt_sexo = $_REQUEST['sexo'];
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
    
    public function actionEliminarPaciente(){
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

            $paciente = EntPacientes::find()->where(['id_paciente'=>$idPaciente])->andWhere(['b_habilitado'=>1])->one();

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

        $nombre = null;
        $apellido = null;
        $email = null;
        $tel = null;
        $edad = null;
        $sexo = null;
        $page = null;
        $query = EntPacientes::find()->where(['b_habilitado'=>1]);        
        
        if(isset($_REQUEST['nombre']) && isset($_REQUEST['txt_token_seguridad'])){
            $nombre = $_REQUEST['nombre'];
            $query->andFilterWhere(['like', 'txt_nombre', $nombre]);            
        }
        if(isset($_REQUEST['apPaterno']) && isset($_REQUEST['txt_token_seguridad'])){
            $apellido = $_REQUEST['apPaterno'];
            $query->andFilterWhere(['like', 'txt_apellido_paterno', $apellido]);            
        }
        if(isset($_REQUEST['email'])){                
            $email = $_REQUEST['email'];
            $query->andFilterWhere(['like', 'txt_email', $email]);            
        }
        if(isset($_REQUEST['edad'])){
            $edad = $_REQUEST['edad'];
            $query->andFilterWhere(['num_edad'=>$edad]);
        }
        if(isset($_REQUEST['sexo'])){                
            $sexo = $_REQUEST['sexo'];
            $query->andFilterWhere(['like', 'txt_sexo', $sexo]);
        }
        if(isset($_REQUEST['page'])){                
            $page = $_REQUEST['page'];
        }else{
            return $respuesta;
        }
        
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['txt_nombre'=>'asc']],
            'pagination' => [
                'pageSize' => 50,
                'page' => $page
            ]
        ]);
        
        if($dataProvider->getModels()){
            $respuesta ['error'] = false;
            $respuesta ['message'] = 'Paciente mostrado';
            $respuesta ['paciente'] = $dataProvider->getModels();
            $respuesta ['request'] = $_REQUEST;
        }else{
            $respuesta ['error'] = true;
            $respuesta ['message'] = 'No hay datos';
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

        if( isset($_REQUEST['idTratamiento']) ){
            $id = $_REQUEST['idTratamiento'];
            $dosis = EntDosis::find()->where(['id_tratamiento'=>$id])->all();

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
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $utils = new Utils();
        $respuesta['error'] = true;
        $respuesta['message'] = 'Faltan datos';

        if( (isset($_REQUEST['id_tratamiento']) && isset($_REQUEST['num_peso']) && isset($_REQUEST['id_presentacion']) && isset($_REQUEST['dosisSugerida']) && isset($_REQUEST['dosisAcumulada']) && isset($_REQUEST['dosisDiaria']) && isset($_REQUEST['tiempoTratamiento']) && isset($_REQUEST['diasTratamiento'])) || /*Solo los campos requeridos*/
        (isset($_REQUEST['id_tratamiento']) && isset($_REQUEST['num_peso']) && isset($_REQUEST['id_presentacion']) && isset($_REQUEST['dosisSugerida']) && isset($_REQUEST['dosisAcumulada']) && isset($_REQUEST['dosisDiaria']) && isset($_REQUEST['tiempoTratamiento']) && isset($_REQUEST['diasTratamiento']) && isset($_REQUEST['num_estatura'])) || /*Campos requeridos y num_estatura*/
        (isset($_REQUEST['id_tratamiento']) && isset($_REQUEST['num_peso']) && isset($_REQUEST['id_presentacion']) && isset($_REQUEST['dosisSugerida']) && isset($_REQUEST['dosisAcumulada']) && isset($_REQUEST['dosisDiaria']) && isset($_REQUEST['tiempoTratamiento']) && isset($_REQUEST['diasTratamiento']) && isset($_REQUEST['fch_visita'])) || /*Campos requeridos y fch_visita*/
        (isset($_REQUEST['id_tratamiento']) && isset($_REQUEST['num_peso']) && isset($_REQUEST['id_presentacion']) && isset($_REQUEST['dosisSugerida']) && isset($_REQUEST['dosisAcumulada']) && isset($_REQUEST['dosisDiaria']) && isset($_REQUEST['tiempoTratamiento']) && isset($_REQUEST['diasTratamiento']) && isset($_REQUEST['num_estatura']) && isset($_REQUEST['fch_visita'])) /*Todos los campos requeridos y opcionales*/ ){
            $dosis = new EntDosis();
            $tratamiento = EntTratamiento::find()->where(['id_tratamiento'=>$_REQUEST['id_tratamiento']])->one();

            $dosis->id_tratamiento = $_REQUEST['id_tratamiento'];
            $dosis->id_presentacion = $_REQUEST['id_presentacion'];
            $dosis->num_dosis_sugerida = $_REQUEST['dosisSugerida'];
            $dosis->num_dosis_acumulada = $_REQUEST['dosisAcumulada'];
            $dosis->num_dosis_diaria = $_REQUEST['dosisDiaria'];
            $dosis->num_tiempo_tratamiento = $_REQUEST['tiempoTratamiento'];
            $dosis->num_dias_tratamiento = $_REQUEST['diasTratamiento'];
            $dosis->num_peso = $_REQUEST['num_peso'];
            if(isset($_REQUEST['num_estatura'])){
                $dosis->num_estatura = $_REQUEST['num_estatura'];
            }
            $dosis->txt_token = $utils->generateToken();
            if(isset($_REQUEST['fch_visita'])){
                $fecha = str_replace('/', '-', $_REQUEST['fch_visita']);
                $fecha = date('Y-m-d H:i:s', strtotime($fecha));
                $dosis->fch_proxima_visita = $fecha;//Utils::changeFormatDateInput($_REQUEST['fch_visita']);
            }
            $paciente = EntPacientes::find()->where(['id_paciente'=>$tratamiento->id_paciente])->one();
            $doctor = EntDoctores::find()->where(['id_doctor'=>$tratamiento->id_doctor])->one();

            if($dosis->save() && $doctor && $paciente){
                $html2pdf = new Html2Pdf();
                $vistaHtml = $this->renderAjax('plantilla', [
                    'dosis' => $dosis,
                    'paciente' => $paciente,
                    'tratamiento' => $tratamiento
                ]);
                if($this->actualizarDatosTratamiento($tratamiento, $dosis)){
                    $respuesta['message2'] = "Se actualizo el tratamiento";
                }else{
                    $respuesta['message2'] = "No se actualizo el tratamiento";                    
                }

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
                $respuesta['dosisErr'] = $dosis->errors;
            }
        }
        return $respuesta;
    }

    public function actualizarDatosTratamiento($tratamiento, $dosis){
        $tratamiento->num_dosis_sugerida = $dosis->num_dosis_sugerida;
        $tratamiento->num_dosis_acumulada = $dosis->num_dosis_acumulada;
        $tratamiento->num_dosis_diaria = $dosis->num_dosis_diaria;
        $tratamiento->num_tiempo_tratamiento = $dosis->num_tiempo_tratamiento;
        $tratamiento->num_dias_tratamiento = $dosis->num_dias_tratamiento;
        $tratamiento->num_peso = $dosis->num_peso;
        if($tratamiento->save()){
            return true;
        }else{
            return false;
        }
    }

    public function actionDownloadPdf(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $respuesta['error'] = true;
        $respuesta['message'] = 'Datos incorrectos';

        if(isset($_REQUEST['token'])){
            $dosis = EntDosis::find()->where(['txt_token'=>$_REQUEST['token']])->one();
            if($dosis){
                $tratamiento = EntTratamiento::find()->where(['id_tratamiento'=>$dosis->id_tratamiento])->one();
                $paciente = $tratamiento->idPaciente;

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
                $respuesta['error'] = true;
                $respuesta['message'] = 'No exixte archivo';
            }
        }

        return $respuesta;
    }

    /**
    * Obtiene toda la información del doctor
    */
    public function actionGetDataDoctor(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $respuesta['error'] = true;
        $respuesta['message'] = 'Faltan parametros';
        

        if( isset($_REQUEST['txt_token'])){
                $tokenDoctor = $_REQUEST['txt_token'];

                $doctor = EntDoctores::find()->where(['txt_token'=>$tokenDoctor])->one();

                if($doctor){
                    $respuesta['error'] = false;
                    $respuesta['message'] = 'Pacientes encontrados';
                    $respuesta['pacientes'] = $doctor->entPacientes;
                    $respuesta['tratamientos'] = $doctor->entTratamientos;
                    $respuesta['dosis'] = [];
                    foreach($doctor->entTratamientos as $tratamiento){
                       foreach($tratamiento->entDoses as $dosis){
                            $respuesta['dosis'][] = $dosis;
                       }
                    }

                }else{
                    $respuesta['error'] = true;
                    $respuesta['message'] = 'Doctor no encontrado';
                }

                
          
        }

        return $respuesta;
    }

    public function actionGetPacientesDoctor(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $respuesta['error'] = true;
        $respuesta['message'] = 'Faltan Datos';
        $page = null;

        if( (isset($_REQUEST['id_doctor']) && isset($_REQUEST['page'])) || isset($_REQUEST['id_doctor'])){
            if(isset($_REQUEST['page'])){
                $page = $_REQUEST['page'];
            }

            if($page != null){
                $query = EntPacientes::find()->where(['id_doctor'=>$_REQUEST['id_doctor']]);
                // add conditions that should always apply here
                $dataProvider = new ActiveDataProvider([
                    'query' => $query,
                    'sort'=> ['defaultOrder' => ['txt_nombre'=>'asc']],
                    'pagination' => [
                        'pageSize' => 5,
                        'page' => $page
                    ]
                ]);
                if($dataProvider->getModels()){
                    $respuesta['error'] = false;
                    $respuesta['message'] = 'Pacientes encontrados';
                    $respuesta['pacientes'] = $dataProvider->getModels();
                }else{
                    $respuesta['error'] = true;
                    $respuesta['message'] = 'No hay pacientes';
                }
            }else{
                $pacientes = EntPacientes::find()->where(['id_doctor'=>$_REQUEST['id_doctor']])->all();
                if($pacientes){
                    $respuesta['error'] = false;
                    $respuesta['message'] = 'Pacientes encontrados';
                    $respuesta['pacientes'] = $pacientes;
                }else{
                    $respuesta['error'] = true;
                    $respuesta['message'] = 'No hay pacientes';
                }
            }
        }

        return $respuesta;
    } 

    public function actionCrearTratamiento(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $respuesta['error'] = true;
        $respuesta['message'] = 'Faltan Datos';

        if(isset($_REQUEST['id_doctor']) && isset($_REQUEST['id_paciente']) && isset($_REQUEST['id_presentacion']) && isset($_REQUEST['txt_nombre_tratamiento'])){
            $tratamiento = new EntTratamiento();
            $tratamiento->id_paciente = $_REQUEST['id_paciente'];
            $tratamiento->id_doctor = $_REQUEST['id_doctor'];
            $tratamiento->id_presentacion = $_REQUEST['id_presentacion'];
            $tratamiento->txt_nombre_tratamiento = $_REQUEST['txt_nombre_tratamiento'];

            if($tratamiento->save()){
                $respuesta['error'] = false;
                $respuesta['message'] = 'Tratamiento creado';
                $respuesta['tratamiento'] = $tratamiento;
            }else{
                $respuesta['error'] = true;
                $respuesta['message'] = 'Error al guardar tratamiento';
                $respuesta['tratamientoErr'] = $tratamiento->errors;
            }
        }

        return $respuesta;   
    }

    public function actionMostrarTratamientos(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $respuesta['error'] = true;
        $respuesta['message'] = 'Faltan Datos';

        if(isset($_REQUEST['id_doctor']) && isset($_REQUEST['id_paciente'])){
            $tratamientos = EntTratamiento::find()->where(['id_paciente'=>$_REQUEST['id_paciente']])->andWhere(['id_doctor'=>$_REQUEST['id_doctor']])->all() ;

            if($tratamientos){
                $respuesta['error'] = false;
                $respuesta['message'] = 'Tratamientos mostrados';
                $respuesta['tratamientos'] = $tratamientos;
            }else{
                $respuesta['error'] = true;
                $respuesta['message'] = 'No hay tratamientos';
                $respuesta['tratamientos'] = [];                
            }
        }

        return $respuesta;   
    } 

    public function actionRelPacienteAviso(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $respuesta['error'] = true;
        $respuesta['message'] = 'Faltan Datos';

        if(isset($_REQUEST['id_paciente']) && isset($_REQUEST['id_aviso']) && isset($_REQUEST['b_acepto'])){
            $relPacienteAviso = RelPacienteAviso::find()->where(['id_paciente'=>$_REQUEST['id_paciente']])->andWhere(['id_aviso'=>$_REQUEST['id_aviso']])->one();;

            if($_REQUEST['b_acepto'] == 1){
                $relPacienteAviso->b_aceptado = 1;
                $respuesta['message'] = 'Paciente acepto el aviso';                
            }else{
                $relPacienteAviso->b_aceptado = 0;
                $respuesta['message'] = 'Paciente no acepto el aviso';                                
            }

            if($relPacienteAviso->save()){
                $respuesta['error'] = false;
                $respuesta['relacion'] = 'Relacion guardada correctamente';
            }else{
                $respuesta['error'] = true;
                $respuesta['message'] = 'Error al guardar en la BD';                                                
                $respuesta['relacionErr'] = $relPacienteAviso->errors;
            }
        }

        return $respuesta;
    }

    public function actionGetTratamiento(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $respuesta['error'] = true;
        $respuesta['message'] = 'Faltan Datos';

        if(isset($_REQUEST['id_tratamiento'])){
            $tratamiento = EntTratamiento::find()->where(['id_tratamiento'=>$_REQUEST['id_tratamiento']])->one();
            if($tratamiento){
                $respuesta['error'] = false;
                $respuesta['message'] = 'Tratamiento mostrado';
                $respuesta['tratamiento'] = $tratamiento;
            }else{
                $respuesta['error'] = true;
                $respuesta['message'] = 'No hay tratamiento';
                $respuesta['tratamiento'] = [];
            }
        }   
        return $respuesta;
    }
}
