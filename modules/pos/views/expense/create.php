<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Expense $model */

$this->title = 'Create Expense';
$this->params['breadcrumbs'][] = ['label' => 'Expenses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expense-create">

    <?= $this->render('_form', [
        'model' => $model,
        'modelsExpenseItem' => $modelsExpenseItem,
    ]) ?>

</div>
