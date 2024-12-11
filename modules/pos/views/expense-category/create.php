<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ExpenseCategory $model */

$this->title = 'Create Expense Category';
$this->params['breadcrumbs'][] = ['label' => 'Expense Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expense-category-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
