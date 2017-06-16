<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

use app\models\EntDoctores;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }


    public function actionLogoutDoctor() {
		Yii::$app->user->logout ();
		
		return $this->redirect ( [ 
				'site/login-doctor' 
		] );
	}

    public function actionLoginDoctor() {
		$session = Yii::$app->session;
		//$this->layout = 'mainEmpleado';
		$session->set ( 'doctor', [ ] );
		
		return $this->render ( 'loginDoctores' );
	}

    public function actionDoctorLogin() {
		$this->enableCsrfValidation = false;
		//$this->layout = 'mainEmpleado';
		$session = Yii::$app->session;
		$usu = null;
		$pass = null;
		
		if ($sesionDoctor = $session->get ( 'doctor' )) {
			$usu = $sesionDoctor->txt_usuario;
			$pass = $sesionDoctor->txt_password;
		} else if (isset ( $_POST ['usu'] ) && $_POST ['pass']) {
			$usu = $_POST ['usu'];
			$pass = $_POST ['pass'];
		}
		
		$doctorSess = $session->get ( 'empleado' );
		
		// echo $usu;
		// echo $pass;
		// exit();
		
		$doctor = EntDoctores::find ()->where ( [ 
				'txt_email' => $usu 
		] )->andWhere ( [ 
				'txt_password' => $pass 
		] )->one ();
		
		if (empty ( $doctor )) {
			Yii::$app->session->setFlash ( 'error', 'Usuario y/o contraseña incorrecto' );
			return $this->redirect ( [ 
					'login-doctor' 
			] );
		}
		
		$session->set ( 'doctor', $doctorSess );
		
		return $this->redirect(['pacientes/index']);
	}
}
