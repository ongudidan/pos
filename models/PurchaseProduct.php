<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "purchase_product".
 *
 * @property string $id
 * @property string|null $product_id
 * @property string|null $company_id
 * @property int $quantity
 * @property float $buying_price
 * @property float $total_cost
 * @property int $purchase_date
 * @property string|null $payment_method_id
 *
 * @property Company $company
 * @property PaymentMethod $paymentMethod
 * @property Product $product
 */
class PurchaseProduct extends \yii\db\ActiveRecord
{
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
        return 'purchase_product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quantity', 'product_id', 'buying_price','total_cost', 'payment_method_id'], 'required'],
            [['quantity', 'created_at', 'updated_at'], 'integer'],
            [['buying_price', 'total_cost'], 'number'],
            [['id', 'product_id', 'purchase_id', 'purchase_date', 'company_id', 'payment_method_id'], 'string', 'max' => 255],
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
            'buying_price' => 'Buying Price',
            'total_cost' => 'Total Cost',
            'purchase_date' => 'Purchase Date',
            'payment_method_id' => 'Payment Method',
        ];
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
}
