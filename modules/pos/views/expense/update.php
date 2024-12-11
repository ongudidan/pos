<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Expense $model */

$this->title = 'Update Expense: ' . $model->reference_no;
$this->params['breadcrumbs'][] = ['label' => 'Expenses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->reference_no, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="expense-update">

    <?= $this->render('_form', [
        'model' => $model,
        'modelsExpenseItem' => $modelsExpenseItem,
    ]) ?>

</div>
