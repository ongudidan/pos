<?php

use app\models\ProductCategory;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ProductSubCategory $model */
/** @var yii\widgets\ActiveForm $form */
?>


<?php
$formAction = Yii::$app->controller->action->id === 'update'
    ? ['product-sub-category/update', 'id' => $model->id]
    : ['product-sub-category/create']; // Use 'create' action if it's not update
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
                    <div class="col-12 col-sm-4">
                        <div class="form-group local-forms">
                            <?= $form->field($model, 'product_category_id')->widget(Select2::classname(), [
                                'data' => ArrayHelper::map(ProductCategory::find()->all(), 'id', 'name'),
                                'language' => 'en',
                                'options' => ['placeholder' => 'Select Product Category ...'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]);

                            ?>
                        </div>
                    </div>

                    <div class="col-12 col-sm-4">
                        <div class="form-group local-forms">
                            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>

                    <div class="col-12 col-sm-4">
                        <div class="form-group local-forms">
                            <?= $form->field($model, 'thumbnail')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>

                    <div class="col-12 col-sm-12">
                        <div class="form-group local-forms">
                            <?= $form->field($model, 'description')->textarea(['maxlength' => true]) ?>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="student-submit d-flex justify-content-center">
                            <?= Html::submitButton('Save', ['class' => 'btn btn-primary', 'form' => 'main-form']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>