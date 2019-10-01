<?php

namespace app\controllers;

use Yii;
use app\models\Employees;
use app\models\EmployeesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;


/**
 * EmployeesController implements the CRUD actions for Employees model.
 */
class EmployeesController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Employees models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EmployeesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Employees model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
	        'dataProvider' => new ActiveDataProvider([
		        'query' =>  \app\models\Employees::reqHistory($id),
		        'pagination' => false,
	        ])
	    ]);
    }

    /**
     * Creates a new Employees model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
	    if (!\Yii::$app->user->can('edit_access')) throw new ForbiddenHttpException('Доступ к редактированию закрыт');
        $model = new Employees();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->employee_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Employees model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
	    if (!\Yii::$app->user->can('edit_access')) throw new ForbiddenHttpException('Доступ к редактированию закрыт');

	    $model = new Employees();
	    if ($model->load(Yii::$app->request->post())) {
		    $model->employee_id=$id;
		     if ($model->save()) return $this->redirect(['view', 'id' => $id]);
	    }

        return $this->render('update', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Deletes an existing Employees model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
	    if (!\Yii::$app->user->can('edit_access')) throw new ForbiddenHttpException('Доступ к редактированию закрыт');

	    $model = new Employees();
	    $data=$this->findModel($id)->attributes;
	    $model->setAttributes($data);
	    $model->employee_id=$id;
	    $model->deleted=1;
		$model->save();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Employees model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Employees the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Employees::find()->where(['employee_id'=>$id])->orderBy(['id'=>SORT_DESC])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
