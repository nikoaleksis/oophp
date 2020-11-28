<?php
if (!$res) {
    return;
}
?>

<h1>Administrate</h1>

<table style="text-align: center;">
    <tr class="first">
        <th>Id</th>
        <th>Title</th>
        <th>Type</th>
        <th>Published</th>
        <th>Created</th>
        <th>Updated</th>
        <th>Deleted</th>
        <th>Actions</th>
    </tr>
<?php $id = -1; foreach ($res as $row) :
    $id++; ?>
    <tr>
        <td style="border-right: 1px solid #eee;"><?= $row->id ?></td>
        <td style="border-right: 1px solid #eee;"><?= $row->title ?></td>
        <td style="border-right: 1px solid #eee;"><?= $row->type ?></td>
        <td style="border-right: 1px solid #eee;"><?= $row->published ?></td>
        <td style="border-right: 1px solid #eee;"><?= $row->created ?></td>
        <td style="border-right: 1px solid #eee;"><?= $row->updated ?></td>
        <td style="border-right: 1px solid #eee;"><?= $row->deleted ?></td>
        <td>
            <a class="icons" href="edit?id=<?= $row->id ?>" title="Edit this content">
                Edit
            </a>
            <a class="icons" href="delete?id=<?= $row->id ?>" title="Edit this content">
                Delete
            </a>
        </td>
    </tr>
<?php endforeach; ?>
</table>
