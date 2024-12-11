<?php

use yii\helpers\Url;
?>
<div class="main-wrapper">
    <div class="error-box">
        <h1>403</h1>
        <h3 class="h2 mb-3"><i class="fas fa-ban"></i> Access Denied</h3>
        <p class="h4 font-weight-normal">You do not have permission to access this page.</p>
        <a href="<?= Url::to(['/']) ?>" class="btn btn-primary">Back to Home</a>
    </div>
</div>