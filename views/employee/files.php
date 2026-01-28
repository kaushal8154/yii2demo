<?php

use yii\helpers\Html;

/* @var $files app\models\File[] */
?>

<h2>My Files</h2>

<?php if (empty($files)): ?>
    <p>No files found.</p>
<?php else: ?>
    <table class="table table-bordered">
        <tr>
            <th>#</th>
            <th>File Name</th>
            <th>Action</th>
        </tr>

        <?php foreach ($files as $i => $file): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= Html::encode($file->filename) ?></td>
                <td>
                    <?= Html::a(
                        'View',
                        Yii::getAlias('@web/uploads/employee_files/' . $file->filename),
                        ['target' => '_blank', 'class' => 'btn btn-sm btn-primary']
                    ) ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
