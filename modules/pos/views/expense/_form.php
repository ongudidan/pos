<?php

use kartik\datetime\DateTimePicker;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Expense $model */
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
                  
                        <div class="col-12 col-sm-6">
                            <div class="form-group local-forms">
                                <?= $form->field($model, 'date')->widget(DateTimePicker::classname(), [
                                    'options' => [
                                        'placeholder' => 'Enter Expense date...',
                                        'class' => 'form-control',  // Add the form-control class to the input
                                        'value' => isset($model->purchase_date) && $model->purchase_date > 0
                                            ? date('d-M-Y H:i', $model->purchase_date)
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

                        <div class="col-12 col-sm-6">
                            <div class="form-group local-forms">
                                <?= $form->field($model, 'reference_no')->textInput([
                                    // Check if the model is new or existing
                                    'value' => $model->isNewRecord ? $model->generateReferenceNo() : $model->reference_no,
                                ]) ?>
                            </div>
                        </div>
                    </div>



                    <?= $this->render(
                        'components/_expense-dynamic-form.php',
                        [
                            'modelsExpenseItem' => $modelsExpenseItem,
                            'form' => $form,

                        ]
                    ) ?>



                    <?php ActiveForm::end(); ?>

                </div>
            </div>
        </div>
    </div>
</div>