<?php

use app\models\AuthItem;
use app\models\User;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\AuthAssignment $model */
/** @var yii\widgets\ActiveForm $form */
?>

<?php
$formAction = Yii::$app->controller->action->id === 'update'
    ? ['auth-assignment/update', 'id' => $model->id]
    : ['auth-assignment/create']; // Use 'create' action if it's not update
?>

<?php $form = ActiveForm::begin([
    'id' => 'main-form',
    'enableAjaxValidation' => false, // Disable if you're not using AJAX
    'action' => $formAction, // Set action based on create or update
    'method' => 'post',
]); ?>

<div class="row">
    <div class="col-sm-12">
        <div class="card comman-shadow">
            <div class="card-body">

                <div class="row">


                    <div class="col-12 col-sm-12">
                        <div class="form-group local-forms">
                            <?= $form->field($model, 'user_id')->widget(Select2::classname(), [
                                'data' => ArrayHelper::map(User::find()->all(), 'id', 'username'),
                                'language' => 'en',
                                'options' => ['placeholder' => 'Select user ...'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]);

                            ?>
                        </div>
                    </div>

                    <div class="col-12 col-sm-12">
                        <div class="form-group local-forms">
                            <?= $form->field($model, 'item_name')->widget(Select2::classname(), [
                                'data' => ArrayHelper::map(AuthItem::find()->where(['type' => '1'])->all(), 'name', 'name'),
                                'language' => 'en',
                                'options' => ['placeholder' => 'Select auth item ...'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]);

                            ?>
                        </div>
                    </div>


                    <div class="col-12">
                        <div class="student-submit d-flex justify-content-center">
                            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'form' => 'main-form']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>