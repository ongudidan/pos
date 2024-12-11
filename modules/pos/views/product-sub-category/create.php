<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ProductSubCategory $model */

$this->title = 'Create Product Sub Category';
$this->params['breadcrumbs'][] = ['label' => 'Product Sub Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-sub-category-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
