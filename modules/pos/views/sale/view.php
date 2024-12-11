<?php

use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Sale $model */

$this->title = $model->reference_no;
$this->params['breadcrumbs'][] = ['label' => 'Sales', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="sale-view">

    <div>
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <!-- Action Buttons -->
                <div class="d-flex justify-content-end mb-3">
                    <a href="<?= Url::to(['/pos/sale/update', 'id' => $model->id]) ?>" class="btn btn-sm btn-outline-primary me-2">
                        <i class="feather-edit"></i> Edit
                    </a>
                    <a href="#" class="btn btn-sm btn-outline-danger delete-btn" data-url="<?= Url::to(['/pos/sale/delete', 'id' => $model->id]) ?>">
                        <i class="feather-trash"></i> Delete
                    </a>
                </div>

                <!-- sale Details -->
                <div class="row g-3">
                    <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                        <strong>Reference No:</strong>
                        <div><?= $model->reference_no ?></div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                        <strong>Supplier:</strong>
                        <div><?= $model->customer->name ?></div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                        <strong>Created By:</strong>
                        <div><?= User::findOne($model->created_by)->username ?? '{not set}' ?></div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                        <strong>Updated By:</strong>
                        <div><?= User::findOne($model->updated_by)->username ?? '{not set}' ?></div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                        <strong>sale Date:</strong>
                        <div><?= Yii::$app->formatter->asDatetime($model->sale_date) ?></div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                        <strong>Created At:</strong>
                        <div><?= Yii::$app->formatter->asDatetime($model->created_at) ?></div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-2">
                        <strong>Updated At:</strong>
                        <div><?= Yii::$app->formatter->asDatetime($model->updated_at) ?></div>
                    </div>
                </div>

                <!-- Sales Details -->
                <h4 class="mt-4">Sales Details</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Selling Price (Per Unit)</th>
                                <th>Total Amount</th>
                                <th>Payment Method</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $totalAmount = 0;
                            foreach ($model->saleProduct as $index => $sale) :
                                $totalAmount += $sale->total_cost;
                            ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= Html::encode($sale->product->name ?? 'Unknown') ?></td>
                                    <td><?= $sale->quantity ?></td>
                                    <td><?= Yii::$app->formatter->asCurrency($sale->selling_price) ?></td>
                                    <td><?= Yii::$app->formatter->asCurrency($sale->total_cost) ?></td>
                                    <td><?= Html::encode($sale->paymentMethod->name ?? 'Unknown') ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Total:</strong></td>
                                <td colspan="3"><strong><?= Yii::$app->formatter->asCurrency($totalAmount) ?></strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>