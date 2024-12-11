<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Sale $model */

$this->title = 'Create Sale';
$this->params['breadcrumbs'][] = ['label' => 'Sales', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sale-create">


    <?= $this->render('_form', [
        'model' => $model,
        'modelsSaleProduct' => $modelsSaleProduct,

    ]) ?>

</div>
