<?php

use app\models\PaymentMethod;
use app\models\Product;
use kartik\select2\Select2;
use Yii2\Extensions\DynamicForm\DynamicFormWidget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\modules\pos\models\JobCard $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class=" mt-4">
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
                'model' => $modelsSaleProduct[0],
                'formId' => 'dynamic-form',
                'formFields' => [
                    'product_id',
                    'quantity',
                    'selling_price',
                    'total_cost',
                    'sale_date',
                    'created_by',
                    'updated_by',
                    'payment_method_id',
                ],
            ]); ?>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Sell Price</th>
                            <th>Total Amount</th>
                            <th>Payment Method</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="container-items">
                        <?php foreach ($modelsSaleProduct as $i => $modelSaleProduct): ?>
                            <tr class="item">
                                <?php if (!$modelSaleProduct->isNewRecord): ?>
                                    <?= Html::activeHiddenInput($modelSaleProduct, "[{$i}]sale_id"); ?>
                                <?php endif; ?>

                                <td>
                                    <?= $form->field($modelSaleProduct, "[{$i}]product_id", ['template' => "{input}\n{error}"])
                                        ->widget(Select2::classname(), [
                                            'data' => ArrayHelper::map(Product::find()->all(), 'id', 'name'),
                                            'options' => ['placeholder' => 'Select product ...', 'class' => 'form-select product-field'],
                                            'pluginOptions' => ['allowClear' => true],
                                        ]); ?>
                                </td>
                                <td>
                                    <?= $form->field($modelSaleProduct, "[{$i}]quantity", ['template' => "{input}\n{error}"])
                                        ->textInput(['type' => 'number', 'min' => 1, 'class' => 'form-control quantity-field']); ?>
                                </td>
                                <td>
                                    <?= $form->field($modelSaleProduct, "[{$i}]selling_price", ['template' => "{input}\n{error}"])
                                        ->textInput(['type' => 'number', 'step' => '0.01', 'class' => 'form-control sell-price-field']); ?>
                                </td>
                                <td>
                                    <?= $form->field($modelSaleProduct, "[{$i}]total_cost", ['template' => "{input}\n{error}"])
                                        ->textInput(['readonly' => true, 'class' => 'form-control total-amount-field']); ?>
                                </td>
                                <td>
                                    <?= $form->field($modelSaleProduct, "[{$i}]payment_method_id", ['template' => "{input}\n{error}"])
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
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
    </div>
</div>


<?php
$this->registerJs(<<<JS
// Debounce function to limit rapid AJAX calls
function debounce(func, wait) {
    let timeout;
    return function (...args) {
        const context = this;
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(context, args), wait);
    };
}

// Update total amount
function updateTotalAmount(row) {
    const quantity = parseFloat(row.find('.quantity-field').val()) || 1; // Default quantity to 1
    const sellPrice = parseFloat(row.find('.sell-price-field').val()) || 0;
    const totalAmount = quantity * sellPrice;
    row.find('.total-amount-field').val(totalAmount.toFixed(2)); // Limit to 2 decimal places
}

// Delegate event listeners to the table body for better performance
const container = $('.container-items');

// Handle product selection
container.on('change', '.product-field', debounce(function () {
    const row = $(this).closest('.item');
    const productId = $(this).val();

    // Set default quantity to 1 if empty
    if (!row.find('.quantity-field').val()) {
        row.find('.quantity-field').val(1);
    }

    if (productId) {
        $.ajax({
            url: '/pos/sale/get-product-details',
            type: 'GET',
            data: { id: productId },
            success: function (response) {
                const data = JSON.parse(response);
                if (data && data.price) {
                    row.find('.sell-price-field').val(data.price);
                    updateTotalAmount(row);
                }
            },
            error: function () {
                alert('Unable to fetch product details.');
            }
        });
    } else {
        row.find('.sell-price-field').val('');
        row.find('.total-amount-field').val('');
    }
}, 300)); // Debounce time: 300ms

// Handle quantity input change
container.on('input', '.quantity-field', function () {
    const row = $(this).closest('.item');
    updateTotalAmount(row);
});

// Handle sell price input change
container.on('input', '.sell-price-field', function () {
    const row = $(this).closest('.item');
    updateTotalAmount(row);
});

JS);
?>