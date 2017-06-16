<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\EntDoctores;
use app\models\EntPacientes;
use yii\data\ActiveDataProvider;

class ApiController extends Controller
{
    public $enableCsrfValidation = false;
    
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
                $respuesta ['error'] = true;
                $respuesta ['message'] = 'Datos invalidos';
                $respuesta['errosDoc'] = $doctor->errors;
            }

            $respuesta ['error'] = false;
            $respuesta ['message'] = 'Doctor guardado';
            $respuesta ['doctor'] = $doctor;
        }else{
            $respuesta ['error'] = true;
            $respuesta ['message'] = 'No hay datos';
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
                    $respuesta ['message'] = 'Doctor eliminado';
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
}
