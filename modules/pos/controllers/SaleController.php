<?php

namespace app\modules\pos\controllers;

use app\components\IdGenerator;
use app\models\Model;
use app\models\Product;
use app\models\PurchaseProduct;
use app\models\Sale;
use app\models\SaleProduct;
use app\modules\pos\models\SaleSearch;
use Exception;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Response;

/**
 * SaleController implements the CRUD actions for Sale model.
 */
class SaleController extends Controller
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
     * Lists all Sale models.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->user->can('Purchase-view')) {

            $searchModel = new SaleSearch();
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
     * Displays a single Sale model.
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
     * Creates a new Sale model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        if (Yii::$app->user->can('Purchase-create')) {

            $model = new Sale();

            $modelsSaleProduct = [new SaleProduct()];

            if ($this->request->isPost) {
                if ($model->load($this->request->post())) {
                    $model->reference_no = $model->generateReferenceNo();

                    $saleDate = strtotime($model->date);

                    $model->sale_date = $saleDate;
                    $model->id = IdGenerator::generateUniqueId();
                    $model->company_id = Yii::$app->user->identity->company_id;

                    // print_r($saleDate);
                    // exit;


                    $modelsSaleProduct = Model::createMultiple(SaleProduct::classname());
                    Model::loadMultiple($modelsSaleProduct, Yii::$app->request->post());

                    // validate all models
                    $valid = $model->validate();
                    $valid = Model::validateMultiple($modelsSaleProduct) && $valid;

                    // print_r($model);

                    if (!$valid) {
                        $errors = implode('<br>', ArrayHelper::getColumn($model->getErrors(), 0));
                        Yii::$app->session->setFlash('error', 'Validation failed for the sale. Errors:<br>' . $errors);
                    }

                    // print_r($valid);
                    // exit();

                    if ($valid) {
                        $transaction = \Yii::$app->db->beginTransaction();
                        try {
                            if ($flag = $model->save(false)) {
                                foreach ($modelsSaleProduct as $modelSaleProduct) {
                                    $modelSaleProduct->id = IdGenerator::generateUniqueId();
                                    $modelSaleProduct->sale_id = $model->id;
                                    $modelSaleProduct->company_id = Yii::$app->user->identity->company_id;

                                    // $modelSaleProduct->sale_date = $saleDate;

                                    if (! ($flag = $modelSaleProduct->save(false))) {
                                        // Capture save errors for individual product purchase
                                        $errors = implode('<br>', ArrayHelper::getColumn($modelSaleProduct->getErrors(), 0));
                                        Yii::$app->session->setFlash('error', 'Failed to save a product purchase. Errors:<br>' . $errors);
                                        $transaction->rollBack();
                                        break;
                                    }
                                }
                            }
                            if ($flag) {
                                $transaction->commit();
                                Yii::$app->session->setFlash('success', 'sale created successfully.');

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
                'modelsSaleProduct' => (empty($modelsSaleProduct)) ? [new SaleProduct] : $modelsSaleProduct

            ]);
        } else {
            $this->layout = '@app/views/layouts/LoginLayout';
            return $this->render('@app/views/layouts/error-403');
        }
    }

    /**
     * Updates an existing Sale model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->user->can('Purchase-update')) {

            $model = $this->findModel($id);
            $modelsSaleProduct = $model->saleProduct;

            if ($this->request->isPost && $model->load($this->request->post())) {



                $saleDate = strtotime($model->date);

                $model->sale_date = $saleDate;

                // print_r($saleDate);
                // exit;


                $oldIDs = ArrayHelper::map($modelsSaleProduct, 'id', 'id');
                $modelsSaleProduct = Model::createMultiple(SaleProduct::classname(), $modelsSaleProduct);
                Model::loadMultiple($modelsSaleProduct, Yii::$app->request->post());
                $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsSaleProduct, 'id', 'id')));

                // Validate all models
                $valid = $model->validate();
                $valid = Model::validateMultiple($modelsSaleProduct) && $valid;

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
                                SaleProduct::deleteAll(['id' => $deletedIDs]);
                            }
                            foreach ($modelsSaleProduct as $modelSaleProduct) {

                                $modelSaleProduct->id = IdGenerator::generateUniqueId();
                                $modelSaleProduct->sale_id = $model->id;
                                $modelSaleProduct->company_id = Yii::$app->user->identity->company_id;


                                if (!($flag = $modelSaleProduct->save(false))) {
                                    // Capture save errors for individual defects
                                    $errors = implode('<br>', ArrayHelper::getColumn($modelSaleProduct->getErrors(), 0));
                                    Yii::$app->session->setFlash('error', 'Failed to save a purchased product. Errors:<br>' . $errors);
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        }

                        if ($flag) {
                            $transaction->commit();
                            Yii::$app->session->setFlash('success', 'sale updated successfully.');
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

                'modelsSaleProduct' => (empty($modelsSaleProduct)) ? [new SaleProduct] : $modelsSaleProduct
            ]);
        } else {
            $this->layout = '@app/views/layouts/LoginLayout';
            return $this->render('@app/views/layouts/error-403');
        }
    }

    /**
     * Deletes an existing Sale model.
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
     * Finds the Sale model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id ID
     * @return Sale the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sale::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /////////////////////////////////////

    public function actionGetProductDetails($id)
    {
        $product = Product::findOne($id);
        if ($product) {
            return json_encode(['price' => $product->selling_price]);
        }
        return json_encode(null);
    }

    // public function actionGetSales()
    // {
    //     $sum = SaleProduct::getSalesForSpecificWeek();
    //     if ($sum) {
    //         return json_encode(['sum' => $sum]);
    //     }
    //     return json_encode(null);
    // }

    // Action to return total sales by month for the current year in JSON format
    public function actionGetSalesAndPurchasesByMonth()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        // Get current year
        $currentYear = date('Y');

        // Get the current user's company ID
        $companyId = Yii::$app->user->identity->company_id;

        // Query the sale_product table to get the total sales per month for the current year
        $salesData = (new Query())
        ->select([
            'MONTH(FROM_UNIXTIME(created_at)) AS month', // Extract the month from created_at timestamp
            'SUM(total_cost) AS total_sales', // Sum total_cost for each month
        ])
        ->from('{{%sale_product}}')
        ->where([
            'YEAR(FROM_UNIXTIME(created_at))' => $currentYear, // Filter for current year
            'company_id' => $companyId, // Filter for the current user's company ID
        ])
        ->groupBy(['month']) // Group by month
        ->orderBy(['month' => SORT_ASC]) // Sort by month in ascending order
            ->all();

        // Query the purchase_product table to get the total purchases per month for the current year
        $purchasesData = (new Query())
        ->select([
            'MONTH(FROM_UNIXTIME(purchase_date)) AS month', // Extract the month from purchase_date timestamp
            'SUM(total_cost) AS total_purchases', // Sum total_cost for each month
        ])
        ->from('{{%purchase_product}}')
        ->where([
            'YEAR(FROM_UNIXTIME(purchase_date))' => $currentYear, // Filter for current year
            'company_id' => $companyId, // Filter for the current user's company ID
        ])
        ->groupBy(['month']) // Group by month
        ->orderBy(['month' => SORT_ASC]) // Sort by month in ascending order
            ->all();

        // Initialize arrays to hold sales and purchases data
        $monthlyData = [];
        $overallTotalSales = 0; // Initialize overall total sales
        $overallTotalPurchases = 0; // Initialize overall total purchases

        // Initialize array for the months
        $months = [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'May',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Aug',
            9 => 'Sep',
            10 => 'Oct',
            11 => 'Nov',
            12 => 'Dec'
        ];

        // Prepare the chart data, ensuring every month is included, even if no sales or purchases
        foreach (range(1, 12) as $month) {
            $monthlyData[] = [
                'month' => $months[$month], // Month name
                'sales' => 0, // Default sales to 0
                'purchases' => 0, // Default purchases to 0
            ];
        }

        // Populate the actual sales data and purchases data
        foreach ($salesData as $data) {
            $sales = (float) $data['total_sales'];
            $monthlyData[$data['month'] - 1]['sales'] = $sales;
            $overallTotalSales += $sales; // Add to overall total sales
        }

        foreach ($purchasesData as $data) {
            $purchases = (float) $data['total_purchases'];
            $monthlyData[$data['month'] - 1]['purchases'] = $purchases;
            $overallTotalPurchases += $purchases; // Add to overall total purchases
        }

        // Return the sales and purchases data as JSON
        return [
            'status' => 'success',
            'data' => $monthlyData,
            'overallTotalSales' => $overallTotalSales, // Include the total sales amount
            'overallTotalPurchases' => $overallTotalPurchases, // Include the total purchases amount
        ];
    }


    public function actionGetMonthlySummary()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        try {
            // Get the first and last day of the current month
            $startOfMonth = strtotime(date('Y-m-01 00:00:00'));
            $endOfMonth = strtotime(date('Y-m-t 23:59:59'));

            // Get the current user's company ID
            $companyId = Yii::$app->user->identity->company_id;

            // Query to calculate total sales for the current month
            $salesData = (new Query())
                ->select(['SUM(selling_price * quantity) AS total_sales'])
                ->from('{{%sale_product}}')
                ->where(['between', 'created_at', $startOfMonth, $endOfMonth])
                ->andWhere(['company_id' => $companyId]) // Filter by company_id
                ->scalar();

            // Query to calculate total purchases for the current month
            $purchasesData = (new Query())
                ->select(['SUM(total_cost) AS total_purchases'])
                ->from('{{%purchase_product}}')
                ->where(['between', 'created_at', $startOfMonth, $endOfMonth])
                ->andWhere(['company_id' => $companyId]) // Filter by company_id
                ->scalar();

            // Query to calculate total expenses for the current month
            $expensesData = (new Query())
                ->select(['SUM(amount) AS total_expenses'])
                ->from('{{%expense_item}}')
                ->where(['between', 'created_at', $startOfMonth, $endOfMonth])
                ->andWhere(['company_id' => $companyId]) // Filter by company_id
                ->scalar();

            // Prepare the response
            return [
                'status' => 'success',
                'data' => [
                    'sales' => (float) $salesData ?: 0,
                    'purchases' => (float) $purchasesData ?: 0,
                    'expenses' => (float) $expensesData ?: 0,
                ],
            ];
        } catch (\Exception $e) {
            // Log the error
            Yii::error("Error fetching monthly summary: " . $e->getMessage(), __METHOD__);
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    public function actionCheckStock($id, $bulkSaleId)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Find the product by ID
        $product = Product::findOne($id);
        if (!$product) {
            return ['available_stock' => 0]; // Return 0 if the product does not exist
        }

        // Calculate total purchased quantity for the product
        $totalPurchased = PurchaseProduct::find()
            ->where(['id' => $id])
            ->sum('quantity') ?? 0;

        // Calculate total sold quantity for the product
        $totalSold = SaleProduct::find()
            ->where(['id' => $id])
            ->sum('quantity') ?? 0;

        // If a bulk sale ID is provided, consider the bulk sale stock as well
        if ($bulkSaleId !== null) {
            $totalBulkSold = SaleProduct::find()
                ->where(['id' => $bulkSaleId])
                ->sum('quantity') ?? 0;
        }


        // Calculate the available stock
        $availableStock = max(($totalPurchased - $totalSold) + $totalBulkSold, 0);

        // Return the available stock in the response
        return ['available_stock' => max($availableStock, 0)];
    }
}
