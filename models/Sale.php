<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "sale".
 *
 * @property string $id
 * @property string|null $customer_id
 * @property string|null $company_id
 * @property string|null $reference_no
 * @property int|null $sale_date
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 *
 * @property Company $company
 * @property Customer $customer
 */
class Sale extends \yii\db\ActiveRecord
{
    public $date;

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
        return 'sale';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id'], 'required'],
            [['sale_date', 'created_at', 'updated_at'], 'integer'],
            [['id', 'customer_id', 'company_id', 'date', 'reference_no', 'created_by', 'updated_by'], 'string', 'max' => 255],
            [['id'], 'unique'],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::class, 'targetAttribute' => ['customer_id' => 'id']],
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
            'customer_id' => 'Customer ',
            'company_id' => 'Company',
            'reference_no' => 'Reference No',
            'sale_date' => 'Sale Date',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    public static function generateReferenceNo()
    {
        $year = date('Y');
        $prefix = '#';
        $yearPrefix = substr($year, -2);

        // Get the maximum card number from the database
        $lastRecord = self::find()
            ->select(['reference_no'])
            ->orderBy(['reference_no' => SORT_DESC])
            ->limit(1)
            ->one();

        // Extract the last number from the highest card number
        if ($lastRecord && preg_match('/(\d{5})' . $yearPrefix . '$/', $lastRecord->reference_no, $matches)) {
            $lastNumber = intval($matches[1]);
        } else {
            $lastNumber = 0;  // Default to 0 if no records found
        }

        // Increment the last number to create a new number
        $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);

        return $prefix . $newNumber . $yearPrefix;
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
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::class, ['id' => 'customer_id']);
    }

    public function getSaleProduct()
    {
        return $this->hasMany(SaleProduct::class, ['sale_id' => 'id']);
    }
}
