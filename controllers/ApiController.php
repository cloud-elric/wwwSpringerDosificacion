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
use app\models\EntAvisosPrivacidad;
use app\models\Calendario;
use kartik\mpdf\Pdf;


class ApiController extends Controller
{
    public $enableCsrfValidation = false;
    //Variable para verificar si se quere seguridad en los servicios
    private $seguridad = false;

    public function beforeAction($action){
        $respuesta['error'] = true;
        $respuesta['message'] = 'No tienes permisos para acceder';

        if($this->seguridad){
            if(($action->id == "login") || ($action->id == "mandar-password") || ($action->id == "crear-doctor")){
                return parent::beforeAction($action);                                
            }else{
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
        echo \json_encode($respuesta);
        exit();
   }
    
    /**
    * Action para loguear al doctor
    */
    public function actionLogin(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $respuesta['error'] = true;
        $respuesta['message'] = 'Faltan datos';
        $utils = new Utils();

        if(isset($_REQUEST['usuario']) && isset($_REQUEST['password'])){
            $usuario = $_REQUEST['usuario'];
            $password = $_REQUEST['password'];

            if($doctor = EntDoctores::getDoctor($usuario, $password)){
                $doctor->txt_token_seguridad = $utils->generateTokenSeg();
                if($doctor->save()){
                    $respuesta ['error'] = false;
                    $respuesta ['message'] = 'Doctor encontrado';
                    $respuesta ['doctor'] = $doctor;
                }
            }else{
                $respuesta ['error'] = true;
                $respuesta ['message'] = 'Email y/o contraseña incorrecto(s)';
               
            }
        }

        return $respuesta;

    }

       /**
       * Action para registrar al doctor
       */ 
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

            if(!$clave){
                $respuesta ['error'] = true;
                $respuesta ['message'] = 'Clave invalida';
                $respuesta ['errosClave'] = ['txt_clave'=>'Folio de activación incorrecto'];
                $doctor->validate();
                $respuesta['errosDoc'] = $doctor->errors;
                return $respuesta;
            }else if($clave && $clave->b_usado == 0){
                $clave->b_usado = 1;
                $doctor->id_clave = $clave->id_clave;   
            }else{
                $respuesta ['error'] = true;
                $respuesta ['message'] = 'Clave usada';
                $respuesta ['errosClave'] = ['txt_clave'=>'Folio de activación ya ha sido utilizado'];
                $doctor->validate();
                $respuesta['errosDoc'] = $doctor->errors;

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
                    $doctor->validate();
                    $respuesta['errosDoc'] = $doctor->errors;
                }
            }else{
                $respuesta ['error'] = true;
                $respuesta ['message'] = 'Correo ya utilizado';
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

        if( isset($_REQUEST['nombre']) && isset($_REQUEST['apPaterno']) && 
        isset($_REQUEST['email']) && isset($_REQUEST['edad'])  && isset($_REQUEST['sexo']) && 
        isset($_REQUEST['id_doctor']) && isset($_REQUEST['peso']) && isset($_REQUEST['id_paciente_cliente']) ){

            $paciente->id_doctor = $_REQUEST['id_doctor'];
            $paciente->id_paciente_cliente = $_REQUEST['id_paciente_cliente'];            
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
            $paciente->num_peso = $_REQUEST['peso'];

            if($paciente->save()){

                $relPacienteAviso = new RelPacienteAviso();
                $aviso = EntAvisosPrivacidad::find()->where(['b_habilitado'=>1])->one();

                $relPacienteAviso->id_aviso = $aviso->id_aviso;
                $relPacienteAviso->id_paciente = $paciente->id_paciente;
                $relPacienteAviso->b_aceptado = 1;
                $respuesta['messageAviso'] = 'Paciente acepto el aviso';                
    
                if($relPacienteAviso->save()){
                    $respuesta['error'] = false;
                    $respuesta['relacion'] = 'Relacion guardada correctamente';
                }else{
                    $respuesta['error'] = true;
                    $respuesta['message'] = 'Error al guardar en la BD';                                                
                    $respuesta['relacionErr'] = $relPacienteAviso->errors;
                }

                $respuesta ['error'] = false;
                $respuesta ['message'] = 'Paciente guardado';
                $respuesta ['paciente'] = $paciente;
            }else{
                $respuesta ['error'] = true;
                $respuesta ['message'] = 'Correo ya utilizado';
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

    public function actionIndex(){
        Yii::$app->response->format = Response::FORMAT_JSON;
    

        $vistaHtml = $this->renderAjax('plantilla', [
            'diaNombre'=>"Mes",
            'diaNumero'=>"Numero",
            'mes'=>"otro mes",
            'anio'=>"17",
            'nombreDoctor'=>"Nombre doctor",
            'cedulaDoctor'=>"cedula",
            'nombrePaciente'=> "Nombre paciente",
            // 'dosis' => $dosis,
            // 'paciente' => $paciente,
            // 'tratamiento' => $tratamiento
        ]);

        $carpeta = 'pdfDosis/test';
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }


        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE, 
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'filename' =>$carpeta."/".uniqid().".pdf",
            // stream to browser inline
            'destination' => Pdf::DEST_FILE, 
            // your html content input
            'content' => $vistaHtml,  
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => 'webAssets/css/plantilla-pdf.css',
            // any css to be embedded if required
            //'cssInline' => '.kv-heading-1{font-size:18px}', 
             // set mPDF properties on the fly
            //'options' => ['title' => 'Krajee Report Title'],
             // call mPDF methods on the fly
            'methods' => [ 
              //  'SetHeader'=>['Krajee Report Header'], 
                //'SetFooter'=>['{PAGENO}'],
            ]
        ]);

        $pdf->render();
        $respuestas['response'] = "Esta es una respuesta";
        return $respuestas;
    }


    public function actionReenviarPdf(){
        Yii::$app->response->format = Response::FORMAT_JSON;

        $respuesta['error'] = true;
        $respuesta['message'] = 'Faltan datos';

        if(isset($_REQUEST['txt_token']) && isset($_REQUEST['id_doctor'])){
            $token = $_REQUEST['txt_token'];
            $idDoctor = $_REQUEST['id_doctor'];
            $dosis = EntDosis::find()->where(['txt_token'=>$token])->one();
            $doctor = EntDoctores::find()->where(['id_doctor'=>$idDoctor])->one();

            if($dosis && $doctor){

                $carpeta = 'pdfDosis/'. $dosis->idTratamiento->idPaciente->txt_token;
                
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
                $respuesta['message'] = 'No se encontro el doctor o consulta';
            }
        }

        return $respuesta;
    }

    public function actionGenerarPdf(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $utils = new Utils();
        $respuesta['error'] = true;
        $respuesta['message'] = 'Faltan datos';

        if( (isset($_REQUEST['id_tratamiento']) && isset($_REQUEST['num_peso']) && 
        isset($_REQUEST['id_presentacion']) && isset($_REQUEST['dosisSugerida']) && 
        isset($_REQUEST['dosisAcumulada']) && isset($_REQUEST['dosisDiaria']) && 
        isset($_REQUEST['diasTratamiento']) && 
        isset($_REQUEST['id_tratamiento_cliente']) && 
        isset($_REQUEST['id_dosis_cliente']) && isset($_REQUEST['dosisObjetivo']) && 
        isset($_REQUEST['dosisObjetivoCal']) && isset($_REQUEST['dosisRedondeada']) && 
        isset($_REQUEST['numMeses']) && isset($_REQUEST['numCapsulas'])) /*Campos requeridos y fch_visita*/ ){
            $dosis = new EntDosis();
            $tratamiento = EntTratamiento::find()->where(['id_tratamiento'=>$_REQUEST['id_tratamiento']])->one();

            $dosis->id_tratamiento = $_REQUEST['id_tratamiento'];
            $dosis->id_tratamiento_cliente = $_REQUEST['id_tratamiento_cliente'];
            $dosis->id_dosis_cliente = $_REQUEST['id_dosis_cliente'];                    
            $dosis->id_presentacion = $_REQUEST['id_presentacion'];
            $dosis->num_dias_tratamiento = $_REQUEST['diasTratamiento'];
            $dosis->num_dosis_sugerida = $_REQUEST['dosisSugerida'];
            $dosis->num_dosis_acumulada = $_REQUEST['dosisAcumulada'];
            $dosis->num_dosis_diaria = intval($_REQUEST['dosisDiaria']);
            $dosis->num_peso = $_REQUEST['num_peso'];
            $dosis->num_dosis_objetivo = $_REQUEST['dosisObjetivo'];
            $dosis->num_dosis_objetivo_cal = $_REQUEST['dosisObjetivoCal'];
            $dosis->num_dosis_redondeada = $_REQUEST['dosisRedondeada'];
            $dosis->num_meses = intval($_REQUEST['numMeses']);
            $dosis->num_capsulas = $_REQUEST['numCapsulas'];
            $dosis->txt_token = $utils->generateToken();
            
            if(isset($_REQUEST['fch_visita'])){
                $fecha = str_replace('/', '-', $_REQUEST['fch_visita']);
                $fecha = date('Y-m-d H:i:s', strtotime($fecha));
                $dosis->fch_proxima_visita = $fecha;//Utils::changeFormatDateInput($_REQUEST['fch_visita']);
                $dosis->fch_creacion = $fecha;
            }
            $paciente = EntPacientes::find()->where(['id_paciente'=>$tratamiento->id_paciente])->one();
            $doctor = EntDoctores::find()->where(['id_doctor'=>$tratamiento->id_doctor])->one();

            if($dosis->save() && $doctor && $paciente){
                $html2pdf = new Html2Pdf();

                $diaNombre = Calendario::getDayName();
                $diaNumero =  Calendario::getDayNumber();
                $mes = Calendario::getMonthName();
                $anio = Calendario::getYearLastDigit();

                $vistaHtml = $this->renderAjax('plantilla', [
                    'diaNombre'=>$diaNombre,
                    'diaNumero'=>$diaNumero,
                    'mes'=>$mes,
                    'anio'=>$anio,
                    'nombreDoctor'=>$doctor->txt_nombre.' '.$doctor->txt_apellido_paterno,
                    'cedulaDoctor'=>$doctor->txt_cedula,
                    'nombrePaciente'=> $paciente->txt_nombre.' '.$paciente->txt_apellido_paterno,
                    // 'dosis' => $dosis,
                    // 'paciente' => $paciente,
                    // 'tratamiento' => $tratamiento
                ]);
                if($this->actualizarDatosTratamiento($tratamiento, $dosis)){
                    $respuesta['message2'] = "Se actualizo el tratamiento";
                    $respuesta['consulta'] = $dosis;
                    $respuesta['tratamiento'] = $tratamiento;
                }else{
                    $respuesta['message2'] = "No se actualizo el tratamiento";                    
                }

                $carpeta = 'pdfDosis/'. $paciente->txt_token;
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777, true);
                }
                $pathArchivo = $carpeta . '/' . $dosis->txt_token . '.pdf';

                $pdf = new Pdf([
                    // set to use core fonts only
                    'mode' => Pdf::MODE_CORE, 
                    // A4 paper format
                    'format' => Pdf::FORMAT_A4, 
                    // portrait orientation
                    'orientation' => Pdf::ORIENT_PORTRAIT,
                    'filename' =>$pathArchivo,
                    // stream to browser inline
                    'destination' => Pdf::DEST_FILE, 
                    // your html content input
                    'content' => $vistaHtml,  
                    // format content from your own css file if needed or use the
                    // enhanced bootstrap css built by Krajee for mPDF formatting 
                    'cssFile' => 'webAssets/css/plantilla-pdf.css',
                    // any css to be embedded if required
                    //'cssInline' => '.kv-heading-1{font-size:18px}', 
                     // set mPDF properties on the fly
                    //'options' => ['title' => 'Krajee Report Title'],
                     // call mPDF methods on the fly
                    'methods' => [ 
                      //  'SetHeader'=>['Krajee Report Header'], 
                        //'SetFooter'=>['{PAGENO}'],
                    ]
                ]);
        
                $pdf->render();
               
                
                /* *********CORREO CON ARCHIVO ADJUNTO**********  */

                
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
        $tratamiento->num_peso = $dosis->num_peso;
        $tratamiento->num_dosis_sugerida = $dosis->num_dosis_sugerida;
        $tratamiento->num_dosis_objetivo = $dosis->num_dosis_objetivo;
        $tratamiento->num_dosis_objetivo_cal = $dosis->num_dosis_objetivo_cal;
        $tratamiento->num_dosis_acumulada = $dosis->num_dosis_acumulada;
        $tratamiento->num_dosis_diaria = $dosis->num_dosis_diaria;
        $tratamiento->num_dosis_redondeada = $dosis->num_dosis_redondeada;
        $tratamiento->num_dias_tratamiento = $dosis->num_dias_tratamiento;
        $tratamiento->num_meses = $dosis->num_meses;
        $tratamiento->num_capsulas = $dosis->num_capsulas;        
        
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
                $avisoPrivacidad = EntAvisosPrivacidad::find()->where("b_habilitado=1")->one();
                if($doctor){
                    $respuesta['error'] = false;
                    $respuesta['message'] = 'Pacientes encontrados';
                    $respuesta['pacientes'] = $doctor->entPacientes;
                    $respuesta['tratamientos'] = $doctor->entTratamientos;
                    $respuesta['dosis'] = [];
                    $respuesta['avisoPrivacidad'] = $avisoPrivacidad;
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

    public function actionGuardarDosis(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $respuesta['error'] = true;
        $respuesta['message'] = 'Faltan Datos';

        $dosis = new EntTratamiento();
        $dosis->attributes = $_REQUEST;

       if($dosis->validate()){

            if($dosis->id_tratamiento){

            }


       }else{
           $respuesta['errores'] = $dosis->errors;
           $respuesta['message'] = 'Datos no validos';
       }

        return $respuesta;   
        
                
    }

    /**
    * Guarda un tratamiento
    */
    public function actionCrearTratamiento(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $respuesta['error'] = true;

        $tratamiento = new EntTratamiento();
        $tratamiento->attributes = $_REQUEST;
        $tratamiento->verificacionDatos();
        if($tratamiento->validate()){            

            if($tratamiento->save()){
                
                     $respuesta['error'] = false;
                     $respuesta['message'] = 'Tratamiento creado';
                     $respuesta['tratamiento'] = $tratamiento;
              
            }else{
                $respuesta['error'] = true;
                $respuesta['message'] = 'Error al guardar tratamiento';
                $respuesta['tratamientoErr'] = $tratamiento->errors;
            }
        }else{

            $respuesta['message'] = "Datos no validos";
            $respuesta['errores'] = $tratamiento->errors;
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

    public function actionFinalizarTratamiento(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $respuesta['error'] = true;
        $respuesta['message'] = 'Faltan Datos';

        if(isset($_REQUEST['tokenTratamiento']) && isset($_REQUEST['fchFinalizar'])){
            $tratamiento = EntTratamiento::find()->where(['txt_token'=>$_REQUEST['tokenTratamiento']])->one();
            if($tratamiento){
                $tratamiento->fch_fin_tratamiento = $_REQUEST['fchFinalizar'];
                $tratamiento->b_habilitado = 0;
                if($tratamiento->save()){
                    $respuesta['error'] = false;
                    $respuesta['message'] = 'Asignada fecha de finalización';
                    $respuesta['tratamiento'] = $tratamiento;
                }else{
                    $respuesta['error'] = true;
                    $respuesta['message'] = 'Error al guardar tratamiento';
                    $respuesta['tratErrors'] = $tratamiento->errors;
                }
            }else{
                $respuesta['error'] = true;
                $respuesta['message'] = 'No se encuentra el tratamiento';
            }
        }

        return $respuesta;
    }

    public function actionSetDataPacientes(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $respuesta['error'] = true;
        $respuesta['message'] = 'Faltan Datos';

        //Receive the RAW post data.
        $content = trim(file_get_contents("php://input"));
        //Attempt to decode the incoming RAW post data from JSON.
        $decoded = json_decode($content, true);

        $utils = new Utils();
        $respuesta['dosis'] = [];   
        $respuesta['tratamientos'] = [];  
        $respuesta['pacientes'] = [];        
        $i = 0;

        foreach($decoded["pacientes"] as $pacienteJSON){
            $pacienteNuevo = new EntPacientes();
            
            $pacienteNuevo->id_doctor = $pacienteJSON["id_doctor"];
            $pacienteNuevo->id_paciente_cliente = $pacienteJSON["id_paciente_cliente"];            
            $pacienteNuevo->txt_nombre = $pacienteJSON['nombrePaciente'];
            $pacienteNuevo->txt_apellido_paterno = $pacienteJSON['apellidoPaterno'];
            $pacienteNuevo->txt_apellido_materno = $pacienteJSON['apellidoMaterno'];
            $pacienteNuevo->txt_email = $pacienteJSON['email'];
            $pacienteNuevo->txt_telefono_contacto = $pacienteJSON['telefonoContacto'];
            $pacienteNuevo->txt_sexo = $pacienteJSON['sexo'];
            $pacienteNuevo->num_edad = $pacienteJSON['edad'];
            $pacienteNuevo->num_peso = $pacienteJSON['peso'];
            $pacienteNuevo->txt_token = $utils->generateToken();
            
            if($pacienteNuevo->save()){
                $respuesta['pacientes'][$i] = $pacienteNuevo;

                foreach($pacienteJSON["tratamientos"] as $tratamientoJSON){
                    $tratamientoNuevo = new EntTratamiento();
                    
                    $tratamientoNuevo->id_tratamiento_cliente = $tratamientoJSON['id_tratamiento_cliente'];            
                    $tratamientoNuevo->id_paciente = $pacienteNuevo->id_paciente;
                    $tratamientoNuevo->id_paciente_cliente = $tratamientoJSON['id_paciente_cliente'];            
                    $tratamientoNuevo->id_doctor = $tratamientoJSON['id_doctor'];
                    $tratamientoNuevo->id_presentacion = $tratamientoJSON['id_presentacion'];
                    $tratamientoNuevo->txt_nombre_tratamiento = $tratamientoJSON['nombreTratamiento'];
                    $tratamientoNuevo->num_peso = $tratamientoJSON['peso'];
                    $tratamientoNuevo->num_dosis_sugerida = $tratamientoJSON['dosisSugerida'];
                    $tratamientoNuevo->num_dosis_acumulada = $tratamientoJSON['dosisAcumulada'];
                    $tratamientoNuevo->num_dosis_diaria = $tratamientoJSON['dosisDiaria'];
                    $tratamientoNuevo->num_dias_tratamiento = $tratamientoJSON['diasTratamiento'];
                    $tratamientoNuevo->fch_ultima_visita = $tratamientoJSON['fchInicioTratamiento'];
                    $tratamientoNuevo->fch_inicio_tratamiento = $tratamientoJSON['fchInicioTratamiento'];
                    $tratamientoNuevo->num_dosis_objetivo = $tratamientoJSON['dosisObjetivo'];
                    $tratamientoNuevo->num_dosis_objetivo_cal = $tratamientoJSON['dosisObjetivoCal'];
                    $tratamientoNuevo->num_dosis_redondeada = $tratamientoJSON['dosisRedondeada'];
                    $tratamientoNuevo->num_meses = $tratamientoJSON['meses'];
                    $tratamientoNuevo->txt_token = $utils->generateToken();
                
                    if($tratamientoNuevo->save()){
                        $respuesta['tratamientos'][$i] = $tratamientoNuevo;                

                        foreach($tratamientoJSON["dosis"] as $dosisJSON){
                            $dosisNueva = new EntDosis();

                            $dosisNueva->id_tratamiento = $tratamientoNuevo->id_tratamiento;
                            $dosisNueva->id_tratamiento_cliente = $dosisJSON['id_tratamiento_cliente'];
                            $dosisNueva->id_dosis_cliente = $dosisJSON['id_dosis_cliente'];                                            
                            $dosisNueva->id_presentacion = $dosisJSON['id_presentacion'];
                            $dosisNueva->num_peso = $dosisJSON['peso'];
                            $dosisNueva->num_dosis_sugerida = $dosisJSON['dosisSugerida'];
                            $dosisNueva->num_dosis_acumulada = $dosisJSON['dosisAcumulada'];
                            $dosisNueva->num_dosis_diaria = $dosisJSON['dosisDiaria'];
                            $dosisNueva->num_dias_tratamiento = $dosisJSON['diasTratamiento'];
                            $dosisNueva->fch_creacion = $dosisJSON['peso'];
                            $dosisNueva->num_dosis_objetivo = $dosisJSON['dosisObjetivo'];
                            $dosisNueva->num_dosis_objetivo_cal = $dosisJSON['dosisObjetivoCal'];
                            $dosisNueva->num_dosis_redondeada = $dosisJSON['dosisRedondeada'];
                            $dosisNueva->num_meses = $dosisJSON['meses'];
                            $dosisNueva->txt_token = $utils->generateToken();

                            if($dosisNueva->save()){
                                $respuesta['dosis'][$i] = $dosisNueva;
                                $i = $i + 1;                

                                $respuesta['error'] = false;
                                $respuesta['message'] = 'Datos guardados correctamente';
                                
                            }else{
                                $respuesta['error'] = true;
                                $respuesta['message'] = 'Error al guardar dosis';
                                $respuesta['dosisErr'] = $dosisNueva->errors;
                            }
                        }
                    }else{
                        $respuesta['error'] = true;
                        $respuesta['message'] = 'Error al guardar tratamiento';
                        $respuesta['tratamientoErr'] = $tratamientoNuevo->errors;
                    }
                }
            }else{
                $respuesta['error'] = true;
                $respuesta['message'] = 'Error al guardar paciente';
                $respuesta['pacienteErr'] = $pacienteNuevo->errors;
            }
        }

        return $respuesta;
    }

    /**
    * Guarda
    */
    public function actionGuardarPacientes(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        

        $pacientes = $_REQUEST['pacientes'];
    echo ($pacientes);
    exit;
        $respuesta['error'] = false;
        $respuesta['message'] = "Este es un mensaje";

        foreach($pacientes as $paciente){
            //$paciente = json_decode($paciente[0], true);
var_dump($paciente);

exit;
            $pacienteGuardar = new EntPacientes();
            $pacienteGuardar->attributes = $paciente;
            $respuesta["pacientes"][] = $pacienteGuardar;
        }

        return $respuesta;
 
    }

    // public function actionPdf(){
    //     return $this->renderAjax('plantilla' , [
    //         'diaNombre'=>"Mes",
    //         'diaNumero'=>"Numero",
    //         'mes'=>"otro mes",
    //         'anio'=>"17",
    //         'nombreDoctor'=>"Nombre doctor",
    //         'cedulaDoctor'=>"cedula",
    //         'nombrePaciente'=> "Nombre paciente",
    //         // 'dosis' => $dosis,
    //         // 'paciente' => $paciente,
    //         // 'tratamiento' => $tratamiento
    //     ]);
    // }
}


