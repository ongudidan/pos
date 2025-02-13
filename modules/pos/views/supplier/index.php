<?php

use app\models\Supplier;
use app\models\User;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\modules\pos\models\SupplierSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Suppliers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplier-index">

    <div class="row-group-form">
        <div class="row">
            <?php $form = ActiveForm::begin([
                'method' => 'get',
                'action' => Url::to(['/pos/supplier/index']),
                'options' => ['class' => 'row'],
            ]); ?>

            <div class="col-lg-4 col-md-6">
                <?= $form->field($searchModel, 'name', [
                    'options' => ['class' => 'form-group'],
                ])->textInput([
                    'class' => 'form-control',
                    'placeholder' => 'Name ...',
                ])->label(false); ?>
            </div>
            <div class="col-lg-3 col-md-6">
                <?= $form->field($searchModel, 'email', [
                    'options' => ['class' => 'form-group'],
                ])->textInput([
                    'class' => 'form-control',
                    'placeholder' => 'Email ...',
                ])->label(false); ?>
            </div>
            <div class="col-lg-3 col-md-6">
                <?= $form->field($searchModel, 'phone_no', [
                    'options' => ['class' => 'form-group'],
                ])->textInput([
                    'class' => 'form-control',
                    'placeholder' => 'Phone Number ...',
                ])->label(false); ?>
            </div>

            <div class="col-lg-2">
                <div class="search-student-btn">
                    <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table comman-shadow">
                <div class="card-body">
                    <div class="page-header">
                        <div class="row align-items-center">
                            <div class="col-auto text-end float-end ms-auto download-grp">
                                <a href="<?= Url::to('/pos/supplier/create') ?>" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm mb-0"> <!-- Added 'table-sm' for smaller padding -->
                            <thead class="student-thread "> <!-- Apply 'small' class for smaller font size in the header -->
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone number</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>created By</th>
                                    <th>Updated By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class=""> <!-- Apply 'small' class for smaller font size in the body -->
                                <?php if ($dataProvider->getCount() > 0): ?>
                                    <?php foreach ($dataProvider->getModels() as $index => $row):
                                        $createdBy = User::findOne($row->created_by);
                                        $updatedBy = User::findOne($row->updated_by);
                                    ?>
                                        <tr>
                                            <td><?= $dataProvider->pagination->page * $dataProvider->pagination->pageSize + $index + 1 ?></td>
                                            <td><?= $row->name ?></td>
                                            <td><?= $row->email ?? '' ?></td>
                                            <td><?= $row->phone_no ?? '' ?></td>
                                            <td><?= Yii::$app->formatter->asDatetime($row->created_at) ?></td>
                                            <td><?= Yii::$app->formatter->asDatetime($row->updated_at) ?></td>
                                            <td><?= $createdBy ? Html::encode($createdBy->username) : 'Admin' ?></td>
                                            <td><?= $updatedBy ? Html::encode($updatedBy->username) : 'Admin' ?></td>

                                            <td>
                                                <div class="dropdown d-inline">
                                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item has-icon" href="<?= Url::to(['/pos/supplier/view', 'id' => $row->id]) ?>">
                                                            <i class="feather-eye"></i> View
                                                        </a>
                                                        <a class="dropdown-item has-icon" href="<?= Url::to(['/pos/supplier/update', 'id' => $row->id]) ?>">
                                                            <i class="feather-edit"></i> Update
                                                        </a>
                                                        <a class="dropdown-item has-icon delete-btn" href="#" data-url="<?= Url::to(['/pos/supplier/delete', 'id' => $row->id]) ?>">
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