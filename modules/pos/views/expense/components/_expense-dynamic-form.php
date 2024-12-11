<?php

use app\models\ExpenseCategory;
use app\models\PaymentMethod;
use app\models\PaymentMethods;
use app\models\Product;
use app\models\Products;
use kartik\select2\Select2;
use Yii2\Extensions\DynamicForm\DynamicFormWidget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\modules\dashboard\models\JobCard $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="mt-4">
    <div class="card shadow-sm">
        <div class="card-header text-white">
            <h5 class="mb-0">
                <i class="fas fa-list-alt"></i> Items
            </h5>
        </div>
        <div class="card-body">
            <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper',
                'widgetBody' => '.container-items',
                'widgetItem' => '.item',
                'limit' => 50000,
                'min' => 1,
                'insertButton' => '.add-item',
                'deleteButton' => '.remove-item',
                'model' => $modelsExpenseItem[0],
                'formId' => 'dynamic-form',
                'formFields' => [
                    'expense_category_id',
                    'amount',
                    'payment_method_id',
                ],
            ]); ?>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Expense Category</th>
                            <th>amount</th>
                            <th>Payment Method</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="container-items">
                        <?php foreach ($modelsExpenseItem as $i => $modelExpenseItem): ?>
                            <tr class="item">
                                <?php if (!$modelExpenseItem->isNewRecord): ?>
                                    <?= Html::activeHiddenInput($modelExpenseItem, "[{$i}]expense_id"); ?>
                                <?php endif; ?>

                                <td>
                                    <?= $form->field($modelExpenseItem, "[{$i}]expense_category_id", ['template' => "{input}\n{error}"])
                                        ->widget(Select2::classname(), [
                                            'data' => ArrayHelper::map(ExpenseCategory::find()->all(), 'id', 'name'),
                                            'options' => ['placeholder' => 'Select Expense Category ...', 'class' => 'form-select'],
                                            'pluginOptions' => ['allowClear' => true],
                                        ]); ?>
                                </td>
                                <td>
                                    <?= $form->field($modelExpenseItem, "[{$i}]amount", ['template' => "{input}\n{error}"])
                                        ->textInput(['type' => 'number', 'min' => 1, 'class' => 'form-control amount-field']); ?>
                                </td>
                                <td>
                                    <?= $form->field($modelExpenseItem, "[{$i}]payment_method_id", ['template' => "{input}\n{error}"])
                                        ->widget(Select2::classname(), [
                                            'data' => ArrayHelper::map(PaymentMethod::find()->all(), 'id', 'name'),
                                            'options' => ['placeholder' => 'Select payment method ...', 'class' => 'form-select'],
                                            'pluginOptions' => ['allowClear' => true],
                                        ]); ?>
                                </td>
                                <td>
                                    <button type="button" class="remove-item btn btn-outline-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                <button type="button" class="add-item btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add Item
                </button>
            </div>

            <?php DynamicFormWidget::end(); ?>
        </div>
    </div>

    <div class="text-center mt-4">
        <button type="button" id="submit-form" class="btn btn-primary">Save</button>
    </div>
</div>

<?php
$this->registerJs(<<<JS
// Calculate total cost on amount or buying price change
$(document).on('input', '.dynamicform_wrapper .amount-field, .dynamicform_wrapper .buying-price-field', function () {
    var row = $(this).closest('.item');
    var amount = parseFloat(row.find('.amount-field').val()) || 0;
    var buyingPrice = parseFloat(row.find('.buying-price-field').val()) || 0;
    var totalCost = amount * buyingPrice;
    row.find('.total-cost-field').val(totalCost.toFixed(2));
});

// Submit form when the save button is clicked
$('#submit-form').on('click', function () {
    $('#dynamic-form').submit(); // Submit the form directly
});
JS);
?>