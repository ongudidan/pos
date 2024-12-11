<?php

use yii\helpers\Url;

// Get the current module, controller, and action
$module = Yii::$app->controller->module->id;
$controller = Yii::$app->controller->id;
$action = Yii::$app->controller->action->id;

// Define sidebar menu structure with module, controller, and action
$sidebarMenus = [
    [
        'label' => 'Dashboard',
        'url' => Url::to(['/pos/default/index']),
        'icon' => 'fas fa-tachometer-alt',  // dashboard icon
        'module' => 'pos',
        'controller' => 'default',
        'action' => 'index',
    ],
    [
        'label' => 'Products',
        'icon' => 'fas fa-box-open',  // Products icon
        'submenu' => true,
        'active' => $module === 'dashboard' && $controller === 'products',
        'items' => [
            [
                'label' => 'Products List',
                'url' => Url::to(['/pos/product/index']),
                'module' => 'pos',
                'controller' => 'product',
                'action' => 'index',
            ],
            [
                'label' => 'Create Product',
                'url' => Url::to(['/pos/product/create']),
                'module' => 'pos',
                'controller' => 'product',
                'action' => 'create',
            ],
            [
                'label' => 'Category List',
                'url' => Url::to(['/pos/product-category/index']),
                'module' => 'pos',
                'controller' => 'product-category',
                'action' => 'index',
            ],
            [
                'label' => 'Create Category',
                'url' => Url::to(['/pos/product-category/create']),
                'module' => 'pos',
                'controller' => 'product-category',
                'action' => 'create',
            ],
            [
                'label' => 'Sub-Category List',
                'url' => Url::to(['/pos/product-sub-category/index']),
                'module' => 'pos',
                'controller' => 'product-sub-category',
                'action' => 'index',
            ],
            [
                'label' => 'Create Sub-Category',
                'url' => Url::to(['/pos/product-sub-category/create']),
                'module' => 'pos',
                'controller' => 'product-sub-category',
                'action' => 'create',
            ],
        ]
    ],
    [
        'label' => 'Sales',
        'icon' => 'fas fa-chart-line',  // Bulk Sale icon
        'submenu' => true,
        'active' => $module === 'pos' && $controller === 'sale',
        'items' => [
            [
                'label' => 'Sale List',
                'url' => Url::to(['/pos/sale/index']),
                'module' => 'pos',
                'controller' => 'sale',
                'action' => 'index',
            ],
            [
                'label' => 'Create Sale',
                'url' => Url::to(['/pos/sale/create']),
                'module' => 'pos',
                'controller' => 'sale',
                'action' => 'create',
            ],
        ]
    ],
    [
        'label' => 'Purchases',
        'icon' => 'fas fa-shopping-cart',  // Bulk Purchase icon
        'submenu' => true,
        'active' => $module === 'pos' && $controller === 'purchase',
        'items' => [
            [
                'label' => 'Purchase List',
                'url' => Url::to(['/pos/purchase/index']),
                'module' => 'pos',
                'controller' => 'purchase',
                'action' => 'index',
            ],
            [
                'label' => 'Create purchase',
                'url' => Url::to(['/pos/purchase/create']),
                'module' => 'pos',
                'controller' => 'purchase',
                'action' => 'create',
            ],
        ]
    ],
    [
        'label' => 'Expenses',
        'icon' => 'fas fa-receipt',  // Expenses icon
        'submenu' => true,
        'active' => $module === 'pos' && $controller === 'expense',
        'items' => [
            [
                'label' => 'Expense List',
                'url' => Url::to(['/pos/expense/index']),
                'module' => 'pos',
                'controller' => 'expense',
                'action' => 'index',
            ],
            [
                'label' => 'Create Expense',
                'url' => Url::to(['/pos/expense/create']),
                'module' => 'pos',
                'controller' => 'expense',
                'action' => 'create',
            ],
            [
                'label' => 'Expense Categories List',
                'url' => Url::to(['/pos/expense-category/index']),
                'module' => 'pos',
                'controller' => 'expense-category',
                'action' => 'index',
            ],
            [
                'label' => 'Create Expense Category',
                'url' => Url::to(['/pos/expense-category/create']),
                'module' => 'pos',
                'controller' => 'expense-category',
                'action' => 'create',
            ],
        ]
    ],
    [
        'label' => 'Payment Methods',
        'icon' => 'fas fa-shopping-cart',  // Bulk Payment Method icon
        'submenu' => true,
        'active' => $module === 'pos' && $controller === 'payment-method',
        'items' => [
            [
                'label' => 'Payment Method List',
                'url' => Url::to(['/pos/payment-method/index']),
                'module' => 'pos',
                'controller' => 'payment-method',
                'action' => 'index',
            ],
            [
                'label' => 'Create payment-method',
                'url' => Url::to(['/pos/payment-method/create']),
                'module' => 'pos',
                'controller' => 'payment-method',
                'action' => 'create',
            ],
        ]
    ],
    [
        'label' => 'Supplier',
        'icon' => 'fas fa-shopping-cart',  // Supplier icon
        'submenu' => true,
        'active' => $module === 'pos' && $controller === 'supplier',
        'items' => [
            [
                'label' => 'Suppliers List',
                'url' => Url::to(['/pos/supplier/index']),
                'module' => 'pos',
                'controller' => 'supplier',
                'action' => 'index',
            ],
            [
                'label' => 'Create supplier',
                'url' => Url::to(['/pos/supplier/create']),
                'module' => 'pos',
                'controller' => 'supplier',
                'action' => 'create',
            ],
        ]
    ],
    [
        'label' => 'Customer',
        'icon' => 'fas fa-shopping-cart',  // Customer icon
        'submenu' => true,
        'active' => $module === 'pos' && $controller === 'customer',
        'items' => [
            [
                'label' => 'Customers List',
                'url' => Url::to(['/pos/customer/index']),
                'module' => 'pos',
                'controller' => 'customer',
                'action' => 'index',
            ],
            [
                'label' => 'Create customer',
                'url' => Url::to(['/pos/customer/create']),
                'module' => 'pos',
                'controller' => 'customer',
                'action' => 'create',
            ],
        ]
    ],
];



?>
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title">
                    <span>Main Dashboard</span>
                </li>

                <?php foreach ($sidebarMenus as $menu): ?>
                    <?php if (isset($menu['submenu']) && $menu['submenu']): ?>
                        <!-- Submenu -->
                        <li class="submenu <?= $menu['active'] ? 'active' : '' ?>">
                            <a href="#"><i class="<?= $menu['icon'] ?>"></i> <span> <?= $menu['label'] ?> </span> <span class="menu-arrow"></span></a>
                            <ul>
                                <?php foreach ($menu['items'] as $subItem): ?>
                                    <li>
                                        <a href="<?= $subItem['url'] ?>"
                                            class="<?= ($module == $subItem['module'] && $controller == $subItem['controller'] && $action == $subItem['action']) ? 'active' : '' ?>">
                                            <?= $subItem['label'] ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php else: ?>
                        <!-- Regular Menu -->
                        <li class="<?= ($module == $menu['module'] && $controller == $menu['controller'] && $action == $menu['action']) ? 'active' : '' ?>">
                            <a href="<?= $menu['url'] ?>"><i class="<?= $menu['icon'] ?>"></i> <span> <?= $menu['label'] ?> </span></a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>

            </ul>
        </div>
    </div>
</div>

