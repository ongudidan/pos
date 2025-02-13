<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Supplier $model */

$this->title = 'Update Supplier: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Suppliers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="supplier-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
