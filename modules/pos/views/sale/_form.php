<?php

use app\models\Customer;
use kartik\datetime\DateTimePicker;
use kartik\select2\Select2;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\Sale $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="row">
    <div class="col-sm-12">
        <div class="card comman-shadow">
            <div class="card-body">
                <div class="row">

                    <?php $form = ActiveForm::begin([
                        'id' => 'dynamic-form',
                        'enableAjaxValidation' => false,
                        'method' => 'post',

                    ]); ?>
                    <div class="row">
                        <div class="col-12 col-sm-4">
                            <div class="form-group local-forms">
                                <?= $form->field($model, 'customer_id')->widget(Select2::classname(), [
                                    'data' => ArrayHelper::map(Customer::find()->where(['company_id' => Yii::$app->user->identity->company_id])->all(), 'id', 'name'),
                                    'language' => 'en',
                                    'options' => ['placeholder' => 'Select Customer ...'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ]);

                                ?>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="form-group local-forms">
                                <?= $form->field($model, 'date')->widget(DateTimePicker::classname(), [
                                    'options' => [
                                        'placeholder' => 'Enter sale date...',
                                        'class' => 'form-control',  // Add the form-control class to the input
                                        'value' => isset($model->sale_date) && $model->sale_date > 0
                                            ? date('d-M-Y H:i', $model->sale_date)
                                            : date('d-M-Y H:i'),
                                    ],
                                    'pluginOptions' => [
                                        'autoclose' => true,
                                        'format' => 'dd-M-yyyy hh:ii', // Set the date format to 'dd-M-yyyy' and include time
                                        'todayHighlight' => true, // Highlight today's date
                                        'todayBtn' => true, // Add a button to quickly select today's date and time
                                        'minuteStep' => 1, // Optional: set minute interval for time picker
                                    ]
                                ]);
                                ?>
                            </div>
                        </div>

                        <div class="col-12 col-sm-4">
                            <div class="form-group local-forms">
                                <?= $form->field($model, 'reference_no')->textInput([
                                    // Check if the model is new or existing
                                    'value' => $model->isNewRecord ? $model->generateReferenceNo() : $model->reference_no,
                                ]) ?>
                            </div>
                        </div>
                    </div>

                    <?= $this->render(
                        'components/_sale-dynamic-form.php',
                        [
                            'modelsSaleProduct' => $modelsSaleProduct,
                            'form' => $form,
                            'model' => $model,


                        ]
                    ) ?>

                    <?php ActiveForm::end(); ?>

                </div>
            </div>
        </div>
    </div>
</div>