<?php

use app\models\AuthAssignment;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\modules\developer\models\AuthAssignmentSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Auth Assignments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-assignment-index">

    <div class="product-group-form">
        <div class="row">
            <form method="get" action="<?= Url::to(['/developer/auth-assignment/index']) ?>">
                <div class="row">
                    <div class="col-lg-5 col-md-6">
                        <div class="form-group">
                            <input type="text" name="AuthAssignmentSearch[item_name]" class="form-control" placeholder="Auth item name ..." value="<?= Html::encode($searchModel->item_name) ?>">
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-6">
                        <div class="form-group">
                            <input type="text" name="AuthAssignmentSearch[user_id]" class="form-control" placeholder="User username ..." value="<?= Html::encode($searchModel->user_id) ?>">
                        </div>
                    </div>


                    <div class="col-lg-1">
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
                                <a href="<?= Url::to('/developer/auth-assignment/create') ?>" class="btn btn-primary"><i
                                        class="fas fa-plus"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="student-thread">
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Item Name</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($dataProvider->getCount() > 0): ?>
                                    <?php foreach ($dataProvider->getModels() as $index => $authAssignment): ?>
                                        <tr>
                                            <td><?= $dataProvider->pagination->page * $dataProvider->pagination->pageSize + $index + 1 ?></td>
                                            <td><?= Html::encode($authAssignment->user->username) ?></td>
                                            <td><?= Html::encode($authAssignment->item_name) ?></td>


                                            <td class="text-center">
                                                <div class="dropdown d-inline">
                                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item has-icon" href="<?= Url::to(['/developer/auth-assignment/view', 'item_name' => $authAssignment->item_name, 'user_id' => $authAssignment->user_id]) ?>">
                                                            <i class="feather-eye"></i> View
                                                        </a>
                                                        <a class="dropdown-item has-icon" href="<?= Url::to(['/developer/auth-assignment/update', 'item_name' => $authAssignment->item_name, 'user_id' => $authAssignment->user_id]) ?>">
                                                            <i class="feather-eye"></i> Update
                                                        </a>
                                                        <a class="dropdown-item has-icon delete-btn" href="#" data-url="<?= Url::to(['/developer/auth-assignment/delete', 'item_name' => $authAssignment->item_name, 'user_id' => $authAssignment->user_id]) ?>">
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