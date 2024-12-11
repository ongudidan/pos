<?php

namespace app\modules\pos\controllers;

use app\components\IdGenerator;
use app\models\Expense;
use app\models\ExpenseItem;
use app\models\Model;
use app\modules\pos\models\ExpenseSearch;
use Exception;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * ExpenseController implements the CRUD actions for Expense model.
 */
class ExpenseController extends Controller
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
     * Lists all Expense models.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->user->can('Expense-view')) {

            $searchModel = new ExpenseSearch();
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
     * Displays a single Expense model.
     * @param string $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if (Yii::$app->user->can('Expense-view')) {

            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        } else {
            $this->layout = '@app/views/layouts/LoginLayout';
            return $this->render('@app/views/layouts/error-403');
        }
    }

    /**
     * Creates a new Expense model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        if (Yii::$app->user->can('Expense-create')) {

            $model = new Expense();

            $modelsExpenseItem = [new ExpenseItem()];

            if ($this->request->isPost) {
                if ($model->load($this->request->post())) {
                    // $model->reference_no = $model->generateReferenceNo();

                    $expenseDate = strtotime($model->date);

                    $model->id = IdGenerator::generateUniqueId();
                    $model->expense_date = $expenseDate;
                    $model->company_id = Yii::$app->user->identity->company_id;

                    // print_r($purchaseDate);
                    // exit;


                    $modelsExpenseItem = Model::createMultiple(ExpenseItem::classname());
                    Model::loadMultiple($modelsExpenseItem, Yii::$app->request->post());

                    // validate all models
                    $valid = $model->validate();
                    $valid = Model::validateMultiple($modelsExpenseItem) && $valid;

                    // print_r($model);

                    if (!$valid) {
                        $errors = implode('<br>', ArrayHelper::getColumn($model->getErrors(), 0));
                        Yii::$app->session->setFlash('error', 'Validation failed for the expense. Errors:<br>' . $errors);
                    }

                    // print_r($valid);
                    // exit();

                    if ($valid) {
                        $transaction = \Yii::$app->db->beginTransaction();
                        try {
                            if ($flag = $model->save(false)) {
                                foreach ($modelsExpenseItem as $modelExpenseItem) {

                                    $modelExpenseItem->id = IdGenerator::generateUniqueId();
                                    $modelExpenseItem->expense_id = $model->id;
                                    $modelExpenseItem->company_id = Yii::$app->user->identity->company_id;

                                    if (! ($flag = $modelExpenseItem->save(false))) {
                                        // Capture save errors for individual Expense Items
                                        $errors = implode('<br>', ArrayHelper::getColumn($modelExpenseItem->getErrors(), 0));
                                        Yii::$app->session->setFlash('error', 'Failed to save a Expense Items. Errors:<br>' . $errors);
                                        $transaction->rollBack();
                                        break;
                                    }
                                }
                            }
                            if ($flag) {
                                $transaction->commit();
                                Yii::$app->session->setFlash('success', 'Expense created successfully.');

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

                'modelsExpenseItem' => (empty($modelsExpenseItem)) ? [new ExpenseItem] : $modelsExpenseItem
            ]);
        } else {
            $this->layout = '@app/views/layouts/LoginLayout';
            return $this->render('@app/views/layouts/error-403');
        }
    }

    /**
     * Updates an existing Expense model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->user->can('Expense-update')) {

            $model = $this->findModel($id);
            $modelsExpenseItem = $model->expenseItems;

            if ($this->request->isPost && $model->load($this->request->post())) {



                $expenseDdate = strtotime($model->date);

                $model->expense_date = $expenseDdate;

                // print_r($expenseDdate);
                // exit;


                $oldIDs = ArrayHelper::map($modelsExpenseItem, 'id', 'id');
                $modelsExpenseItem = Model::createMultiple(ExpenseItem::classname(), $modelsExpenseItem);
                Model::loadMultiple($modelsExpenseItem, Yii::$app->request->post());
                $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsExpenseItem, 'id', 'id')));

                // Validate all models
                $valid = $model->validate();
                $valid = Model::validateMultiple($modelsExpenseItem) && $valid;

                // Capture validation errors for main model
                if (!$valid) {
                    $errors = implode('<br>', ArrayHelper::getColumn($model->getErrors(), 0));
                    Yii::$app->session->setFlash('error', 'Validation failed for the expense. Errors:<br>' . $errors);
                }

                if ($valid) {
                    $transaction = \Yii::$app->db->beginTransaction();
                    try {
                        if ($flag = $model->save(false)) {
                            if (!empty($deletedIDs)) {
                                ExpenseItem::deleteAll(['id' => $deletedIDs]);
                            }
                            foreach ($modelsExpenseItem as $modelExpenseItem) {

                                $modelExpenseItem->id = IdGenerator::generateUniqueId();
                                $modelExpenseItem->expense_id = $model->id;
                                $modelExpenseItem->company_id = Yii::$app->user->identity->company_id;


                                if (!($flag = $modelExpenseItem->save(false))) {
                                    // Capture save errors for individual defects
                                    $errors = implode('<br>', ArrayHelper::getColumn($modelExpenseItem->getErrors(), 0));
                                    Yii::$app->session->setFlash('error', 'Failed to save a expense item. Errors:<br>' . $errors);
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        }

                        if ($flag) {
                            $transaction->commit();
                            Yii::$app->session->setFlash('success', 'Expense updated successfully.');
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

                'modelsExpenseItem' => (empty($modelsExpenseItem)) ? [new ExpenseItem] : $modelsExpenseItem
            ]);
        } else {
            $this->layout = '@app/views/layouts/LoginLayout';
            return $this->render('@app/views/layouts/error-403');
        }
    }

    /**
     * Deletes an existing Expense model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (Yii::$app->user->can('Expense-delete')) {

            $this->findModel($id)->delete();

            return $this->redirect(['index']);
        } else {
            $this->layout = '@app/views/layouts/LoginLayout';
            return $this->render('@app/views/layouts/error-403');
        }
    }

    /**
     * Finds the Expense model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id ID
     * @return Expense the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Expense::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
