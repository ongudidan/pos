<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Sale $model */

$this->title = 'Update Sale: ' . $model->reference_no;
$this->params['breadcrumbs'][] = ['label' => 'Sales', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->reference_no, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sale-update">

    <?= $this->render('_form', [
        'model' => $model,
        'modelsSaleProduct' => $modelsSaleProduct,
    ]) ?>

</div>
