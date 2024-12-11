<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "product_sub_category".
 *
 * @property string $id
 * @property string|null $product_category_id
 * @property string|null $company_id
 * @property string $name
 * @property string|null $description
 * @property string|null $thumbnail
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Company $company
 * @property ProductCategory $productCategory
 * @property Product[] $products
 */
class ProductSubCategory extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_sub_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'name'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['id', 'product_category_id', 'company_id', 'name', 'description', 'thumbnail'], 'string', 'max' => 255],
            [['id'], 'unique'],
            [['product_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductCategory::class, 'targetAttribute' => ['product_category_id' => 'id']],
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
            'product_category_id' => 'Product Category ID',
            'company_id' => 'Company ID',
            'name' => 'Name',
            'description' => 'Description',
            'thumbnail' => 'Thumbnail',
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
     * Gets query for [[ProductCategory]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductCategory()
    {
        return $this->hasOne(ProductCategory::class, ['id' => 'product_category_id']);
    }

    /**
     * Gets query for [[Products]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::class, ['product_sub_category_id' => 'id']);
    }
}
