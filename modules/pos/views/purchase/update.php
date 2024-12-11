<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Purchase $model */

$this->title = 'Update Purchase: ' . $model->reference_no;
$this->params['breadcrumbs'][] = ['label' => 'Purchases', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->reference_no, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="purchase-update">

    <?= $this->render('_form', [
        'model' => $model,
        'modelsPurchaseProduct' => $modelsPurchaseProduct,

    ]) ?>

</div>
