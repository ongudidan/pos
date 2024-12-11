<?php

use app\models\ProductCategory;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\modules\pos\models\productCategoryCategorySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Product Categories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="productCategory-category-index">

    <div class="productCategory-group-form">
        <div class="row">
            <form method="get" action="<?= Url::to(['/pos/product-category/index']) ?>">
                <div class="row">

                    <div class="col-lg-10 col-md-6">
                        <div class="form-group">
                            <input type="text" name="ProductCategorySearch[name]" class="form-control" placeholder="Category Name ..." value="<?= Html::encode($searchModel->name) ?>">
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="search-student-btn">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table comman-shadow">
                <div class="card-body">
                    <div class="page-header">
                        <div class="row align-items-center">
                            <div class="col-auto text-end float-end ms-auto download-grp">
                                <a href="<?= Url::to('/pos/product-category/create') ?>" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm mb-0"> <!-- Added 'table-sm' for smaller padding -->
                            <thead class="student-thread "> <!-- Apply 'small' class for smaller font size in the header -->
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>

                                    <th>Created At</th>
                                    <th>Updated At</th>

                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class=""> <!-- Apply 'small' class for smaller font size in the body -->
                                <?php if ($dataProvider->getCount() > 0): ?>
                                    <?php foreach ($dataProvider->getModels() as $index => $productCategory):
                                        // $totalSalesQuantity = Sales::find()->where(['productCategory_id' => $productCategory->productCategory_id])->sum('quantity');
                                        // $totalPurchasesQuantity = Purchases::find()->where(['productCategory_id' => $productCategory->productCategory_id])->sum('quantity');
                                        // $totalQuantity = $totalPurchasesQuantity - $totalSalesQuantity;
                                        // $canManage = Yii::$app->user->can('Sales Manage');
                                    ?>
                                        <tr>
                                            <td><?= $dataProvider->pagination->page * $dataProvider->pagination->pageSize + $index + 1 ?></td>
                                            <td><?= $productCategory->name ?></td>
                                            <td><?= Yii::$app->formatter->asDatetime($productCategory->created_at) ?></td>
                                            <td><?= Yii::$app->formatter->asDatetime($productCategory->updated_at) ?></td>
                                            <td>
                                                <div class="dropdown d-inline">
                                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item has-icon" href="<?= Url::to(['/pos/product-category/view', 'id' => $productCategory->id]) ?>">
                                                            <i class="feather-eye"></i> View
                                                        </a>
                                                        <a class="dropdown-item has-icon" href="<?= Url::to(['/pos/product-category/update', 'id' => $productCategory->id]) ?>">
                                                            <i class="feather-edit"></i> Update
                                                        </a>
                                                        <a class="dropdown-item has-icon delete-btn" href="#" data-url="<?= Url::to(['/pos/product-category/delete', 'id' => $productCategory->id]) ?>">
                                                            <i class="feather-trash"></i> Delete
                                                        </a>

                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="10" class="text-center">No data found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <!-- Pagination inside the table container -->
                        <div class="pagination-wrapper mt-3">
                            <?= \app\components\CustomLinkPager::widget([
                                'pagination' => $dataProvider->pagination,
                                'options' => ['class' => 'pagination justify-content-center mb-4'],
                                'linkOptions' => ['class' => 'page-link'],
                                'activePageCssClass' => 'active',
                                'disabledPageCssClass' => 'disabled',
                                'prevPageLabel' => '<span aria-hidden="true">«</span><span class="sr-only">Previous</span>',
                                'nextPageLabel' => '<span aria-hidden="true">»</span><span class="sr-only">Next</span>',
                            ]); ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>