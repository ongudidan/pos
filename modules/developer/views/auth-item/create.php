<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\AuthItem $model */

$this->title = 'Create Auth Item';
$this->params['breadcrumbs'][] = ['label' => 'Auth Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-create">


    <?= $this->render('_form', [
        'model' => $model,
        'authItemsGrouped' => $authItemsGrouped,

    ]) ?>

</div>
