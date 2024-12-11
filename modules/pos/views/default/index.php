<?php

use app\models\Customer;
use app\models\Product;
use app\models\Supplier;
use app\models\User;

$this->title = 'POS Dashboard';
$this->params['breadcrumbs'][] = $this->title;
?>
<div>
    <div class="row">
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6>Customers</h6>
                            <h3><?= number_format(Customer::find()->where(['company_id' => Yii::$app->user->identity->company_id])->count()) ?? 0  ?></h3>
                        </div>
                        <div class="db-icon">
                            <i class="fas fa-users fa-3x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6>Suppliers</h6>
                            <h3><?= number_format(Supplier::find()->where(['company_id' => Yii::$app->user->identity->company_id])->count()) ?? 0  ?></h3>
                        </div>
                        <div class="db-icon">
                            <i class="fas fa-truck fa-3x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6>Products</h6>
                            <h3><?= number_format(Product::find()->where(['company_id' => Yii::$app->user->identity->company_id])->count()) ?? 0  ?></h3>
                        </div>
                        <div class="db-icon">
                            <i class="fas fa-box fa-3x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6>Staffs</h6>
                            <h3><?= number_format(User::find()->where(['company_id' => Yii::$app->user->identity->company_id])->count()) ?? 0  ?></h3>
                        </div>
                        <div class="db-icon">
                            <i class="fas fa-user-tie fa-3x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="row">
        <div class="col-md-12 col-lg-6">

            <div class="card card-chart">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="card-title"><?= date('Y'); ?> Sales & Purchases</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="apexcharts-area"></div>
                </div>
            </div>

        </div>
        <div class="col-md-12 col-lg-6">

            <div class="card card-chart">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="card-title"><?= date('F Y'); ?></h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="donut-chart"></div>
                </div>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 d-flex">
            <div class="card flex-fill student-space comman-shadow">
                <div class="card-header d-flex align-items-center">
                    <h5 class="card-title">This Week Report</h5>
                    <ul class="chart-list-out student-ellips">
                        <li class="star-menus"><a href="javascript:;"><i class="fas fa-ellipsis-v"></i></a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table star-student table-hover table-center table-borderless">
                            <thead>
                                <tr>
                                    <th scope="col">Day</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Products Sold</th>
                                    <th scope="col">Sales</th>
                                    <th scope="col">Expenses</th>
                                    <th scope="col">Profit</th>
                                    <th scope="col">Net Profit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $totalProductsSold = 0;
                                $totalSales = 0;
                                $totalExpenses = 0;
                                $totalProfit = 0;
                                $totalNetProfit = 0;

                                // Define a list of unique colors
                                $rowColors = [
                                    'table-cyan',     // Light Cyan
                                    'table-lime',     // Light Lime
                                    'table-amber',    // Light Amber
                                    'table-teal',     // Light Teal
                                    'table-pink',     // Light Pink
                                    'table-orange',   // Light Orange
                                    'table-indigo',   // Light Indigo
                                    'table-brown'     // Light Brown
                                ];


                                // Keep track of used colors
                                $usedColors = [];

                                foreach ($reportData as $data):
                                    // Accumulate totals
                                    $totalProductsSold += $data['products_sold'] ?? 0;
                                    $totalSales += $data['sales'] ?? 0;
                                    $totalExpenses += $data['expenses'] ?? 0;
                                    $totalProfit += $data['profit'] ?? 0;
                                    $totalNetProfit += $data['net_profit'] ?? 0;

                                    // Assign a unique color
                                    foreach ($rowColors as $color) {
                                        if (!in_array($color, $usedColors)) {
                                            $rowClass = $color;
                                            $usedColors[] = $color;
                                            break;
                                        }
                                    }
                                ?>
                                    <tr class="<?= $rowClass ?>">
                                        <th scope="row"><?= strtoupper(substr($data['day'], 0, 3)) ?></th>
                                        <td><?= Yii::$app->formatter->asDate($data['date'], 'php:Y-m-d') ?></td>
                                        <td><?= $data['products_sold'] ?? 0 ?></td>
                                        <td><?= Yii::$app->formatter->asCurrency($data['sales'] ?? 0, 'KES') ?></td>
                                        <td><?= Yii::$app->formatter->asCurrency($data['expenses'] ?? 0, 'KES') ?></td>
                                        <td><?= Yii::$app->formatter->asCurrency($data['profit'] ?? 0, 'KES') ?></td>
                                        <td><?= Yii::$app->formatter->asCurrency($data['net_profit'] ?? 0, 'KES') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="7">&nbsp;</td>
                                </tr>
                                <tr style="font-size: 1.1em; font-weight: bold; text-transform: uppercase;">
                                    <th scope="row" colspan="2">Total</th>
                                    <td><?= $totalProductsSold ?></td>
                                    <td><?= Yii::$app->formatter->asCurrency($totalSales, 'KES') ?></td>
                                    <td><?= Yii::$app->formatter->asCurrency($totalExpenses, 'KES') ?></td>
                                    <td><?= Yii::$app->formatter->asCurrency($totalProfit, 'KES') ?></td>
                                    <td><?= Yii::$app->formatter->asCurrency($totalNetProfit, 'KES') ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>