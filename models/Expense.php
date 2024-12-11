<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "expense".
 *
 * @property string $id
 * @property string|null $company_id
 * @property string|null $reference_no
 * @property int|null $expense_date
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 *
 * @property Company $company
 * @property ExpenseItem[] $expenseItems
 */
class Expense extends \yii\db\ActiveRecord
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
        return 'expense';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // [['id'], 'required'],
            [['expense_date', 'created_at', 'updated_at'], 'integer'],
            [['id', 'company_id', 'date', 'reference_no', 'created_by', 'updated_by'], 'string', 'max' => 255],
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
            'reference_no' => 'Reference No',
            'expense_date' => 'Expense Date',
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
     * Gets query for [[ExpenseItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpenseItems()
    {
        return $this->hasMany(ExpenseItem::class, ['expense_id' => 'id']);
    }
}
