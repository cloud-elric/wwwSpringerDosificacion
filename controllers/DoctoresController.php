<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use app\models\EntDoctores;
use app\models\EntDoctoresSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Utils;

/**
 * DoctoresController implements the CRUD actions for EntDoctores model.
 */
class DoctoresController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [ 
				'class' => AccessControl::className (),
				'only' => [ 
					'index',
                    'view',
                    'create',
                    'update',
                    'delete'
				],
				'rules' => [ 
					[ 
						'actions' => [ 
							'index',
                            'view',
                            'create',
                            'update',
                            'delete'
						],
						'allow' => true,
						'roles' => [ '@' ] 
					] 
				] 
			],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all EntDoctores models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EntDoctoresSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single EntDoctores model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new EntDoctores model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new EntDoctores();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $utils = new Utils();
			$parametrosEmail = [
				'nombre' => $model->txt_nombre,
				'apellido' => $model->txt_apellido_paterno,
				'password'=>$model->txt_password,
				'email'=>$model->txt_email,
			];	
			$utils->sendCorreoBienvenida ( $model->txt_email, $parametrosEmail );
            
            return $this->redirect(['view', 'id' => $model->id_doctor]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing EntDoctores model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_doctor]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing EntDoctores model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $doctor = $this->findModel($id);
        $doctor->b_habilitado = 0;
        $doctor->save();
        
        return $this->redirect(['index']);
    }

    /**
     * Finds the EntDoctores model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return EntDoctores the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EntDoctores::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
