<?php

namespace app\modules\pos\controllers;

use app\models\ExpenseItem;
use app\models\Product;
use app\models\SaleProduct;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * Default controller for the `pos` module
 */
class DefaultController extends Controller
{
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

    public $layout = 'PosLayout';
    /**
     * Renders the index view for the module
     * @return string
     */
    // public function actionIndex()
    // {
    //     return $this->render('index');
    // }

    public function actionIndex($week = null)
    {
        $query = Product::find()->where(['<', 'quantity', 3]); // Adjust the query as needed

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10, // Adjust as needed
            ],
        ]);

        // $lowStockProducts = $this->getLowStockProducts();

        $year = date('Y');
        $currentWeek = $week ? (int)$week : (int)date('W');

        // Calculate previous and next weeks
        $prevWeek = $currentWeek > 1 ? $currentWeek - 1 : null;
        $nextWeek = $currentWeek < 52 ? $currentWeek + 1 : null;

        // Fetch all sales records for the specific week
        $startOfWeek = strtotime("{$year}-W{$currentWeek}-1");
        $endOfWeek = strtotime("{$year}-W{$currentWeek}-7 23:59:59");

        // Fetch sales data for the specified week
        $sales = SaleProduct::find()
            ->where(['between', 'created_at', $startOfWeek, $endOfWeek])
            ->all();

        // Calculate total sales quantity for the week
        $totalSalesQuantity = SaleProduct::find()
            ->where(['between', 'created_at', $startOfWeek, $endOfWeek])
            ->sum('quantity');

        // Calculate total expenditure for the week
        $totalExpenditure = ExpenseItem::find()
            ->where(['between', 'created_at', $startOfWeek, $endOfWeek])
            ->sum('amount');

        // Calculate total income for the week
        $totalIncome = SaleProduct::find()
            ->where(['between', 'created_at', $startOfWeek, $endOfWeek])
            ->sum('total_cost');

        // Calculate total profit for the week
        $totalProfit = 0;
        foreach ($sales as $sale) {
            $sale->calculatedProfit = $sale->calculateProfit();
            $totalProfit += $sale->calculatedProfit;
        }

        $netProfit = $totalProfit - $totalExpenditure;

        // Fetch the weekly sales data
        // $dataPoints = SaleProduct::getWeeklySales();

        // Fetch the weekly report data
        $reportData = SaleProduct::getWeeklyReport($startOfWeek, $endOfWeek);

        // Pass data to the view
        return $this->render('index', [
            'sales' => $sales,
            'totalSalesQuantity' => $totalSalesQuantity,
            'totalExpenditure' => $totalExpenditure,
            'totalIncome' => $totalIncome,
            'netProfit' => $netProfit,
            // 'dataPoints' => $dataPoints,
            'reportData' => $reportData,
            'prevWeek' => $prevWeek,
            'nextWeek' => $nextWeek,
            'currentWeek' => $currentWeek,
            // 'lowStockProducts' => $lowStockProducts,
            'dataProvider' => $dataProvider,

        ]);
    }

}
