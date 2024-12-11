<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "sale_product".
 *
 * @property string $id
 * @property string|null $product_id
 * @property string|null $company_id
 * @property int $quantity
 * @property float $selling_price
 * @property float $total_cost
 * @property string|null $payment_method_id
 *
 * @property Company $company
 * @property PaymentMethod $paymentMethod
 * @property Product $product
 */
class SaleProduct extends \yii\db\ActiveRecord
{
    public $calculatedProfit; // Temporary attribute to hold the calculated profit

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
            ],
            [
                'class' => BlameableBehavior::class,
            ],

        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sale_product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'quantity', 'selling_price', 'product_id', 'total_cost', 'payment_method_id'], 'required'],
            [['quantity', 'created_at', 'updated_at'], 'integer'],
            [['quantity'], 'compare', 'compareValue' => 1, 'operator' => '>=', 'message' => 'Quantity must be at least 1.'],
            [['quantity'], 'checkStock'],  // Custom validation to check stock
            [['selling_price', 'total_cost'], 'number'],
            [['id', 'product_id', 'sale_id', 'company_id', 'payment_method_id'], 'string', 'max' => 255],
            [['id'], 'unique'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::class, 'targetAttribute' => ['product_id' => 'id']],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::class, 'targetAttribute' => ['company_id' => 'id']],
            [['payment_method_id'], 'exist', 'skipOnError' => true, 'targetClass' => PaymentMethod::class, 'targetAttribute' => ['payment_method_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product',
            'company_id' => 'Company',
            'quantity' => 'Quantity',
            'selling_price' => 'Selling Price',
            'total_cost' => 'Total Cost',
            'payment_method_id' => 'Payment Method',
        ];
    }

    public function checkStock($attribute, $params)
    {
        // Get available stock for the product, including bulk sale stock
        $availableStock = $this->getAvailableStock($this->product_id, $this->sale_id);

        // If the quantity entered exceeds available stock, add an error
        if ($this->quantity > $availableStock) {
            $this->addError($attribute, 'Available stock: ' . $availableStock);
        }
    }

    /**
     * Calculate the available stock for a product, including bulk sale products.
     */
    private function getAvailableStock($productId, $bulkSaleId = null)
    {
        // Get the total purchased quantity for the product
        $totalPurchased = PurchaseProduct::find()
            ->where(['product_id' => $productId])
            ->sum('quantity') ?? 0;

        // Get the total sold quantity for the product
        $totalSold = SaleProduct::find()
            ->where(['product_id' => $productId])
            ->sum('quantity') ?? 0;

        // Initialize bulk sale stock
        $totalBulkSold = 0;

        // If a bulk sale ID is provided, consider the bulk sale stock as well
        if ($bulkSaleId !== null) {
            $totalBulkSold = SaleProduct::find()
                ->where([
                    'sale_id' => $bulkSaleId,
                    'product_id' => $productId,
                ])
                ->sum('quantity') ?? 0;
        }

        // Calculate the available stock (individual product + bulk sales)
        $availableStock = max(($totalPurchased - $totalSold) + $totalBulkSold, 0);

        return $availableStock;
    }


    /**
     * Gets query for [[Company]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::class, ['id' => 'company_id']);
    }

    /**
     * Gets query for [[PaymentMethod]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethod()
    {
        return $this->hasOne(PaymentMethod::class, ['id' => 'payment_method_id']);
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    public static function getSalesForSpecificWeek()
    {
        $timestamp = strtotime('today');
        $week = date('W', $timestamp);
        $year = date('Y', $timestamp);

        // Get the start of the week (Monday)
        $startOfWeek = strtotime($year . 'W' . $week . '1'); // Monday as the start of the week
        // Get the end of the week (Sunday)
        $endOfWeek = strtotime($year . 'W' . $week . '7 23:59:59'); // Sunday as the end of the week

        return self::find()
            ->where(['between', 'created_at', $startOfWeek, $endOfWeek])
            ->sum('total_cost') ?: 0;
    }

    public static function getWeeklyReport($startOfWeek, $endOfWeek)
    {
        // Adjust $startOfWeek to the previous Monday if it isn’t already a Monday
        if (date('w', $startOfWeek) != 1) { // '1' represents Monday in `date('w')`
            $startOfWeek = strtotime('last Monday', $startOfWeek);
        }

        // Array to store the daily report
        $reportData = [];

        // Iterate over each day of the week, starting from Monday
        for ($day = 0; $day < 7; $day++) {
            $currentDay = strtotime("+$day day", $startOfWeek);
            $dayStart = strtotime(date('Y-m-d 00:00:00', $currentDay));
            $dayEnd = strtotime(date('Y-m-d 23:59:59', $currentDay));

            // Calculate sales for the day
            $salesData = SaleProduct::find()
                ->where(['between', 'created_at', $dayStart, $dayEnd])
                ->all();

            $salesTotal = SaleProduct::find()
                ->where(['between', 'created_at', $dayStart, $dayEnd])
                ->sum('total_cost') ?? 0;

            $productsSold = SaleProduct::find()
                ->where(['between', 'created_at', $dayStart, $dayEnd])
                ->sum('quantity') ?? 0;

            // Calculate expenses for the day
            $expenses = ExpenseItem::find()
                ->where(['between', 'created_at', $dayStart, $dayEnd])
                ->sum('amount') ?? 0;

            // Calculate profit for each sale
            $dailyProfit = 0;
            foreach ($salesData as $sale) {
                $dailyProfit += $sale->calculateProfit();
            }

            // Calculate net profit
            $netProfit = $dailyProfit - $expenses;

            // Add daily data to the report
            $reportData[] = [
                'day' => date('l', $currentDay),
                'date' => date('Y-m-d', $currentDay),
                'products_sold' => $productsSold,
                'sales' => $salesTotal,
                'expenses' => $expenses,
                'profit' => $dailyProfit,
                'net_profit' => $netProfit,
            ];
        }

        return $reportData;
    }

    public function calculateProfit()
    {
        $profit = 0;
        $remainingQuantity = $this->quantity; // Quantity sold in this sale
        $sellingPrice = $this->selling_price; // Selling price per unit

        // Fetch all purchases sorted by the purchase date (oldest first)
        $purchases = PurchaseProduct::find()
            ->where(['product_id' => $this->product_id])
            ->orderBy(['created_at' => SORT_ASC])
            ->all();

        foreach ($purchases as $purchase) {
            if ($remainingQuantity <= 0) {
                break; // If no quantity left to calculate profit for, exit loop
            }

            // Calculate how much of the current purchase record is used for this sale
            $usedQuantity = min($remainingQuantity, $purchase->quantity);

            // Calculate profit for this portion
            $profit += $usedQuantity * ($sellingPrice - $purchase->buying_price);

            // Decrease remaining quantity to be calculated
            $remainingQuantity -= $usedQuantity;
        }

        return $profit;
    }
}
