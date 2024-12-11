<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Purchase $model */

$this->title = 'Create Purchase';
$this->params['breadcrumbs'][] = ['label' => 'Purchases', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchase-create">


    <?= $this->render('_form', [
        'model' => $model,
        'modelsPurchaseProduct' => $modelsPurchaseProduct,
    ]) ?>

</div>
