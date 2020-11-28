<h1>Edit</h1>

<form method="post">
    <fieldset>
    <legend>Edit</legend>
    <input type="hidden" name="contentId" value="<?= esc($res->id) ?>"/>

    <p>
        <label>Title:<br>
        <input type="text" name="contentTitle" value="<?= esc($res->title) ?>"/>
        </label>
    </p>

    <p>
        <label>Path:<br>
        <input type="text" name="contentPath" value="<?= esc($res->path) ?>"/>
    </p>

    <p>
        <label>Slug:<br>
        <input type="text" name="contentSlug" value="<?= esc($res->slug) ?>"/>
    </p>

    <p>
        <label>Text:<br>
        <textarea name="contentData" rows="15" cols="70"><?= esc($res->data) ?></textarea>
     </p>

     <p>
         <label>Type:<br>
         <input type="text" name="contentType" value="<?= esc($res->type) ?>"/>
     </p>

     <p>
         <label>Filter:<br>
         <input type="text" name="contentFilter" value="<?= esc($res->filter) ?>"/>
     </p>

     <p>
         <label>Publish:<br>
         <input type="datetime" name="contentPublish" value="<?= esc($res->published) ?>"/>
     </p>

    <p>
        <button type="submit" name="doSave">Edit</button>
        <button type="submit" name="doDelete">Delete</button>
    </p>
    </fieldset>
</form>
