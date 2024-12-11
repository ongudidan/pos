<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "customer".
 *
 * @property string $id
 * @property string|null $company_id
 * @property string $name
 * @property string|null $email
 * @property string|null $phone_no
 * @property string|null $company_name
 * @property string|null $address
 * @property string|null $city
 * @property string|null $country
 * @property string|null $postal_code
 * @property string|null $state
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Company $company
 * @property Sale[] $sales
 */
class Customer extends \yii\db\ActiveRecord
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
        return 'customer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'name'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['id', 'company_id', 'name', 'email', 'phone_no', 'company_name', 'address', 'city', 'country', 'postal_code', 'state'], 'string', 'max' => 255],
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
            'email' => 'Email',
            'phone_no' => 'Phone No',
            'company_name' => 'Company Name',
            'address' => 'Address',
            'city' => 'City',
            'country' => 'Country',
            'postal_code' => 'Postal Code',
            'state' => 'State',
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
     * Gets query for [[Sales]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSales()
    {
        return $this->hasMany(Sale::class, ['customer_id' => 'id']);
    }
}
