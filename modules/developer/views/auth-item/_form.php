<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\AuthItem $model */
/** @var yii\widgets\ActiveForm $form */
?>


<?php

$this->registerJs(<<<JS
    // Listen for changes on "All" checkboxes
    document.querySelectorAll('.select-all').forEach(function(selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            // Find all checkboxes in the same row as the "All" checkbox
            let rowCheckboxes = this.closest('tr').querySelectorAll('.form-check-input');
            rowCheckboxes.forEach(function(checkbox) {
                checkbox.checked = selectAllCheckbox.checked;
            });
        });
    });

    // Toggle all checkboxes when the "Toggle All" button is clicked
    document.querySelector('#toggle-all').addEventListener('click', function() {
        let allCheckboxes = document.querySelectorAll('.form-check-input');
        let allChecked = Array.from(allCheckboxes).every(checkbox => checkbox.checked);
        
        allCheckboxes.forEach(function(checkbox) {
            checkbox.checked = !allChecked; // Check if all are checked and toggle
        });
    });
JS);
?>

<div>
    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-create-role']
    ]); ?>

    <div class="card p-3 mb-4">
        <div class="mb-3">
            <label class="form-label">Rule Name</label>
            <?= $form->field($model, 'name')->textInput(['placeholder' => 'Rule Name'])->label(false) ?>
        </div>

        <div class="mb-3">
            <h5>Permissions</h5>
            <button type="button" id="toggle-all" class="btn btn-secondary mb-3">Toggle All</button>
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Section</th>
                            <th>All</th>
                            <th>View</th>
                            <th>Create</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($authItemsGrouped as $modelName => $actions): ?>
                            <tr>
                                <td><?= ucfirst($modelName) ?></td>

                                <!-- "All" checkbox to select/deselect all permissions in the row -->
                                <td><input type="checkbox" class="form-check-input select-all"></td>

                                <!-- Individual action checkboxes for each permission type -->
                                <td>
                                    <input type="checkbox" name="AuthItemForm[children][]" class="form-check-input"
                                        value="<?= $actions['view'] ?? '' ?>"
                                        <?= isset($actions['view']) && in_array($actions['view'], $model->children) ? 'checked' : '' ?>
                                        <?= isset($actions['view']) ? '' : 'disabled' ?>>
                                </td>
                                <td>
                                    <input type="checkbox" name="AuthItemForm[children][]" class="form-check-input"
                                        value="<?= $actions['create'] ?? '' ?>"
                                        <?= isset($actions['create']) && in_array($actions['create'], $model->children) ? 'checked' : '' ?>
                                        <?= isset($actions['create']) ? '' : 'disabled' ?>>
                                </td>
                                <td>
                                    <input type="checkbox" name="AuthItemForm[children][]" class="form-check-input"
                                        value="<?= $actions['update'] ?? '' ?>"
                                        <?= isset($actions['update']) && in_array($actions['update'], $model->children) ? 'checked' : '' ?>
                                        <?= isset($actions['update']) ? '' : 'disabled' ?>>
                                </td>
                                <td>
                                    <input type="checkbox" name="AuthItemForm[children][]" class="form-check-input"
                                        value="<?= $actions['delete'] ?? '' ?>"
                                        <?= isset($actions['delete']) && in_array($actions['delete'], $model->children) ? 'checked' : '' ?>
                                        <?= isset($actions['delete']) ? '' : 'disabled' ?>>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="text-center">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>