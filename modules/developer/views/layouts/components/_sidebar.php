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
        'url' => Url::to(['/developer/default/index']),
        'icon' => 'fas fa-tachometer-alt',  // developer icon
        'module' => 'developer',
        'controller' => 'default',
        'action' => 'index',
    ],
    [
        'label' => 'Companies',
        'icon' => 'fas fa-box-open',  // Companies icon
        'submenu' => true,
        'active' => $module === 'developer' && $controller === 'company',
        'items' => [
            [
                'label' => 'Company List',
                'url' => Url::to(['/developer/company/index']),
                'module' => 'developer',
                'controller' => 'company',
                'action' => 'index',
            ],
            [
                'label' => 'Create Company',
                'url' => Url::to(['/developer/company/create']),
                'module' => 'developer',
                'controller' => 'company',
                'action' => 'create',
            ],
        ]
    ],
    [
        'label' => 'Users',
        'icon' => 'fas fa-box-open',  // Users icon
        'submenu' => true,
        'active' => $module === 'developer' && $controller === 'user',
        'items' => [
            [
                'label' => 'User List',
                'url' => Url::to(['/developer/user/index']),
                'module' => 'developer',
                'controller' => 'user',
                'action' => 'index',
            ],
            [
                'label' => 'Create User',
                'url' => Url::to(['/developer/user/create']),
                'module' => 'developer',
                'controller' => 'user',
                'action' => 'create',
            ],
        ]
    ],
    [
        'label' => 'Subscriptions',
        'icon' => 'fas fa-chart-line',  // Subscriptions icon
        'submenu' => true,
        'active' => $module === 'developer' && $controller === 'subscription',
        'items' => [
            [
                'label' => 'Subscriptions List',
                'url' => Url::to(['/developer/subscription/index']),
                'module' => 'developer',
                'controller' => 'subscription',
                'action' => 'index',
            ],
            [
                'label' => 'Create Subscription',
                'url' => Url::to(['/developer/subscription/create']),
                'module' => 'developer',
                'controller' => 'subscription',
                'action' => 'create',
            ],
        ]
    ],
    [
        'label' => 'Auth Items',
        'icon' => 'fas fa-chart-line',  // Auth Items icon
        'submenu' => true,
        'active' => $module === 'developer' && $controller === 'auth-rule',
        'items' => [
            [
                'label' => 'Auth Items List',
                'url' => Url::to(['/developer/auth-item/index']),
                'module' => 'developer',
                'controller' => 'auth-item',
                'action' => 'index',
            ],
            [
                'label' => 'Create Item',
                'url' => Url::to(['/developer/auth-item/create']),
                'module' => 'developer',
                'controller' => 'auth-item',
                'action' => 'create',
            ],
        ]
    ],
    [
        'label' => 'Auth Assignment',
        'icon' => 'fas fa-receipt',  // Expenses icon
        'submenu' => true,
        'active' => $module === 'developer' && $controller === 'auth-assignment',
        'items' => [
            [
                'label' => 'auth-assignment List',
                'url' => Url::to(['/developer/auth-assignment/index']),
                'module' => 'developer',
                'controller' => 'auth-assignment',
                'action' => 'index',
            ],
            [
                'label' => 'Asign User',
                'url' => Url::to(['/developer/auth-assignment/create']),
                'module' => 'developer',
                'controller' => 'auth-assignment',
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

