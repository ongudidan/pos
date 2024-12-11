<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<!-- Login 10 - Bootstrap Brain Component -->
<section class=" vh-100 d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
                <div class="mb-5">
                    <h4 class="text-center mb-4">Welcome back!</h4>
                </div>
                <div class="card border border-light-subtle rounded-4">
                    <div class="card-body p-3 p-md-4 p-xl-5">
                        <?php $form = ActiveForm::begin([
                            'id' => 'login-form',
                            'options' => ['class' => 'p-3'],
                        ]); ?>

                        <p class="text-center mb-4" style="font-size: 1.25rem; font-weight: 500; color: #495057;">
                            Sign in using <span class="text-primary">email</span>
                        </p>
                        <div class="row gy-3">
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <?= $form->field($model, 'email')->textInput(['autofocus' => true, 'class' => 'form-control', 'placeholder' => 'name@example.com'])->label('Email', ['class' => 'form-label']) ?>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <?= $form->field($model, 'password')->passwordInput(['class' => 'form-control', 'placeholder' => 'Password'])->label('Password', ['class' => 'form-label']) ?>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <?= $form->field($model, 'rememberMe')->checkbox([
                                        'template' => "<div class=\"form-check\">{input} {label}</div>\n",
                                        'class' => 'form-check-input'
                                    ])->label('Keep me logged in', ['class' => 'form-check-label text-secondary']) ?>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-grid">
                                    <button class="btn btn-primary btn-lg" type="submit">Log in</button>
                                </div>
                            </div>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>