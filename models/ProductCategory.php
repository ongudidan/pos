<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "product_category".
 *
 * @property string $id
 * @property string|null $company_id
 * @property string $name
 * @property string|null $description
 * @property string|null $thumbnail
 * @property int $created_at
 * @property int $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 *
 * @property Company $company
 * @property ProductSubCategory[] $productSubCategories
 */
class ProductCategory extends \yii\db\ActiveRecord
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
        return 'product_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'name'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['id', 'company_id', 'name', 'description', 'thumbnail', 'created_by', 'updated_by'], 'string', 'max' => 255],
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
            'description' => 'Description',
            'thumbnail' => 'Thumbnail',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
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
     * Gets query for [[ProductSubCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductSubCategories()
    {
        return $this->hasMany(ProductSubCategory::class, ['product_category_id' => 'id']);
    }
}
