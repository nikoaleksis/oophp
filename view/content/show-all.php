<?php
if (!$res) {
    return;
}
?>

<h1>Show all content</h1>

<table style="text-align: center;">
    <tr class="first">
        <th>Id</th>
        <th>Title</th>
        <th>Published</th>
        <th>Created</th>
        <th>Updated</th>
        <th>Deleted</th>
        <th>Path</th>
        <th>Slug</th>
    </tr>
<?php foreach ($res as $row) : ?>
    <tr>
        <td><?= $row->id ?></td>
        <td><?= $row->title ?></td>
        <td><?= $row->published ?></td>
        <td><?= $row->created ?></td>
        <td><?= $row->updated ?></td>
        <td><?= $row->deleted ?></td>
        <td><?= $row->path ?></td>
        <td><?= $row->slug ?></td>
    </tr>
<?php endforeach; ?>
</table>
