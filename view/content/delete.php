<form method="post">
    <fieldset>
    <legend>Delete</legend>

    <input type="hidden" name="contentId" value="<?= esc($res->id) ?>"/>

    <p>
        <label>Title:<br>
            <input type="text" name="contentTitle" value="<?= esc($res->title) ?>" readonly/>
        </label>
    </p>

    <p>
        <button type="submit" name="doDelete">Delete</button>
    </p>
    </fieldset>
</form>
