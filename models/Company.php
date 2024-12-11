<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "company".
 *
 * @property string $id
 * @property string $name
 * @property string|null $description
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int $status
 *
 * @property Customer[] $customers
 * @property ExpenseCategory[] $expenseCategories
 * @property ExpenseItem[] $expenseItems
 * @property Expense[] $expenses
 * @property PaymentMethod[] $paymentMethods
 * @property ProductCategory[] $productCategories
 * @property ProductSubCategory[] $productSubCategories
 * @property Product[] $products
 * @property PurchaseProduct[] $purchaseProducts
 * @property Purchase[] $purchases
 * @property SaleProduct[] $saleProducts
 * @property Sale[] $sales
 * @property Supplier[] $suppliers
 * @property User[] $users
 */
class Company extends \yii\db\ActiveRecord
{

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                // 'createdAtAttribute' => 'created_at',
                // 'updatedAtAttribute' => 'updated_at',
            ],
            // [
            //     'class' => BlameableBehavior::class,
            //     'createdByAttribute' => 'created_by',
            //     'updatedByAttribute' => 'updated_by',

            // ],

        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'company';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'name'], 'required'],
            [['created_at', 'updated_at', 'status'], 'integer'],
            [['id', 'name', 'description'], 'string', 'max' => 255],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[Customers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomers()
    {
        return $this->hasMany(Customer::class, ['company_id' => 'id']);
    }

    /**
     * Gets query for [[ExpenseCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpenseCategories()
    {
        return $this->hasMany(ExpenseCategory::class, ['company_id' => 'id']);
    }

    /**
     * Gets query for [[ExpenseItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpenseItems()
    {
        return $this->hasMany(ExpenseItem::class, ['company_id' => 'id']);
    }

    /**
     * Gets query for [[Expenses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpenses()
    {
        return $this->hasMany(Expense::class, ['company_id' => 'id']);
    }

    /**
     * Gets query for [[PaymentMethods]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethods()
    {
        return $this->hasMany(PaymentMethod::class, ['company_id' => 'id']);
    }

    /**
     * Gets query for [[ProductCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductCategories()
    {
        return $this->hasMany(ProductCategory::class, ['company_id' => 'id']);
    }

    /**
     * Gets query for [[ProductSubCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductSubCategories()
    {
        return $this->hasMany(ProductSubCategory::class, ['company_id' => 'id']);
    }

    /**
     * Gets query for [[Products]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::class, ['company_id' => 'id']);
    }

    /**
     * Gets query for [[PurchaseProducts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseProducts()
    {
        return $this->hasMany(PurchaseProduct::class, ['company_id' => 'id']);
    }

    /**
     * Gets query for [[Purchases]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPurchases()
    {
        return $this->hasMany(Purchase::class, ['company_id' => 'id']);
    }

    /**
     * Gets query for [[SaleProducts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSaleProducts()
    {
        return $this->hasMany(SaleProduct::class, ['company_id' => 'id']);
    }

    /**
     * Gets query for [[Sales]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSales()
    {
        return $this->hasMany(Sale::class, ['company_id' => 'id']);
    }

    /**
     * Gets query for [[Suppliers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSuppliers()
    {
        return $this->hasMany(Supplier::class, ['company_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['company_id' => 'id']);
    }
}
