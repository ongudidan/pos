<?php

use app\models\Company;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\User $model */
/** @var yii\widgets\ActiveForm $form */
?>

<?php
$formAction = Yii::$app->controller->action->id === 'update'
    ? ['user/update', 'id' => $model->id]
    : ['user/create']; // Use 'create' action if it's not update
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
                    <div class="col-12 col-sm-6">
                        <div class="form-group local-forms">
                            <?= $form->field($model, 'company_id')->widget(Select2::classname(), [
                                'data' => ArrayHelper::map(Company::find()->all(), 'id', 'name'),
                                'language' => 'en',
                                'options' => ['placeholder' => 'Select user ...'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]);

                            ?>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="form-group local-forms">
                            <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="form-group local-forms">
                            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="form-group local-forms">
                            <?= $form->field($model, 'phone_no')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="form-group local-forms">
                            <?= $form->field($model, 'status')->dropDownList([
                                '10' => 'Active',
                                '9' => 'Inactive',

                            ]) ?>
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