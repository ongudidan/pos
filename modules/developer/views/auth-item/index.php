<?php

use app\models\AuthItem;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\modules\developer\models\AuthItemSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Auth Items';
$this->params['breadcrumbs'][] = $this->title;


// Register custom CSS for gradient buttons
$this->registerCss("
.gradient-button {
color: white;
border: none;
padding: 10px 20px;
border-radius: 5px;
text-decoration: none;
transition: background 0.3s ease;
}
.btn-gradient-1 {
background: linear-gradient(45deg, #d9534f, #a94442); /* Darker red */
}
.btn-gradient-2 {
background: linear-gradient(45deg, #5bc0de, #31b0d5); /* Darker blue */
}
.btn-gradient-3 {
background: linear-gradient(45deg, #5cb85c, #4cae4c); /* Darker green */
}
.btn-gradient-4 {
background: linear-gradient(45deg, #f0ad4e, #ec971f); /* Darker orange */
}
.btn-gradient-5 {
background: linear-gradient(45deg, #d9534f, #c9302c); /* Darker pink */
}
.gradient-button:hover {
opacity: 0.8; /* Slightly reduce opacity on hover */
}
");

?>
<div class="auth-item-index">

    <div class="product-group-form">
        <div class="row">
            <form method="get" action="<?= Url::to(['/developer/auth-item/index']) ?>">
                <div class="row">

                    <div class="col-lg-10 col-md-6">
                        <div class="form-group">
                            <input type="text" name="AuthItemSearch[name]" class="form-control" placeholder="Search by name ..." value="<?= Html::encode($searchModel->name) ?>">
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

    <div class="row mb-3">
        <div class="col-sm-12">
            <div class="card card-table comman-shadow">
                <div class="card-body">
                    <!-- Center-aligned link buttons with darker gradient styles -->
                    <div class="mb-3 text-center">
                        <div class="btn-group" role="group">
                            <a href="<?= Url::to(['/developer/auth-item/item-generator']) ?>" class="btn gradient-button btn-gradient-1">Regenerate Items</a>
                             
                        </div>
                    </div>

                    <div class="page-header">
                        <div class="row align-items-center">
                            <div class="col-auto text-end float-end ms-auto download-grp">
                                <a href="<?= Url::to('/developer/auth-item/create') ?>" class="btn btn-primary"><i class="fas fa-plus"></i></a>
                            </div>
                        </div>
                    </div>



                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="student-thread">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($dataProvider->getCount() > 0): ?>
                                    <?php foreach ($dataProvider->getModels() as $index => $authItem): ?>
                                        <tr>
                                            <td><?= $dataProvider->pagination->page * $dataProvider->pagination->pageSize + $index + 1 ?></td>
                                            <td><?= $authItem->name ?></td>
                                            <td><?= $authItem->description ?></td>
                                            <td class="text-end">
                                                <div class="actions">
                                                    <a href="<?= Url::to(['/developer/auth-item/view', 'name' => $authItem->name]) ?>" class="btn btn-sm bg-success-light me-2">
                                                        <i class="feather-eye"></i>
                                                    </a>
                                                    <a href="<?= Url::to(['/developer/auth-item/update', 'name' => $authItem->name]) ?>" class="btn btn-sm bg-danger-light">
                                                        <i class="feather-edit"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-sm bg-danger-light delete-btn" data-url="<?= Url::to(['/developer/auth-item/delete', 'name' => $authItem->name]) ?>">
                                                        <i class="feather-trash"></i>
                                                    </a>
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
                    </div>

                    <!-- pagination -->
                    <div>
                        <ul class="pagination mb-4">
                            <?= LinkPager::widget([
                                'pagination' => $dataProvider->pagination,
                                'options' => ['class' => 'pagination mb-4'],
                                'linkOptions' => ['class' => 'page-link'],
                                'activePageCssClass' => 'active',
                                'disabledPageCssClass' => 'disabled',
                                'prevPageLabel' => '<span aria-hidden="true">«</span><span class="sr-only">Previous</span>',
                                'nextPageLabel' => '<span aria-hidden="true">»</span><span class="sr-only">Next</span>',
                            ]); ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>