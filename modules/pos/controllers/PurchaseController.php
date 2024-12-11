<?php

namespace app\modules\pos\controllers;

use app\components\IdGenerator;
use app\models\Model;
use app\models\Purchase;
use app\models\PurchaseProduct;
use app\modules\pos\models\PurchaseSearch;
use Exception;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * PurchaseController implements the CRUD actions for Purchase model.
 */
class PurchaseController extends Controller
{
    public $layout = 'PosLayout';

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'only' => ['logout', 'update', 'delete', 'create', 'view', 'index'],
                    'rules' => [
                        [
                            'actions' => ['logout', 'update', 'delete', 'create', 'view', 'index'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        // 'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }


    /**
     * Lists all Purchase models.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->user->can('Purchase-view')) {

            $searchModel = new PurchaseSearch();
            $dataProvider = $searchModel->search($this->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        } else {
            $this->layout = '@app/views/layouts/LoginLayout';
            return $this->render('@app/views/layouts/error-403');
        }
    }

    /**
     * Displays a single Purchase model.
     * @param string $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if (Yii::$app->user->can('Purchase-view')) {

            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        } else {
            $this->layout = '@app/views/layouts/LoginLayout';
            return $this->render('@app/views/layouts/error-403');
        }
    }

    /**
     * Creates a new Purchase model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        if (Yii::$app->user->can('Purchase-create')) {

            $model = new Purchase();

            $modelsPurchaseProduct = [new PurchaseProduct()];

            if ($this->request->isPost) {
                if ($model->load($this->request->post())) {
                    // $model->reference_no = $model->generateReferenceNo();

                    $purchaseDate = strtotime($model->date);

                    $model->id = IdGenerator::generateUniqueId();
                    $model->purchase_date = $purchaseDate;
                    $model->company_id = Yii::$app->user->identity->company_id;

                    // print_r($purchaseDate);
                    // exit;


                    $modelsPurchaseProduct = Model::createMultiple(PurchaseProduct::classname());
                    Model::loadMultiple($modelsPurchaseProduct, Yii::$app->request->post());

                    // validate all models
                    $valid = $model->validate();
                    $valid = Model::validateMultiple($modelsPurchaseProduct) && $valid;

                    // print_r($model);

                    if (!$valid) {
                        $errors = implode('<br>', ArrayHelper::getColumn($model->getErrors(), 0));
                        Yii::$app->session->setFlash('error', 'Validation failed for the purchase. Errors:<br>' . $errors);
                    }

                    // print_r($valid);
                    // exit();

                    if ($valid) {
                        $transaction = \Yii::$app->db->beginTransaction();
                        try {
                            if ($flag = $model->save(false)) {
                                foreach ($modelsPurchaseProduct as $modelPurchaseProduct) {

                                    $modelPurchaseProduct->id = IdGenerator::generateUniqueId();
                                    $modelPurchaseProduct->purchase_id = $model->id;
                                    $modelPurchaseProduct->purchase_date = $purchaseDate;
                                    $modelPurchaseProduct->company_id = Yii::$app->user->identity->company_id;

                                    if (! ($flag = $modelPurchaseProduct->save(false))) {
                                        // Capture save errors for individual product purchase
                                        $errors = implode('<br>', ArrayHelper::getColumn($modelPurchaseProduct->getErrors(), 0));
                                        Yii::$app->session->setFlash('error', 'Failed to save a product purchase. Errors:<br>' . $errors);
                                        $transaction->rollBack();
                                        break;
                                    }
                                }
                            }
                            if ($flag) {
                                $transaction->commit();
                                Yii::$app->session->setFlash('success', 'purchase created successfully.');

                                return $this->redirect(['view', 'id' => $model->id]);
                            }
                        } catch (Exception $e) {
                            Yii::$app->session->setFlash('error', 'Transaction failed: ' . $e->getMessage());

                            $transaction->rollBack();
                        }
                    }
                }
            } else {
                $model->loadDefaultValues();
            }


            return $this->render('create', [
                'model' => $model,

                'modelsPurchaseProduct' => (empty($modelsPurchaseProduct)) ? [new PurchaseProduct] : $modelsPurchaseProduct
            ]);
        } else {
            $this->layout = '@app/views/layouts/LoginLayout';
            return $this->render('@app/views/layouts/error-403');
        }
    }

    /**
     * Updates an existing Purchase model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->user->can('Purchase-update')) {

            $model = $this->findModel($id);
            $modelsPurchaseProduct = $model->purchaseProduct;

            if ($this->request->isPost && $model->load($this->request->post())) {



                $purchaseDate = strtotime($model->date);

                $model->purchase_date = $purchaseDate;
                $model->company_id = Yii::$app->user->identity->company_id;


                // print_r($purchaseDate);
                // exit;


                $oldIDs = ArrayHelper::map($modelsPurchaseProduct, 'id', 'id');
                $modelsPurchaseProduct = Model::createMultiple(PurchaseProduct::classname(), $modelsPurchaseProduct);
                Model::loadMultiple($modelsPurchaseProduct, Yii::$app->request->post());
                $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsPurchaseProduct, 'id', 'id')));

                // Validate all models
                $valid = $model->validate();
                $valid = Model::validateMultiple($modelsPurchaseProduct) && $valid;

                // Capture validation errors for main model
                if (!$valid) {
                    $errors = implode('<br>', ArrayHelper::getColumn($model->getErrors(), 0));
                    Yii::$app->session->setFlash('error', 'Validation failed for the purchase. Errors:<br>' . $errors);
                }

                if ($valid) {
                    $transaction = \Yii::$app->db->beginTransaction();
                    try {
                        if ($flag = $model->save(false)) {
                            if (!empty($deletedIDs)) {
                                PurchaseProduct::deleteAll(['id' => $deletedIDs]);
                            }
                            foreach ($modelsPurchaseProduct as $modelPurchaseProduct) {

                                $modelPurchaseProduct->id = IdGenerator::generateUniqueId();
                                $modelPurchaseProduct->purchase_id = $model->id;
                                $modelPurchaseProduct->purchase_date = $purchaseDate;


                                if (!($flag = $modelPurchaseProduct->save(false))) {
                                    // Capture save errors for individual defects
                                    $errors = implode('<br>', ArrayHelper::getColumn($modelPurchaseProduct->getErrors(), 0));
                                    Yii::$app->session->setFlash('error', 'Failed to save a purchased product. Errors:<br>' . $errors);
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        }

                        if ($flag) {
                            $transaction->commit();
                            Yii::$app->session->setFlash('success', 'purchase updated successfully.');
                            return $this->redirect(['view', 'id' => $model->id]);
                        }
                    } catch (Exception $e) {
                        Yii::$app->session->setFlash('error', 'Transaction failed: ' . $e->getMessage());
                        $transaction->rollBack();
                    }
                }
            }

            return $this->render('update', [
                'model' => $model,

                'modelsPurchaseProduct' => (empty($modelsPurchaseProduct)) ? [new PurchaseProduct] : $modelsPurchaseProduct
            ]);
        } else {
            $this->layout = '@app/views/layouts/LoginLayout';
            return $this->render('@app/views/layouts/error-403');
        }
    }

    /**
     * Deletes an existing Purchase model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (Yii::$app->user->can('Purchase-delete')) {

            $this->findModel($id)->delete();

            return $this->redirect(['index']);
        } else {
            $this->layout = '@app/views/layouts/LoginLayout';
            return $this->render('@app/views/layouts/error-403');
        }
    }

    /**
     * Finds the Purchase model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id ID
     * @return Purchase the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Purchase::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
