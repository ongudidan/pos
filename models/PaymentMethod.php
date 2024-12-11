<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "payment_method".
 *
 * @property string $id
 * @property string|null $company_id
 * @property string $name
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Company $company
 * @property ExpenseItem[] $expenseItems
 * @property PurchaseProduct[] $purchaseProducts
 * @property SaleProduct[] $saleProducts
 */
class PaymentMethod extends \yii\db\ActiveRecord
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
        return 'payment_method';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'name'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['id', 'company_id', 'name'], 'string', 'max' => 255],
            [['id'], 'unique'],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::class, 'targetAttribute' => ['company_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => 'Company ID',
            'name' => 'Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
     * Gets query for [[ExpenseItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpenseItems()
    {
        return $this->hasMany(ExpenseItem::class, ['payment_method_id' => 'id']);
    }

    /**
     * Gets query for [[PurchaseProducts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseProducts()
    {
        return $this->hasMany(PurchaseProduct::class, ['payment_method_id' => 'id']);
    }

    /**
     * Gets query for [[SaleProducts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSaleProducts()
    {
        return $this->hasMany(SaleProduct::class, ['payment_method_id' => 'id']);
    }
}
